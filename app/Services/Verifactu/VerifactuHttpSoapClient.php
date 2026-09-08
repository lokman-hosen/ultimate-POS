<?php

namespace App\Services\Verifactu;

use Exception;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class VerifactuHttpSoapClient
{
    protected string $endpoint;
    protected string $certPath;
    protected string $certPassword;
    protected string $environment;
    protected int $timeout;
    protected ?string $lastResponse = null;
    protected ?string $lastRequest = null;

    public function __construct(
        string $endpoint,
        string $certPath = '',
        string $certPassword = '',
        string $environment = 'test',
        int $timeout = 60
    ) {
        $this->endpoint = $endpoint;
        $this->certPath = $certPath;
        $this->certPassword = $certPassword;
        $this->environment = $environment;
        $this->timeout = $timeout;
    }

    /**
     * Send invoice registration request
     */
    public function RegFactuSistemaFacturacion($params)
    {
        $xmlContent = is_array($params) ? ($params['RegistroFactura'] ?? '') : (string) $params;

        $soapEnvelope = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sist="https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/burt/jdit/ws/SistemaFacturacion.xsd">
   <soapenv:Header/>
   <soapenv:Body>
      {$xmlContent}
   </soapenv:Body>
</soapenv:Envelope>
XML;

        return $this->sendRequest('RegFactuSistemaFacturacion', $soapEnvelope);
    }

    /**
     * Check invoice status query
     */
    public function ConsultaFactuSistemaFacturacion(array $params)
    {
        $serie = htmlspecialchars($params['SerieFactura'] ?? '', ENT_XML1);
        $numero = htmlspecialchars($params['NumFactura'] ?? '', ENT_XML1);
        $fecha = htmlspecialchars($params['FechaExpedicion'] ?? '', ENT_XML1);

        $soapEnvelope = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sist="https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/burt/jdit/ws/SistemaFacturacion.xsd">
   <soapenv:Header/>
   <soapenv:Body>
      <sist:ConsultaFactuSistemaFacturacion>
         <sist:SerieFactura>{$serie}</sist:SerieFactura>
         <sist:NumFactura>{$numero}</sist:NumFactura>
         <sist:FechaExpedicion>{$fecha}</sist:FechaExpedicion>
      </sist:ConsultaFactuSistemaFacturacion>
   </soapenv:Body>
</soapenv:Envelope>
XML;

        return $this->sendRequest('ConsultaFactuSistemaFacturacion', $soapEnvelope);
    }

    /**
     * Cancel an invoice
     */
    public function RegAnulacionFactuSistemaFacturacion($params)
    {
        $xmlContent = is_array($params) ? ($params['AnulacionFactura'] ?? '') : (string) $params;

        $soapEnvelope = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sist="https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/burt/jdit/ws/SistemaFacturacion.xsd">
   <soapenv:Header/>
   <soapenv:Body>
      {$xmlContent}
   </soapenv:Body>
</soapenv:Envelope>
XML;

        return $this->sendRequest('RegAnulacionFactuSistemaFacturacion', $soapEnvelope);
    }

    public function __getLastResponse(): ?string
    {
        return $this->lastResponse;
    }

    public function __getLastRequest(): ?string
    {
        return $this->lastRequest;
    }

    /**
     * Execute SOAP request via cURL with SSL certificate handling
     */
    protected function sendRequest(string $action, string $envelope)
    {
        $this->lastRequest = $envelope;

        $headers = [
            'Content-Type: text/xml; charset=utf-8',
            'SOAPAction: "'.$action.'"',
            'Content-Length: '.strlen($envelope),
        ];

        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $envelope);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);

        if ($this->environment === 'production') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        } else {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }

        // Configure client certificate if present
        if (! empty($this->certPath) && file_exists($this->certPath)) {
            $ext = strtolower(pathinfo($this->certPath, PATHINFO_EXTENSION));
            if ($ext === 'p12' || $ext === 'pfx') {
                curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'P12');
                curl_setopt($ch, CURLOPT_SSLCERT, $this->certPath);
                if (! empty($this->certPassword)) {
                    curl_setopt($ch, CURLOPT_KEYPASSWD, $this->certPassword);
                }
            } else {
                curl_setopt($ch, CURLOPT_SSLCERT, $this->certPath);
                if (! empty($this->certPassword)) {
                    curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $this->certPassword);
                }
            }
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            Log::error('AEAT SOAP cURL error', ['error' => $error]);
            throw new RuntimeException("AEAT Connection Error: {$error}");
        }

        $this->lastResponse = $response;

        return $this->parseXmlResponse($response);
    }

    /**
     * Parse XML response to associative array / object
     */
    protected function parseXmlResponse(string $xml)
    {
        try {
            // Strip SOAP envelope tags if present for easy parsing
            $cleanXml = preg_replace('/(<\/?)(\w+):([^>]*>)/', '$1$3', $xml);
            $parsed = simplexml_load_string($cleanXml, 'SimpleXMLElement', LIBXML_NOCDATA);
            $json = json_encode($parsed);

            return json_decode($json, true);
        } catch (Exception $e) {
            return ['raw_response' => $xml];
        }
    }
}
