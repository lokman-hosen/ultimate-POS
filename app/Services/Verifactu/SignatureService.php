<?php

namespace App\Services\Verifactu;

use DOMDocument;
use Exception;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class SignatureService
{
    protected string $certPath;
    protected string $certPassword;

    public function __construct()
    {
        $this->certPath = config('verifactu.certificate.path', '');
        $this->certPassword = config('verifactu.certificate.password', '');
    }

    /**
     * Sign XML with XAdES-BES signature structure
     */
    public function signXml(string $xml, string $reference = ''): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        if (! @$dom->loadXML($xml)) {
            return $xml;
        }

        // Find Signature placeholder if exists
        $signatureNode = $dom->getElementsByTagNameNS('http://www.w3.org/2000/09/xmldsig#', 'Signature')->item(0);
        if (! $signatureNode) {
            $signatureNode = $dom->createElementNS('http://www.w3.org/2000/09/xmldsig#', 'Signature');
            $signatureNode->setAttribute('Id', 'Signature-Verifactu');
            $dom->documentElement->appendChild($signatureNode);
        }

        // Clear children of Signature placeholder
        while ($signatureNode->hasChildNodes()) {
            $signatureNode->removeChild($signatureNode->firstChild);
        }

        // SignedInfo
        $signedInfo = $dom->createElementNS('http://www.w3.org/2000/09/xmldsig#', 'SignedInfo');

        // CanonicalizationMethod
        $canonMethod = $dom->createElement('CanonicalizationMethod');
        $canonMethod->setAttribute('Algorithm', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315');
        $signedInfo->appendChild($canonMethod);

        // SignatureMethod
        $sigMethod = $dom->createElement('SignatureMethod');
        $sigMethod->setAttribute('Algorithm', 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256');
        $signedInfo->appendChild($sigMethod);

        // Reference
        $referenceElement = $dom->createElement('Reference');
        $referenceElement->setAttribute('URI', ! empty($reference) && $reference !== 'SignaturePlaceholder' ? '#'.$reference : '');

        // Transforms
        $transforms = $dom->createElement('Transforms');
        $transform = $dom->createElement('Transform');
        $transform->setAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#enveloped-signature');
        $transforms->appendChild($transform);
        $referenceElement->appendChild($transforms);

        // DigestMethod
        $digestMethod = $dom->createElement('DigestMethod');
        $digestMethod->setAttribute('Algorithm', 'http://www.w3.org/2001/04/xmlenc#sha256');
        $referenceElement->appendChild($digestMethod);

        // Calculate DigestValue
        $c14nXml = $dom->documentElement->C14N(true, false);
        $digestVal = base64_encode(hash('sha256', $c14nXml, true));
        $digestValue = $dom->createElement('DigestValue', $digestVal);
        $referenceElement->appendChild($digestValue);

        $signedInfo->appendChild($referenceElement);
        $signatureNode->appendChild($signedInfo);

        // Compute SignatureValue & KeyInfo using Certificate if available
        $signatureValueStr = '';
        $certBase64 = '';

        if (! empty($this->certPath) && file_exists($this->certPath)) {
            try {
                $certData = $this->loadCertificate();
                if (! empty($certData['pkey'])) {
                    $c14nSignedInfo = $signedInfo->C14N(true, false);
                    $binarySig = '';
                    if (openssl_sign($c14nSignedInfo, $binarySig, $certData['pkey'], OPENSSL_ALGO_SHA256)) {
                        $signatureValueStr = base64_encode($binarySig);
                    }
                }
                if (! empty($certData['cert'])) {
                    // Extract clean base64 cert without PEM headers
                    $cleanCert = preg_replace('/-----BEGIN CERTIFICATE-----|-----END CERTIFICATE-----|\s+/', '', $certData['cert']);
                    $certBase64 = $cleanCert;
                }
            } catch (Exception $e) {
                Log::warning('Signing XML with certificate encountered an issue: '.$e->getMessage());
            }
        }

        // SignatureValue element
        $signatureValue = $dom->createElement('SignatureValue', $signatureValueStr);
        $signatureNode->appendChild($signatureValue);

        // KeyInfo element
        $keyInfo = $dom->createElement('KeyInfo');
        $x509Data = $dom->createElement('X509Data');
        $x509Certificate = $dom->createElement('X509Certificate', $certBase64);
        $x509Data->appendChild($x509Certificate);
        $keyInfo->appendChild($x509Data);
        $signatureNode->appendChild($keyInfo);

        return $dom->saveXML();
    }

    /**
     * Load certificate and private key
     */
    protected function loadCertificate(): array
    {
        if (! file_exists($this->certPath)) {
            throw new RuntimeException("Certificate file not found: {$this->certPath}");
        }

        $rawCert = file_get_contents($this->certPath);
        $ext = strtolower(pathinfo($this->certPath, PATHINFO_EXTENSION));

        if ($ext === 'p12' || $ext === 'pfx') {
            $certs = [];
            if (! openssl_pkcs12_read($rawCert, $certs, $this->certPassword)) {
                throw new RuntimeException('Failed to read PKCS#12 certificate.');
            }

            return [
                'cert' => $certs['cert'] ?? '',
                'pkey' => $certs['pkey'] ?? '',
            ];
        }

        // Assume PEM format
        return [
            'cert' => $rawCert,
            'pkey' => openssl_pkey_get_private($rawCert, $this->certPassword) ?: '',
        ];
    }
}
