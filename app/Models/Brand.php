<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $guarded = [];

    // Ekta brand-er under-e onekgulo product thakte pare
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
