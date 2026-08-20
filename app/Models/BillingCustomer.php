<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingCustomer extends Model
{
    use HasFactory;

    protected $table = 'billing_customers';

    protected $fillable = [
        'profile_id',
        'name',
        'phone',
        'email',
        'address',
        'service_number',
        'billing_day',
        'status',
        'is_isolated',
        'next_isolation_date',
        'notes',
        'owner_id',
    ];
}
