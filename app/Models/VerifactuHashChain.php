<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifactuHashChain extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'verifactu_hash_chains';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Record relation
     */
    public function record()
    {
        return $this->belongsTo(VerifactuRecord::class, 'record_id');
    }

    /**
     * Alias for record relation
     */
    public function verifactuRecord()
    {
        return $this->belongsTo(VerifactuRecord::class, 'record_id');
    }
}
