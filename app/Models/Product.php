<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Define Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    // Mass Assignable Fields
    protected $fillable = [
        'name',
        'price',
        'category_id',
        'subcategory_id',
        'brand',
        'description',
        'images',
        'quantity'
    ];

    // Cast images field as JSON array
    protected $casts = [
        'images' => 'array'
    ];
}
