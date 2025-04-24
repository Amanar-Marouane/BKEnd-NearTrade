<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PasswordReset extends Model
{
    protected $table = 'password_reset';

    protected $fillable = ['id', 'email', 'verification_code', 'expired_at'];

    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'id' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($verification_code) {
            if (empty($verification_code->id)) {
                $verification_code->id = (string) Str::uuid();
            }
        });
    }
}
