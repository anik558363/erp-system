<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosRegister extends Model
{
    protected $guarded = [];

    // Ekta register-er under-e onekgulo shift thakte pare
    public function shifts()
    {
        return $this->hasMany(PosShift::class, 'register_id');
    }

    // Ekta register-er under-e onekgulo sales hote pare
    public function sales()
    {
        return $this->hasMany(PosSale::class, 'register_id');
    }
}
