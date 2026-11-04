<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    // Ekta category-te onekgulo product thakte pare
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
