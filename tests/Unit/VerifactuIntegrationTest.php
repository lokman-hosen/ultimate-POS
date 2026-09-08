<?php

namespace Tests\Unit;

use App\Business;
use App\Contact;
use App\Models\VerifactuHashChain;
use App\Models\VerifactuRecord;
use App\Models\VerifactuResponse;
use App\Product;
use App\Services\Verifactu\HashChainService;
use App\Services\Verifactu\SignatureService;
use App\Services\Verifactu\VerifactuService;
use App\Services\Verifactu\XmlGenerator;
use App\TaxRate;
use App\Transaction;
use App\TransactionSellLine;
use Tests\TestCase;

class VerifactuIntegrationTest extends TestCase
{
    public function test_hash_chain_service_generates_consistent_sha256()
    {
        $hashService = new HashChainService();
        $data = [
            'serie' => 'F',
            'numero' => 'INV-001',
            'fecha_expedicion' => '2026-09-08',
            'importe_total' => '121.00',
            'hash_anterior' => null,
        ];

        $hash1 = $hashService->generateHash($data);
        $hash2 = $hashService->generateHash($data);

        $this->assertNotEmpty($hash1);
        $this->assertEquals($hash1, $hash2);
        $this->assertEquals(64, strlen($hash1));
    }

    public function test_xml_generator_creates_valid_registro_factura_xml()
    {
        $xmlGenerator = new XmlGenerator();

        $business = new Business();
        $business->forceFill([
            'id' => 1,
            'name' => 'Demo Company S.L.',
            'tax_number_1' => 'B12345678',
        ]);

        $contact = new Contact();
        $contact->forceFill([
            'id' => 10,
            'name' => 'John Doe',
            'supplier_business_name' => 'Client Company S.L.',
            'tax_number' => 'A98765432',
            'is_default' => 0,
        ]);

        $tax = new TaxRate();
        $tax->forceFill([
            'id' => 2,
            'amount' => 21.00,
        ]);

        $product = new Product();
        $product->forceFill([
            'id' => 5,
            'name' => 'Premium POS Software',
        ]);

        $sellLine = new TransactionSellLine();
        $sellLine->forceFill([
            'id' => 1,
            'quantity' => 2.00,
            'unit_price' => 50.00,
            'item_tax' => 10.50,
        ]);
        $sellLine->setRelation('product', $product);
        $sellLine->setRelation('line_tax', $tax);

        $transaction = new Transaction();
        $transaction->forceFill([
            'id' => 99999,
            'business_id' => 1,
            'location_id' => 1,
            'type' => 'sell',
            'status' => 'final',
            'invoice_no' => 'FAC-2026-0001',
            'transaction_date' => '2026-09-08 10:00:00',
            'total_before_tax' => 100.00,
            'tax_amount' => 21.00,
            'final_total' => 121.00,
        ]);
        $transaction->setRelation('business', $business);
        $transaction->setRelation('sell_lines', collect([$sellLine]));
        $transaction->setRelation('contact', $contact);
        $transaction->setRelation('tax', $tax);

        $xml = $xmlGenerator->generateInvoiceXml($transaction, [
            'previous_hash' => 'ABCDEF1234567890',
            'current_hash' => '1234567890ABCDEF',
        ]);

        $this->assertStringContainsString('RegistroFactura', $xml);
        $this->assertStringContainsString('Cabecera', $xml);
        $this->assertStringContainsString('SerieFactura>FAC<', $xml);
        $this->assertStringContainsString('NumFactura>2026-0001<', $xml);
        $this->assertStringContainsString('TipoFactura>F1<', $xml);
        $this->assertStringContainsString('ObligadoEmision', $xml);
        $this->assertStringContainsString('B12345678', $xml);
        $this->assertStringContainsString('Demo Company S.L.', $xml);
        $this->assertStringContainsString('Cliente', $xml);
        $this->assertStringContainsString('A98765432', $xml);
        $this->assertStringContainsString('Client Company S.L.', $xml);
        $this->assertStringContainsString('Lineas', $xml);
        $this->assertStringContainsString('Premium POS Software', $xml);
        $this->assertStringContainsString('Totales', $xml);
        $this->assertStringContainsString('121.00', $xml);
        $this->assertStringContainsString('100.00', $xml);
        $this->assertStringContainsString('ABCDEF1234567890', $xml);
        $this->assertStringContainsString('SistemaInformatico', $xml);
        $this->assertStringContainsString('SignaturePlaceholder', $xml);
    }

    public function test_signature_service_creates_xades_envelope()
    {
        $signatureService = new SignatureService();

        $xml = '<?xml version="1.0" encoding="UTF-8"?><RegistroFactura><Signature Id="SignaturePlaceholder"/></RegistroFactura>';
        $signedXml = $signatureService->signXml($xml, 'SignaturePlaceholder');

        $this->assertStringContainsString('http://www.w3.org/2000/09/xmldsig#', $signedXml);
        $this->assertStringContainsString('SignedInfo', $signedXml);
        $this->assertStringContainsString('CanonicalizationMethod', $signedXml);
        $this->assertStringContainsString('SignatureMethod', $signedXml);
        $this->assertStringContainsString('DigestMethod', $signedXml);
        $this->assertStringContainsString('DigestValue', $signedXml);
        $this->assertStringContainsString('SignatureValue', $signedXml);
        $this->assertStringContainsString('KeyInfo', $signedXml);
    }

    public function test_verifactu_service_cancellation_xml_generation()
    {
        $service = app(VerifactuService::class);
        $record = new VerifactuRecord();
        $record->forceFill([
            'serie' => 'F',
            'numero' => 'INV-001',
            'fecha_expedicion' => '2026-09-08',
        ]);

        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('generateCancellationXml');
        $method->setAccessible(true);

        $xml = $method->invoke($service, $record, 'Customer request');

        $this->assertStringContainsString('AnulacionFactura', $xml);
        $this->assertStringContainsString('INV-001', $xml);
        $this->assertStringContainsString('Customer request', $xml);
    }

    public function test_verifactu_response_dto_parsing()
    {
        $payload = [
            'EstadoEnvio' => 'Correcta',
            'CSV' => 'CSV-AEAT-123456789',
            'IDFactura' => 'AEAT-UUID-999',
        ];

        $response = VerifactuResponse::fromArray($payload);

        $this->assertTrue($response->isSuccess);
        $this->assertEquals('Correcta', $response->status);
        $this->assertEquals('CSV-AEAT-123456789', $response->csv);
        $this->assertEquals('AEAT-UUID-999', $response->uuid);
    }
}
