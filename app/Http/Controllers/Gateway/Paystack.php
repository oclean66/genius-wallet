<?php

namespace App\Http\Controllers\Gateway;

class Paystack {

    public function initiate($request,$payment_data,$type)
    {
        $status = 0;
        $message = '';
        $txn_id  = '';
        if(isset($request->ref_id) && $request->ref_id){
            $status = 1;
            $txn_id = $request->ref_id;
        }else{
            $message = __('Ref id not found please try again');
        }
        
        return ['status' => $status,'txn_id' => $txn_id , 'message' => $message ];
    }
}