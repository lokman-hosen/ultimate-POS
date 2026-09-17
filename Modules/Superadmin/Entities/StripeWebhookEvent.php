<?php

namespace Modules\Superadmin\Entities;

use Illuminate\Database\Eloquent\Model;

class StripeWebhookEvent extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'processed_at' => 'datetime',
    ];
}
