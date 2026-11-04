<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Ekta product-er current stock record thakbe
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
