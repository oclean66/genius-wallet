<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_code',
        'amount',
        'details',
        'web_hook',
        'custom',
        'cancel_url',  
        'success_url',  
        'customer_email',
        'payment_id',
        'merchant_id',
        'currency_id',
        'mode'
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class,'currency_id');
    }
    public function merchant()
    {
        return $this->belongsTo(Merchant::class,'merchant_id');
    }


}
