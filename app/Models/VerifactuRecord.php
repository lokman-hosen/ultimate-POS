<?php

namespace App\Models;

use App\Business;
use App\BusinessLocation;
use App\Transaction;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VerifactuRecord extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'verifactu_records';

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
        'response_payload' => 'array',
        'fecha_expedicion' => 'date',
        'submitted_at' => 'datetime',
        'status_checked_at' => 'datetime',
    ];

    /**
     * Transaction relation (Primary in UltimatePOS)
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    /**
     * Alias for transaction
     */
    public function invoice()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    /**
     * Business relation
     */
    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    /**
     * Location relation
     */
    public function location()
    {
        return $this->belongsTo(BusinessLocation::class, 'location_id');
    }

    /**
     * User who created the record
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Submissions history
     */
    public function verifactuSubmissions()
    {
        return $this->hasMany(VerifactuSubmission::class, 'record_id');
    }

    /**
     * Alias for verifactuSubmissions
     */
    public function submissions()
    {
        return $this->hasMany(VerifactuSubmission::class, 'record_id');
    }

    /**
     * Hash chain entry
     */
    public function verifactuHashChain()
    {
        return $this->hasOne(VerifactuHashChain::class, 'record_id');
    }

    /**
     * Alias for verifactuHashChain
     */
    public function hashChain()
    {
        return $this->hasOne(VerifactuHashChain::class, 'record_id');
    }
}
