<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $guarded = [];

    // Ekta area-te onekgulo customer thakte pare
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
