<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $table = 'sessions';

    protected $fillable = [
        'id',
        'user_id',
        'ip_address',
        'user_agent',
        'action',
        'description',
        'payload',
        'last_activity',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
