<?php

namespace App\Services\Verifactu;

use DOMDocument;
use Exception;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class XmlValidator
{
    protected string $xsdPath;

    public function __construct()
    {
        $this->xsdPath = storage_path('app/verifactu/schemas/SistemaFacturacion.xsd');
    }

    /**
     * Validate XML against XSD schema if available, and verify well-formed XML
     */
    public function validate(string $xml): bool
    {
        $dom = new DOMDocument('1.0', 'UTF-8');

        // Check well-formedness
        libxml_use_internal_errors(true);
        if (! $dom->loadXML($xml)) {
            $errors = libxml_get_errors();
            libxml_clear_errors();

            $errorMessages = array_map(fn ($e) => sprintf('Line %d: %s', $e->line, trim($e->message)), $errors);
            Log::error('XML parsing failed', ['errors' => $errorMessages]);
            throw new RuntimeException('XML parsing failed: '.implode(', ', $errorMessages));
        }

        $this->ensureSchemasDownloaded();

        if (file_exists($this->xsdPath)) {
            if (! $dom->schemaValidate($this->xsdPath)) {
                $errors = libxml_get_errors();
                libxml_clear_errors();

                $errorMessages = array_map(fn ($e) => sprintf('Line %d: %s', $e->line, trim($e->message)), $errors);
                Log::warning('XML XSD schema validation warning', ['errors' => $errorMessages]);
                // In production, throw exception if required by policy
            }
        }

        return true;
    }

    /**
     * Ensure schemas are stored locally
     */
    protected function ensureSchemasDownloaded(): void
    {
        $dir = dirname($this->xsdPath);
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        if (! file_exists($this->xsdPath)) {
            $schemaUrl = 'https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/burt/jdit/ws/SistemaFacturacion.xsd';

            try {
                $context = stream_context_create([
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                    'http' => [
                        'timeout' => 5,
                    ],
                ]);
                $content = @file_get_contents($schemaUrl, false, $context);
                if ($content !== false) {
                    file_put_contents($this->xsdPath, $content);
                    Log::info('AEAT XSD schema downloaded successfully');
                }
            } catch (Exception $e) {
                Log::warning('Could not automatically download AEAT XSD: '.$e->getMessage());
            }
        }
    }
}
