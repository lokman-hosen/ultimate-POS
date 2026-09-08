<?php

namespace App\Services\Verifactu;

use App\Models\VerifactuHashChain;
use App\Models\VerifactuRecord;
use App\Models\VerifactuSubmission;
use App\Transaction;
use DOMDocument;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class VerifactuService
{
    protected SoapClientFactory $soapFactory;
    protected XmlGenerator $xmlGenerator;
    protected XmlValidator $xmlValidator;
    protected SignatureService $signatureService;
    protected HashChainService $hashChainService;

    public function __construct(
        SoapClientFactory $soapFactory,
        XmlGenerator $xmlGenerator,
        XmlValidator $xmlValidator,
        SignatureService $signatureService,
        HashChainService $hashChainService
    ) {
        $this->soapFactory = $soapFactory;
        $this->xmlGenerator = $xmlGenerator;
        $this->xmlValidator = $xmlValidator;
        $this->signatureService = $signatureService;
        $this->hashChainService = $hashChainService;
    }

    /**
     * Submit an invoice transaction to AEAT VERI*FACTU
     *
     * @param  Transaction|int  $invoice
     */
    public function submitInvoice($invoice, array $options = []): VerifactuRecord
    {
        if (is_numeric($invoice)) {
            $invoice = Transaction::with(['business', 'location', 'contact', 'sell_lines.product', 'sell_lines.line_tax', 'tax'])->findOrFail($invoice);
        } else {
            $invoice->loadMissing(['business', 'location', 'contact', 'sell_lines.product', 'sell_lines.line_tax', 'tax']);
        }

        return DB::transaction(function () use ($invoice, $options) {
            $serie = $options['serie'] ?? $this->xmlGenerator->extractSerie($invoice);
            $numero = $options['numero'] ?? $this->xmlGenerator->extractNumero($invoice);
            $expeditionDate = $invoice->transaction_date ? \Carbon\Carbon::parse($invoice->transaction_date)->format('Y-m-d') : now()->format('Y-m-d');

            // 1. Get previous hash for chain
            $previousHash = $this->hashChainService->getPreviousHash(
                $invoice->business_id,
                $invoice->location_id,
                $serie
            );

            // 2. Create or find existing pending record
            $record = VerifactuRecord::where('transaction_id', $invoice->id)->first();
            if (! $record) {
                $record = new VerifactuRecord();
                $record->transaction_id = $invoice->id;
                $record->invoice_id = $invoice->id;
            }

            $record->business_id = $invoice->business_id;
            $record->location_id = $invoice->location_id;
            $record->serie = $serie;
            $record->numero = $numero;
            $record->fecha_expedicion = $expeditionDate;
            $record->hash_anterior = $previousHash;
            $record->submission_status = 'processing';
            $record->created_by = $invoice->created_by ?? auth()->id() ?? null;
            $record->save();

            // 3. Generate XML
            $xmlOptions = array_merge($options, [
                'serie' => $serie,
                'numero' => $numero,
                'previous_hash' => $previousHash,
            ]);
            $xml = $this->xmlGenerator->generateInvoiceXml($invoice, $xmlOptions);

            // 4. Validate XML against XSD
            $this->xmlValidator->validate($xml);

            // 5. Generate current hash
            $hashData = [
                'serie' => $record->serie,
                'numero' => $record->numero,
                'fecha_expedicion' => $record->fecha_expedicion->format('Y-m-d'),
                'importe_total' => number_format((float) ($invoice->final_total ?? $invoice->total ?? 0), 2, '.', ''),
                'hash_anterior' => $previousHash,
            ];
            $currentHash = $this->hashChainService->generateHash($hashData);
            $record->hash_registro = $currentHash;

            // 6. Sign the XML (XAdES)
            $signedXml = $this->signatureService->signXml($xml, 'SignaturePlaceholder');
            $record->xml_content = $signedXml;
            $record->save();

            // 7. Create hash chain entry
            $this->hashChainService->createChainEntry($record, $hashData);

            // 8. Prepare SOAP request
            $soapClient = $this->soapFactory->create();

            // 9. Create submission record
            $submission = VerifactuSubmission::create([
                'record_id' => $record->id,
                'operation_type' => 'send',
                'request_xml' => $signedXml,
                'soap_action' => 'RegFactuSistemaFacturacion',
                'attempt' => 1,
            ]);

            try {
                // 10. Send via SOAP
                $response = $soapClient->RegFactuSistemaFacturacion([
                    'RegistroFactura' => $signedXml,
                ]);

                // 11. Process response
                $this->processResponse($record, $submission, $response, $soapClient);

                $record->submitted_at = now();
                $record->save();

            } catch (Exception $e) {
                Log::error('Verifactu submission failed', [
                    'transaction_id' => $invoice->id,
                    'error' => $e->getMessage(),
                ]);

                $submission->update([
                    'success' => false,
                    'error_message' => $e->getMessage(),
                ]);

                $record->update([
                    'submission_status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);

                throw $e;
            }

            return $record;
        });
    }

    /**
     * Check status of a submitted invoice
     */
    public function checkStatus(VerifactuRecord $record): array
    {
        $soapClient = $this->soapFactory->create();

        $submission = VerifactuSubmission::create([
            'record_id' => $record->id,
            'operation_type' => 'status_check',
            'soap_action' => 'ConsultaFactuSistemaFacturacion',
            'attempt' => 1,
        ]);

        try {
            $response = $soapClient->ConsultaFactuSistemaFacturacion([
                'SerieFactura' => $record->serie ?? '',
                'NumFactura' => $record->numero,
                'FechaExpedicion' => $record->fecha_expedicion ? $record->fecha_expedicion->format('d-m-Y') : now()->format('d-m-Y'),
            ]);

            $this->processStatusResponse($record, $response);

            $lastResponse = method_exists($soapClient, '__getLastResponse') ? $soapClient->__getLastResponse() : null;

            $submission->update([
                'success' => true,
                'response_data' => $this->soapToArray($response),
                'response_xml' => $lastResponse,
            ]);

            return $this->soapToArray($response);

        } catch (Exception $e) {
            $submission->update([
                'success' => false,
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Cancel an invoice
     */
    public function cancelInvoice(VerifactuRecord $record, string $reason): bool
    {
        return DB::transaction(function () use ($record, $reason) {
            $soapClient = $this->soapFactory->create();

            $submission = VerifactuSubmission::create([
                'record_id' => $record->id,
                'operation_type' => 'cancel',
                'soap_action' => 'RegAnulacionFactuSistemaFacturacion',
                'attempt' => 1,
            ]);

            try {
                // Generate cancellation XML
                $xml = $this->generateCancellationXml($record, $reason);

                $response = $soapClient->RegAnulacionFactuSistemaFacturacion([
                    'AnulacionFactura' => $xml,
                ]);

                $responseArray = $this->soapToArray($response);
                $lastResponse = method_exists($soapClient, '__getLastResponse') ? $soapClient->__getLastResponse() : null;

                $isAccepted = (isset($responseArray['EstadoAnulacion']) && $responseArray['EstadoAnulacion'] === 'Aceptada')
                    || (isset($responseArray['EstadoEnvio']) && in_array($responseArray['EstadoEnvio'], ['Correcta', 'Aceptada']));

                if ($isAccepted) {
                    $record->update([
                        'aeat_status' => 'Anulada',
                        'submission_status' => 'success',
                        'response_payload' => $responseArray,
                    ]);

                    $submission->update([
                        'success' => true,
                        'response_data' => $responseArray,
                        'response_xml' => $lastResponse,
                    ]);

                    return true;
                }

                $errorReason = $responseArray['MotivoAnulacion'] ?? $responseArray['DescripcionError'] ?? 'Cancellation rejected by AEAT';
                throw new RuntimeException("Cancellation rejected: {$errorReason}");

            } catch (Exception $e) {
                $submission->update([
                    'success' => false,
                    'error_message' => $e->getMessage(),
                ]);

                throw $e;
            }
        });
    }

    /**
     * Process SOAP response
     */
    protected function processResponse(VerifactuRecord $record, VerifactuSubmission $submission, $response, $soapClient): void
    {
        $responseArray = $this->soapToArray($response);
        $lastResponse = method_exists($soapClient, '__getLastResponse') ? $soapClient->__getLastResponse() : null;

        $submission->update([
            'response_data' => $responseArray,
            'response_xml' => $lastResponse,
            'success' => true,
        ]);

        // Extract CSV and UUID
        $csv = $responseArray['CSV'] ?? $responseArray['RespuestaLinea']['CSV'] ?? null;
        $uuid = $responseArray['IDFactura'] ?? $responseArray['RespuestaLinea']['IDFactura'] ?? null;

        if ($csv) {
            $record->csv = (string) $csv;
        }

        if ($uuid) {
            $record->uuid = (string) $uuid;
        }

        // Get status
        $status = $responseArray['EstadoEnvio'] ?? $responseArray['EstadoFactura'] ?? 'Correcta';
        $record->aeat_status = (string) $status;
        $record->submission_status = 'success';
        $record->response_payload = $responseArray;
        $record->save();

        Log::info('Verifactu submission successful', [
            'record_id' => $record->id,
            'csv' => $csv,
            'uuid' => $uuid,
            'status' => $status,
        ]);
    }

    /**
     * Process status check response
     */
    protected function processStatusResponse(VerifactuRecord $record, $response): void
    {
        $responseArray = $this->soapToArray($response);

        if (isset($responseArray['EstadoFactura'])) {
            $record->aeat_status = (string) $responseArray['EstadoFactura'];
            $record->status_checked_at = now();
            $record->response_payload = $responseArray;
            $record->save();

            Log::info('Verifactu status updated', [
                'record_id' => $record->id,
                'status' => $responseArray['EstadoFactura'],
            ]);
        }
    }

    /**
     * Convert SOAP response to array (handles stdClass & SimpleXMLElement)
     */
    public function soapToArray($response): array
    {
        if (is_object($response) && method_exists($response, 'getArrayCopy')) {
            return $response->getArrayCopy();
        }

        if (is_object($response)) {
            return json_decode(json_encode($response), true);
        }

        return is_array($response) ? $response : [];
    }

    /**
     * Generate cancellation XML
     */
    protected function generateCancellationXml(VerifactuRecord $record, string $reason): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $root = $dom->createElement('AnulacionFactura');
        $dom->appendChild($root);

        $fields = [
            'SerieFactura' => $record->serie ?? '',
            'NumFactura' => $record->numero,
            'FechaExpedicion' => $record->fecha_expedicion ? $record->fecha_expedicion->format('d-m-Y') : now()->format('d-m-Y'),
            'MotivoAnulacion' => $reason,
        ];

        foreach ($fields as $key => $value) {
            $element = $dom->createElement($key, (string) $value);
            $root->appendChild($element);
        }

        return $dom->saveXML();
    }
}
