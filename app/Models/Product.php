<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'product_price',
        'product_qty',
        'product_tax',
        'product_description',
        'branch_id',
        'image_path',
        'category_id',
        'sort_order',
        'is_active',
        'sku',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /**
     * URL to the product image (thumbnail) or a placeholder SVG data-URI.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image_path && file_exists(public_path($this->image_path))) {
            return asset($this->image_path);
        }

        // Inline SVG placeholder – a clean modern box icon
        return "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120' viewBox='0 0 120 120'%3E%3Crect width='120' height='120' rx='16' fill='%23f1f5f9'/%3E%3Cg fill='%2394a3b8'%3E%3Cpath d='M60 30L34 44v32l26 14 26-14V44L60 30zm0 8.2l19 10.2-7.5 4.1L52.5 42.3 60 38.2zM38 48.7l19 10.2v21L38 69.7v-21zm25 31.2v-21l19-10.2v21L63 79.9z'/%3E%3C/g%3E%3C/svg%3E";
    }
}
