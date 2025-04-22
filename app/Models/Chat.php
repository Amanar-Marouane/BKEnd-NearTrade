<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Chat extends Model
{
    protected $fillable = [
        'chat_id',
        'id',
        'sender_id',
        'receiver_id',
        'message',
        'type',
        'product_id',
        'status',
        'offer',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'id' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($message) {
            if (empty($message->id)) {
                $message->id = (string) Str::uuid();
            }
        });
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
