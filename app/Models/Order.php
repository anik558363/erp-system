<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    // Customer relation
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Salesman (User) relation
    public function salesman()
    {
        return $this->belongsTo(User::class, 'salesman_id');
    }

    // Delivery Man relation (jodi thake)
    public function deliveryMan()
    {
        return $this->belongsTo(User::class, 'delivery_man_id');
    }

    // Area relation
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    // Order Items relation
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
