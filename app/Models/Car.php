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

    // Relationship dengan Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Accessor untuk URL gambar lengkap
    public function getMainImageUrlAttribute()
    {
        if ($this->imageUrl) {
            return asset('storage/' . $this->imageUrl);
        }
        return null;
    }

    // Scope untuk status available
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    // Scope untuk status unavailable
    public function scopeUnavailable($query)
    {
        return $query->where('status', 'unavailable');
    }
}
