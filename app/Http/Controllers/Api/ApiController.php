<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller as Controller;


class ApiController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message = '')
    {
    	$response = [
            'success'   => true,
            'message'   => $message,
            'response'  => $result,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($message, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $message,
        ];


        if(!empty($errorMessages)){
            $response['response'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
}