<?php

namespace App\Services\Verifactu;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use SoapClient;

class SoapClientFactory
{
    protected array $config;
    protected $client = null;

    public function __construct(array $config = [])
    {
        $this->config = $config ?: config('verifactu', []);
    }

    /**
     * Create or retrieve the SOAP client instance / adapter
     */
    public function create()
    {
        if ($this->client !== null) {
            return $this->client;
        }

        $environment = $this->config['environment'] ?? 'test';
        $wsdl = $this->config['urls'][$environment]['wsdl'] ?? storage_path('app/verifactu/wsdl/SistemaFacturacion.wsdl');
        $endpoint = $this->config['urls'][$environment]['endpoint'] ?? 'https://prewww1.aeat.es/wlpl/TIKE-CONT/ws/SistemaFacturacion/VerifactuSOAP';

        // Check if WSDL file exists, attempt download if missing
        if (! file_exists($wsdl)) {
            $this->downloadWsdl($wsdl);
        }

        $certPath = $this->config['certificate']['path'] ?? '';
        $certPassword = $this->config['certificate']['password'] ?? '';
        $timeout = $this->config['timeout'] ?? 60;

        if (class_exists(SoapClient::class)) {
            $options = [
                'trace' => true,
                'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'location' => $endpoint,
                'uri' => 'https://www2.agenciatributaria.gob.es',
                'soap_version' => SOAP_1_1,
                'encoding' => 'UTF-8',
                'connection_timeout' => $timeout,
            ];

            if (! empty($certPath) && file_exists($certPath)) {
                $options['local_cert'] = $certPath;
                if (! empty($certPassword)) {
                    $options['passphrase'] = $certPassword;
                }
                $options['authentication'] = SOAP_AUTHENTICATION_CERTIFICATE;
            }

            $options['stream_context'] = stream_context_create([
                'ssl' => [
                    'verify_peer' => $environment === 'production',
                    'verify_peer_name' => $environment === 'production',
                    'allow_self_signed' => $environment === 'test',
                    'crypto_method' => STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT,
                ],
            ]);

            try {
                $this->client = new SoapClient($wsdl, $options);
                Log::info('AEAT Verifactu native SOAP client created', ['environment' => $environment]);

                return $this->client;
            } catch (Exception $e) {
                Log::warning('Native SoapClient creation failed, falling back to HTTP SOAP adapter: '.$e->getMessage());
            }
        }

        // Fallback HTTP SOAP Adapter when ext-soap is absent or WSDL resolution fails
        $this->client = new VerifactuHttpSoapClient($endpoint, $certPath, $certPassword, $environment, $timeout);

        return $this->client;
    }

    /**
     * Download AEAT WSDL definition
     */
    protected function downloadWsdl(string $wsdlPath): void
    {
        $wsdlUrl = 'https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/burt/jdit/ws/SistemaFacturacion.wsdl';

        $dir = dirname($wsdlPath);
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        try {
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
                'http' => [
                    'timeout' => 10,
                ],
            ]);
            $wsdlContent = @file_get_contents($wsdlUrl, false, $context);
            if ($wsdlContent !== false) {
                file_put_contents($wsdlPath, $wsdlContent);
                Log::info('WSDL downloaded successfully', ['path' => $wsdlPath]);
            }
        } catch (Exception $e) {
            Log::warning('Could not automatically download WSDL: '.$e->getMessage());
        }
    }
}
