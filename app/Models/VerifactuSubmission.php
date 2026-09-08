<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifactuSubmission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'verifactu_submissions';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'response_data' => 'array',
        'success' => 'boolean',
    ];

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
