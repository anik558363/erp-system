<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosCashMovement extends Model
{
    protected $guarded = [];

    public function shift()
    {
        return $this->belongsTo(PosShift::class, 'shift_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
