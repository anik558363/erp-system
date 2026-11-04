<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosShift extends Model
{
    protected $guarded = [];

    public function register()
    {
        return $this->belongsTo(PosRegister::class, 'register_id');
    }

    // Je cashier shift-ti operate korche
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    // Ke shift-ti close koreche (Office/Owner)
    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function sales()
    {
        return $this->hasMany(PosSale::class, 'shift_id');
    }

    public function cashMovements()
    {
        return $this->hasMany(PosCashMovement::class, 'shift_id');
    }
}
