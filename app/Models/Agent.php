<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Agent extends Authenticatable
{
    use HasFactory;

    protected $guarded = [];

    public function wallets()
    {
        return $this->hasMany(Wallet::class,'user_id')->where('user_type',3);
    }
}
