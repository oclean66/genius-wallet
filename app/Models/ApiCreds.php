<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiCreds extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class,'merchant_id');
    }
}
