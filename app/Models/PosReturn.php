<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosReturn extends Model
{
    protected $guarded = [];

    public function sale()
    {
        return $this->belongsTo(PosSale::class, 'sale_id');
    }

    public function saleItem()
    {
        return $this->belongsTo(PosSaleItem::class, 'sale_item_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function shift()
    {
        return $this->belongsTo(PosShift::class, 'shift_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
