<?php

namespace App\Http\Controllers\Gateway;

use Illuminate\Support\Str;

class Manual {

    public function initiate($request,$payment_data,$type)
    {
        $status = 1;
        $message = '';
        $txn_id  = str_rand();
        return ['status' => $status,'txn_id' => $txn_id , 'message' => $message ];
    }
}