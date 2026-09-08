<?php

namespace App\Services\Verifactu;

use App\Transaction;
use DOMDocument;
use DOMElement;

class XmlGenerator
{
    /**
     * Generate standard AEAT VERI*FACTU XML for a transaction
     */
    public function generateInvoiceXml($invoice, array $options = []): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        // Root element - RegistroFactura
        $root = $dom->createElementNS(
            'https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/burt/jdit/ws/SistemaFacturacion.xsd',
            'RegistroFactura'
        );
        $dom->appendChild($root);

        // Extract series & number
        $serie = $options['serie'] ?? $this->extractSerie($invoice);
        $numero = $options['numero'] ?? $this->extractNumero($invoice);

        // 1. Cabecera
        $this->addHeader($dom, $root, $invoice, $serie, $numero);

        // 2. Emisor / ObligadoEmision
        $this->addIssuer($dom, $root, $invoice);

        // 3. Cliente (if applicable)
        $this->addCustomer($dom, $root, $invoice);

        // 4. Lineas
        $this->addLines($dom, $root, $invoice);

        // 5. Totales
        $this->addTotals($dom, $root, $invoice);

        // 6. Datos Fiscales & Hash Chain
        $this->addFiscalInfo($dom, $root, $invoice, $options);

        // 7. Sistema Informático
        $this->addSoftwareInfo($dom, $root);

        // 8. Signature placeholder
        $this->addSignaturePlaceholder($dom, $root);

        return $dom->saveXML();
    }

    /**
     * Header information
     */
    protected function addHeader(DOMDocument $dom, DOMElement $root, $invoice, string $serie, string $numero): void
    {
        $header = $dom->createElement('Cabecera');

        $date = $invoice->transaction_date ? \Carbon\Carbon::parse($invoice->transaction_date) : now();

        $fields = [
            'IDVersion' => '1.0',
            'SerieFactura' => $serie,
            'NumFactura' => $numero,
            'FechaExpedicion' => $date->format('d-m-Y'),
            'HoraExpedicion' => $date->format('H:i:s'),
            'TipoFactura' => $this->getInvoiceType($invoice),
            'ClaveRegimenIVA' => $this->getIvaRegime($invoice),
        ];

        foreach ($fields as $key => $value) {
            if ($value !== null && $value !== '') {
                $element = $dom->createElement($key, (string) $value);
                $header->appendChild($element);
            }
        }

        $root->appendChild($header);
    }

    /**
     * Obligado a emitir factura (Business)
     */
    protected function addIssuer(DOMDocument $dom, DOMElement $root, $invoice): void
    {
        $issuer = $dom->createElement('ObligadoEmision');

        $business = $invoice->business ?? null;
        $taxNumber = $business->tax_number_1 ?? $business->tax_number_2 ?? config('verifactu.collaborator.nif', 'B00000000');
        $name = $business->name ?? config('verifactu.collaborator.name', 'Business');

        $issuer->appendChild($dom->createElement('NIF', $taxNumber));
        $issuer->appendChild($dom->createElement('NombreRazon', $name));

        // Representation model B: Colaboración Social
        if (config('verifactu.representation_model') === 'B') {
            $collabNif = config('verifactu.collaborator.nif');
            $collabName = config('verifactu.collaborator.name');
            if (! empty($collabNif)) {
                $rep = $dom->createElement('Representante');
                $rep->appendChild($dom->createElement('NIF', $collabNif));
                if (! empty($collabName)) {
                    $rep->appendChild($dom->createElement('NombreRazon', $collabName));
                }
                $issuer->appendChild($rep);
            }
        }

        $root->appendChild($issuer);
    }

    /**
     * Customer details
     */
    protected function addCustomer(DOMDocument $dom, DOMElement $root, $invoice): void
    {
        $contact = $invoice->contact ?? null;
        if (! $contact || $contact->is_default == 1) {
            // Simplified invoices (F2) do not mandate customer block
            return;
        }

        $customer = $dom->createElement('Cliente');

        $customerData = [
            'NIF' => $contact->tax_number ?? '',
            'NombreRazon' => $contact->supplier_business_name ?: ($contact->name ?? ''),
        ];

        if (! empty($customerData['NIF'])) {
            $customer->appendChild($dom->createElement('NIF', $customerData['NIF']));
        }
        if (! empty($customerData['NombreRazon'])) {
            $customer->appendChild($dom->createElement('NombreRazon', $customerData['NombreRazon']));
        }

        $root->appendChild($customer);
    }

    /**
     * Invoice lines
     */
    protected function addLines(DOMDocument $dom, DOMElement $root, $invoice): void
    {
        $lines = $dom->createElement('Lineas');

        $sellLines = $invoice->sell_lines ?? collect([]);
        if ($sellLines->isEmpty()) {
            // Create at least one fallback line matching total
            $line = $dom->createElement('Linea');
            $line->appendChild($dom->createElement('NumLinea', '1'));
            $line->appendChild($dom->createElement('Descripcion', 'General Sale'));
            $line->appendChild($dom->createElement('Cantidad', '1.00'));
            $line->appendChild($dom->createElement('PrecioUnitario', number_format((float) ($invoice->total_before_tax ?? $invoice->final_total ?? 0), 2, '.', '')));
            $line->appendChild($dom->createElement('BaseImponible', number_format((float) ($invoice->total_before_tax ?? $invoice->final_total ?? 0), 2, '.', '')));
            $line->appendChild($dom->createElement('TipoIVA', number_format((float) ($invoice->tax->amount ?? 21.0), 2, '.', '')));
            $line->appendChild($dom->createElement('CuotaIVA', number_format((float) ($invoice->tax_amount ?? 0), 2, '.', '')));
            $lines->appendChild($line);
        } else {
            foreach ($sellLines->take(12) as $index => $item) {
                $line = $dom->createElement('Linea');

                $qty = (float) ($item->quantity ?? 1);
                $unitPrice = (float) ($item->unit_price ?? 0);
                $subtotal = $qty * $unitPrice;
                $taxRate = $item->line_tax ? (float) $item->line_tax->amount : ($invoice->tax ? (float) $invoice->tax->amount : 21.0);
                $taxAmount = (float) ($item->item_tax ? ($item->item_tax * $qty) : ($subtotal * ($taxRate / 100)));

                $productName = $item->product ? $item->product->name : 'Product';

                $lineData = [
                    'NumLinea' => (string) ($index + 1),
                    'Descripcion' => substr($productName, 0, 100),
                    'Cantidad' => number_format($qty, 2, '.', ''),
                    'PrecioUnitario' => number_format($unitPrice, 2, '.', ''),
                    'BaseImponible' => number_format($subtotal, 2, '.', ''),
                    'TipoIVA' => number_format($taxRate, 2, '.', ''),
                    'CuotaIVA' => number_format($taxAmount, 2, '.', ''),
                    'TipoRecargoEquivalencia' => '0.00',
                    'CuotaRecargoEquivalencia' => '0.00',
                    'PorcentajeRetencion' => '0.00',
                    'CuotaRetencion' => '0.00',
                ];

                foreach ($lineData as $key => $value) {
                    $element = $dom->createElement($key, $value);
                    $line->appendChild($element);
                }

                $lines->appendChild($line);
            }
        }

        $root->appendChild($lines);
    }

    /**
     * Totals block
     */
    protected function addTotals(DOMDocument $dom, DOMElement $root, $invoice): void
    {
        $totals = $dom->createElement('Totales');

        $total = (float) ($invoice->final_total ?? $invoice->total ?? 0);
        $subtotal = (float) ($invoice->total_before_tax ?? $invoice->subtotal ?? ($total - ($invoice->tax_amount ?? 0)));
        $taxAmount = (float) ($invoice->tax_amount ?? ($total - $subtotal));

        $totalData = [
            'ImporteTotal' => number_format($total, 2, '.', ''),
            'BaseImponible' => number_format($subtotal, 2, '.', ''),
            'CuotaIVA' => number_format($taxAmount, 2, '.', ''),
            'CuotaRecargoEquivalencia' => '0.00',
            'ImporteRetencion' => '0.00',
            'ImporteTotalConRetencion' => number_format($total, 2, '.', ''),
            'TipoCambio' => number_format((float) ($invoice->exchange_rate ?? 1.0), 4, '.', ''),
        ];

        foreach ($totalData as $key => $value) {
            $element = $dom->createElement($key, $value);
            $totals->appendChild($element);
        }

        $root->appendChild($totals);
    }

    /**
     * Fiscal data and hash chaining
     */
    protected function addFiscalInfo(DOMDocument $dom, DOMElement $root, $invoice, array $options): void
    {
        $fiscal = $dom->createElement('DatosFiscales');

        if (! empty($options['previous_hash'])) {
            $hash = $dom->createElement('HashAnterior', $options['previous_hash']);
            $fiscal->appendChild($hash);
        }

        if (! empty($options['current_hash'])) {
            $hash = $dom->createElement('HashRegistro', $options['current_hash']);
            $fiscal->appendChild($hash);
        }

        $root->appendChild($fiscal);
    }

    /**
     * Software information required by AEAT anti-fraud regulation
     */
    protected function addSoftwareInfo(DOMDocument $dom, DOMElement $root): void
    {
        $sysInfo = config('verifactu.system_info', []);
        $system = $dom->createElement('SistemaInformatico');

        $system->appendChild($dom->createElement('NombreRazon', config('verifactu.collaborator.name', 'UltimatePOS')));
        $system->appendChild($dom->createElement('NIF', config('verifactu.collaborator.nif', 'B00000000')));
        $system->appendChild($dom->createElement('NombreSistemaInformatico', $sysInfo['software_name'] ?? 'UltimatePOS'));
        $system->appendChild($dom->createElement('IdSistemaInformatico', $sysInfo['software_id'] ?? 'UPOS-VERIFACTU-01'));
        $system->appendChild($dom->createElement('Version', $sysInfo['version'] ?? '1.0'));
        $system->appendChild($dom->createElement('NumeroInstalacion', $sysInfo['install_id'] ?? '01'));

        $root->appendChild($system);
    }

    /**
     * XAdES Signature element placeholder
     */
    protected function addSignaturePlaceholder(DOMDocument $dom, DOMElement $root): void
    {
        $signature = $dom->createElementNS('http://www.w3.org/2000/09/xmldsig#', 'Signature');
        $signature->setAttribute('Id', 'SignaturePlaceholder');
        $root->appendChild($signature);
    }

    /**
     * Determine invoice type: F1 (standard), F2 (simplified / ticket), R1 (rectification / return)
     */
    public function getInvoiceType($invoice): string
    {
        if (isset($invoice->type) && $invoice->type === 'sell_return') {
            return 'R1';
        }

        // Check if simplified (Walk-in customer or no tax number)
        $isSimplified = false;
        if (isset($invoice->is_simplified)) {
            $isSimplified = (bool) $invoice->is_simplified;
        } elseif (isset($invoice->contact)) {
            $isSimplified = empty($invoice->contact->tax_number) || $invoice->contact->is_default == 1;
        }

        return $isSimplified ? 'F2' : 'F1';
    }

    /**
     * VAT Regime
     */
    public function getIvaRegime($invoice): string
    {
        return $invoice->iva_regime ?? '01';
    }

    /**
     * Extract series from invoice_no
     */
    public function extractSerie($invoice): string
    {
        if (! empty($invoice->invoice_series)) {
            return (string) $invoice->invoice_series;
        }

        $invoiceNo = $invoice->invoice_no ?? '';
        if (strpos($invoiceNo, '-') !== false) {
            $parts = explode('-', $invoiceNo);
            if (count($parts) > 1) {
                return $parts[0];
            }
        } elseif (strpos($invoiceNo, '/') !== false) {
            $parts = explode('/', $invoiceNo);
            if (count($parts) > 1) {
                return $parts[0];
            }
        }

        return 'F';
    }

    /**
     * Extract number from invoice_no
     */
    public function extractNumero($invoice): string
    {
        $invoiceNo = $invoice->invoice_no ?? '';
        if (strpos($invoiceNo, '-') !== false) {
            $parts = explode('-', $invoiceNo);
            if (count($parts) > 1) {
                return implode('-', array_slice($parts, 1));
            }
        } elseif (strpos($invoiceNo, '/') !== false) {
            $parts = explode('/', $invoiceNo);
            if (count($parts) > 1) {
                return implode('/', array_slice($parts, 1));
            }
        }

        return ! empty($invoiceNo) ? (string) $invoiceNo : '0001';
    }
}
