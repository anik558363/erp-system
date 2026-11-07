<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'company_name',
        'phone',
        'address',
        'opening_due',
        'current_due',
        'status',
    ];

    // ১. Supplier has many purchases
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'supplier_id');
    }

    // ২. Supplier has many payments (এই মেথডটি মিসিং ছিল)
    public function payments()
    {
        return $this->hasMany(SupplierPayment::class, 'supplier_id');
    }
}
