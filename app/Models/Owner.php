<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Owner extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'owners';

    protected $fillable = [
        'username',
        'email',
        'phone',
        'password',
        'status',
        'level',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function routerSessions()
    {
        return $this->hasMany(RouterSession::class, 'owner_id');
    }

    public function agents()
    {
        return $this->hasMany(Agent::class, 'owner_id');
    }
}
