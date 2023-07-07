<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestMoney extends Model
{
    use HasFactory;
    protected $fillable = ['sender_id','receiver_id','currency_id','request_amount','charge','final_amount','status'];
    protected $with = ['currency'];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    public function sender()
    {
        return $this->belongsTo(User::class,'sender_id')->withDefault();
    }
    public function receiver()
    {
        return $this->belongsTo(User::class,'receiver_id')->withDefault();
    }
}
