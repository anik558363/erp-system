<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosSale extends Model
{
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(PosSaleItem::class, 'sale_id');
    }

    public function payments()
    {
        return $this->hasMany(PosPayment::class, 'sale_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
}
