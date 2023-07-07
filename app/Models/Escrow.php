<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escrow extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = ['currency'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function disputedBy()
    {
        return $this->belongsTo(User::class,'dispute_created')->withDefault();
    }
    public function recipient()
    {
        return $this->belongsTo(User::class,'recipient_id');
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class,'currency_id');
    }
}
