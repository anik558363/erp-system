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

    // 1ta Supplier-er under-e onekgula Purchase invoice thakte pare
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
