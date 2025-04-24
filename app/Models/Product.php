<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = ['id', 'user_id', 'category_id', 'name', 'description', 'status', 'price', 'images', 'location'];

    protected $casts = [
        'id' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            if (empty($user->id)) {
                $user->id = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function deleteMedia()
    {
        $images = array_filter(explode('|', $this->images));
        foreach ($images as $imagePath) {
            try {
                $parts = explode('/', $imagePath);
                $filename = end($parts);
                $fullPath = storage_path('app/public/Products/' . $filename);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                } else {
                    $publicPath = public_path('storage/Products/' . $filename);
                    if (file_exists($publicPath)) {
                        unlink($publicPath);
                    } else {
                    }
                }
            } catch (\Exception $e) {
            }
        }
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites()->count();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
