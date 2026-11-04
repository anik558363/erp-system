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

    // app/Models/Product.php er vitore ei relation-gulo add kore din:

public function posSaleItems()
{
    return $this->hasMany(PosSaleItem::class, 'product_id');
}

public function posReturns()
{
    return $this->hasMany(PosReturn::class, 'product_id');
}
}
