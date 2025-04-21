<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ChatIds extends Model
{
    protected $table = 'chat_ids';

    protected $fillable = [
        'id',
        'user1',
        'user2',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'id' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($chat) {
            if (empty($chat->id)) {
                $chat->id = (string) Str::uuid();
            }
        });
    }
}
