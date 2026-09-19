<?php

namespace Modules\Superadmin\Entities;

use Illuminate\Database\Eloquent\Model;

class StripeInvoiceRecord extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'billing_period_start' => 'datetime',
        'billing_period_end' => 'datetime',
        'paid_at' => 'datetime',
    ];
}
