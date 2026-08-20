<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaasPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'plan_slug',
        'order_id',
        'amount',
        'status',
        'payment_method',
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class, 'owner_id');
    }
}
