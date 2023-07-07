<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Merchant extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'name',
        'email',
        'photo',
        'phone',
        'country',
        'city',
        'email_verified',
        'verification_link',
        'address',
        'status',
        'zip',
        'password',
    ];
    protected $casts = ['kyc_info'=>'array'];
 
    public function wallets()
    {
        return $this->hasMany(Wallet::class,'user_id')->where('user_type',2);
    }
    public function api()
    {
        return $this->hasOne(ApiCreds::class,'merchant_id');
    }
}
