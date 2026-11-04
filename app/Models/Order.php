<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesman()
    {
        return $this->belongsTo(User::class, 'salesman_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function deliveryMan()
    {
        return $this->belongsTo(User::class, 'delivery_man_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
