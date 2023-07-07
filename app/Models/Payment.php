<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public function method()
    {
        return $this->belongsTo(PaymentGateway::class,'gateway_id')->withDefault();
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class,'currency_id')->withDefault();
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}
