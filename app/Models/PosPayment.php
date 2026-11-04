<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosPayment extends Model
{
    protected $guarded = [];

    public function sale()
    {
        return $this->belongsTo(PosSale::class, 'sale_id');
    }

    // Je payment ti receive koreche (Cashier)
    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
