<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;
    protected $with = ['currency'];

    public function gateway()
    {
        return $this->belongsTo(PaymentGateway::class,'method');
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class,'currency_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
