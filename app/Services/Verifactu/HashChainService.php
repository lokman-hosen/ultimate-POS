<?php

namespace App\Services\Verifactu;

use App\Models\VerifactuRecord;

class HashChainService
{
    /**
     * Generate SHA-256 hash for a record according to AEAT Verifactu specifications
     */
    public function generateHash(array $data): string
    {
        // Sort keys to ensure consistent ordering
        ksort($data);

        // Convert to JSON and hash
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return strtoupper(hash('sha256', $json));
    }

    /**
     * Get previous hash for a business/location/series
     */
    public function getPreviousHash(?int $businessId, ?int $locationId, ?string $serie): ?string
    {
        $query = VerifactuRecord::query();

        if ($businessId !== null) {
            $query->where('business_id', $businessId);
        }

        if ($locationId !== null) {
            $query->where('location_id', $locationId);
        }

        if (! empty($serie)) {
            $query->where('serie', $serie);
        }

        $lastRecord = $query->whereNotNull('hash_registro')
            ->where('hash_registro', '!=', '')
            ->orderBy('id', 'desc')
            ->first();

        return $lastRecord?->hash_registro;
    }

    /**
     * Create hash chain entry
     */
    public function createChainEntry(VerifactuRecord $record, array $signedData): void
    {
        $record->verifactuHashChain()->create([
            'previous_hash' => $record->hash_anterior,
            'current_hash' => $record->hash_registro ?? $this->generateHash($signedData),
            'signed_data' => json_encode($signedData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);
    }

    /**
     * Verify hash chain integrity
     */
    public function verifyChain(VerifactuRecord $record): bool
    {
        $chain = $record->verifactuHashChain()
            ->orderBy('id', 'asc')
            ->get();

        if ($chain->isEmpty()) {
            return true; // No chain to verify
        }

        $previousHash = null;
        foreach ($chain as $link) {
            if ($previousHash !== null && $link->previous_hash !== $previousHash) {
                return false;
            }
            $previousHash = $link->current_hash;
        }

        return true;
    }
}
