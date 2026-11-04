<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $guarded = [];
    public $timestamps = false; // Karon migration-e timestamp-er jaygay nullable updated_at chilo

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
