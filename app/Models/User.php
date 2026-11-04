<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'salesman_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'salesman_id');
    }

    public function posShifts()
    {
        return $this->hasMany(PosShift::class, 'cashier_id');
    }

    public function posSales()
    {
        return $this->hasMany(PosSale::class, 'cashier_id');
    }

    public function posPaymentsReceived()
    {
        return $this->hasMany(PosPayment::class, 'received_by');
    }

    public function posCashMovements()
    {
        return $this->hasMany(PosCashMovement::class, 'created_by');
    }
}
