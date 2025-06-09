<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'color',
        'status',
        'seat',
        'cc',
        'top_speed',
        'description',
        'location',
        'imageUrl',
        'category_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'seat' => 'integer',
        'cc' => 'integer',
        'top_speed' => 'integer',
        'category_id' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getMainImageUrlAttribute()
    {
        if ($this->imageUrl) {
            return asset('storage/' . $this->imageUrl);
        }
        return null;
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeUnavailable($query)
    {
        return $query->where('status', 'unavailable');
    }
}
