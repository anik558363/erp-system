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

public function payments() {
    return $this->hasMany(CustomerPayment::class);
}

// Total purchase amount (order-er total_amount)
public function totalOrdersAmount()
{
    return $this->orders()->sum('total_amount');
}


// Total paid amount (শুধু কালেকশন বা পেমেন্ট টেবিলের মোট অ্যামাউন্ট)
public function totalPaidAmount()
{
    return (float) $this->payments()->sum('amount');
}

// Real-time Current Due Calculation
public function calculateCurrentDue()
{
    $openingDue = $this->opening_due ?? 0;
    $totalOrders = $this->totalOrdersAmount();
    $totalPaid = $this->totalPaidAmount();

    // Formula: Opening Due + Total Orders - Total Paid
    return ($openingDue + $totalOrders) - $totalPaid;
}


}
