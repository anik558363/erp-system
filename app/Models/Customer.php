<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    // Assigned Salesman (User model)
    public function salesman()
    {
        return $this->belongsTo(User::class, 'salesman_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function ledgers()
    {
        return $this->hasMany(LedgerEntry::class);
    }

    // app/Models/Customer.php er vitore ei relation-ti add kore din:

public function posSales()
{
    return $this->hasMany(PosSale::class, 'customer_id');
}
}
