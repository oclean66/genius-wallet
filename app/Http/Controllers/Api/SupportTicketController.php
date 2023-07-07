<?php

namespace App\Http\Controllers\Api;

use App\Helpers\MediaHelper;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\TicketMessage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiController;

class SupportTicketController extends ApiController
{
    public function index()
    {
        if(request()->routeIs('user.*')){
            $user = auth()->user();
            $type = 1;
        }
        else{
            $user = request()->user();
            $type = 2;
        } 
       
        $success['tickets'] = SupportTicket::where('user_id',$user->id)->where('user_type',$type)->latest()->paginate(15);
        return $this->sendResponse($success,'Your tickets');
    }

    public function messages($ticket_num)
    {
        $user = request()->user();
        $success['messages'] = TicketMessage::where('ticket_num',$ticket_num)->where('user_id',$user->id)->get();
        return $this->sendResponse($success,'Ticket messages of '.$ticket_num);
    }

    public function openTicket(Request $request)
    {
        $validator = Validator::make($request->all(),['subject'=>'required']);
        if( $validator->fails()){
            return $this->sendError('Validation Error',$validator->errors());
        }

        if(request()->routeIs('user.*')){
            $user = auth()->user();
            $type = 1;
        }
        else{
            $user = request()->user();
            $type = 2;
        } 

        $tkt = 'TKT'.randNum(8);
        SupportTicket::create([
            'user_id' => $user->id,
            'user_type' => $type,
            'ticket_num' => $tkt,
            'subject'  => $request->subject,
        ]);

        return $this->sendResponse(['ticket_num' => $tkt],'New ticket has been opened');
    }

    public function replyTicket(Request $request,$ticket_num)
    {
        $validator = Validator::make($request->all(),['message'=>'required','file'=>'mimes:pdf,jpeg,jpg,png,PNG,JPG']);
        if( $validator->fails()){
            return $this->sendError('Validation Error',$validator->errors());
        }
      
        if(request()->routeIs('user.*')){
            $user = auth()->user();
            $type = 1;
        }
        else{
            $user = request()->user();
            $type = 2;
        } 

        $ticket = SupportTicket::where('ticket_num',$ticket_num)->where('user_id',$user->id)
        ->where('user_type',$type)->first();

        if(!$ticket){
            return $this->sendError('Error',['Ticket not fount']);
        }
       
        $message = new TicketMessage();
        $message->ticket_id = $ticket->id;
        $message->ticket_num = $ticket->ticket_num;
        $message->user_id = $user->id;
        $message->user_type = $type;
        $message->message = $request->message;
        if($request->file){
            $message->file = MediaHelper::handleMakeImage($request->file,null,true);
        }
        $message->save();

        $ticket->status = 0;
        $ticket->save();
        return $this->sendResponse(['success'],'Replied successfully');
    }
}


