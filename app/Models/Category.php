<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $casts = [
        'id' => 'string',
    ];

    protected $fillable = ['id', 'category', 'created_at', 'updated_at'];
}
