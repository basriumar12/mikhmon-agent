<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentTransaction extends Model
{
    use HasFactory;

    protected $table = 'agent_transactions';

    // Disable default Laravel timestamps since the legacy table only has created_at
    public $timestamps = false;

    protected $fillable = [
        'agent_id',
        'transaction_type',
        'amount',
        'balance_before',
        'balance_after',
        'profile_name',
        'voucher_username',
        'voucher_password',
        'quantity',
        'description',
        'reference_id',
        'created_by',
        'created_at',
    ];

    protected $casts = [
        'amount' => 'float',
        'balance_before' => 'float',
        'balance_after' => 'float',
        'quantity' => 'integer',
        'created_at' => 'datetime',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }
}
