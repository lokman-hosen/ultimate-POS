<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Main activities typed by users under "Other" in the registration form,
 * offered to later registrations.
 */
class BusinessActivity extends Model
{
    protected $guarded = ['id'];
}
