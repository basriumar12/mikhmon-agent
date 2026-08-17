<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouterSession extends Model
{
    use HasFactory;

    protected $table = 'router_sessions';

    protected $fillable = [
        'owner_id',
        'session_name',
        'ip_address',
        'username',
        'password',
        'hotspot_name',
        'dns_name',
        'currency',
        'auto_reload',
        'interface',
        'info_limit',
        'idle_timeout',
        'live_report',
    ];

    public $timestamps = false; // Mapped to legacy created_at timestamp only

    public function owner()
    {
        return $this->belongsTo(Owner::class, 'owner_id');
    }

    public function setPasswordAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['password'] = '';
            return;
        }
        $key = '128';
        $result = '';
        for ($i = 0, $k = strlen($value); $i < $k; $i++) {
            $char = substr($value, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result .= $char;
        }
        $this->attributes['password'] = base64_encode($result);
    }

    public function getDecryptedPasswordAttribute()
    {
        if (empty($this->attributes['password'])) {
            return '';
        }
        $value = base64_decode($this->attributes['password']);
        $key = '128';
        $result = '';
        for ($i = 0, $k = strlen($value); $i < $k; $i++) {
            $char = substr($value, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result .= $char;
        }
        return $result;
    }
}
