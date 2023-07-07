<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExchangeMoney extends Model
{
    use HasFactory;

    public function fromCurr()
    {
        return $this->belongsTo(Currency::class,'from_currency');
    }
    public function toCurr()
    {
        return $this->belongsTo(Currency::class,'to_currency');
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function($exchange)
        {
            $trnx              = new Transaction();
            $trnx->trnx        = $exchange->trnx;
            $trnx->user_id     = auth()->id();
            $trnx->user_type   = 1;
            $trnx->currency_id = $exchange->from_currency;
            $trnx->amount      = $exchange->from_amount;
            $trnx->charge      = $exchange->charge;
            $trnx->remark      = 'money_exchange';
            $trnx->type        = '-';
            $trnx->details     = trans('Exchanged money from '.$exchange->fromCurr->code.' to '.$exchange->toCurr->code);
            $trnx->save();

            $toTrnx              = new Transaction();
            $toTrnx->trnx        = $trnx->trnx;
            $toTrnx->user_id     = auth()->id();
            $toTrnx->user_type   = 1;
            $toTrnx->currency_id = $exchange->to_currency;
            $toTrnx->amount      = $exchange->to_amount;
            $toTrnx->charge      = 0;
            $toTrnx->remark      = 'money_exchange';
            $toTrnx->type          = '+';
            $toTrnx->details     = trans('Exchanged money from '.$exchange->fromCurr->code.' to '.$exchange->toCurr->code);
            $toTrnx->save();
        });
    }
}
