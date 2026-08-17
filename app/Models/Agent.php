<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $table = 'agents';

    protected $fillable = [
        'owner_id',
        'agent_code',
        'agent_name',
        'email',
        'phone',
        'password',
        'balance',
        'status',
        'level',
        'created_by',
        'notes',
    ];

    protected $hidden = [
        'password',
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class, 'owner_id');
    }
}
