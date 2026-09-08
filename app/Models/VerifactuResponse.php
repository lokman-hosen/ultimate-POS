<?php

namespace App\Models;

class VerifactuResponse
{
    public bool $isSuccess;
    public string $status;
    public ?string $csv;
    public ?string $uuid;
    public ?string $errorCode;
    public ?string $errorMessage;
    public array $rawPayload;

    public function __construct(
        bool $isSuccess,
        string $status,
        ?string $csv = null,
        ?string $uuid = null,
        ?string $errorCode = null,
        ?string $errorMessage = null,
        array $rawPayload = []
    ) {
        $this->isSuccess = $isSuccess;
        $this->status = $status;
        $this->csv = $csv;
        $this->uuid = $uuid;
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
        $this->rawPayload = $rawPayload;
    }

    /**
     * Create from array or SOAP response
     */
    public static function fromArray(array $data): self
    {
        $status = $data['EstadoEnvio'] ?? $data['EstadoFactura'] ?? ($data['success'] ? 'Correcta' : 'Error');
        $isSuccess = in_array($status, ['Correcta', 'AceptadaConErrores', 'Pendiente']);
        $csv = $data['CSV'] ?? $data['csv'] ?? null;
        $uuid = $data['IDFactura'] ?? $data['uuid'] ?? null;
        $errorCode = $data['CodigoError'] ?? $data['error_code'] ?? null;
        $errorMessage = $data['DescripcionError'] ?? $data['error_message'] ?? null;

        return new self($isSuccess, $status, $csv, $uuid, $errorCode, $errorMessage, $data);
    }
}
