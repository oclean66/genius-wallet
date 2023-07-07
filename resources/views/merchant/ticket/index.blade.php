@extends('layouts.merchant')

@section('title')
   @lang('Support tickets')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header">
        <h1>@lang('Support tickets')</h1>
    </div>
</section>
@endsection

@section('content')



        <div class="row justify-content-center pb-5">
            <div class="col-xl-4 col-lg-5">
               <div class="card">
                   <div class="card-body">
                    <div class="chatbox__list__wrapper">
                        <div class="d-flex justify-content-between py-4 border-bottom border--dark">
                            <h5 class=""><a href="javascript:void(0)">@lang('Tickets')<i class="fas fa-arrow-right"></i></a></h5>
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modelId"><i class="fas fa-plus"></i> @lang('Opent a Ticket')</button>
                        </div>
         
                        <ul class="chat__list nav-tab nav border-0">
                            @forelse ($tickets as $item)
                            <li>
                                <a class="chat__item {{request('messages') == $item->ticket_num ? 'active':''}}" href="{{filter('messages',$item->ticket_num)}}" data-bs-toggle="tab">
                                    <div class="item__inner">
                                        <div class="post__creator">
                                            <div class="post__creator-thumb d-flex justify-content-between">
                                                <span class="username">{{$item->ticket_num}} </span>
                                                @if ($item->status == 1)
                                                <small class="badge badge-danger">!</small>
                                                @endif
                                            </div>
                                            <div class="post__creator-content">
                                                <h6 class="name d-inline-block">{{$item->subject}}</h6>
                                               
                                            </div>
                                        </div>
                                        <ul class="chat__meta d-flex justify-content-between">
                                            <li><span class="last-msg"></span></li>
                                            <li><span class="last-chat-time">{{dateFormat($item->created_at,'d M Y')}}</span></li>
                                        </ul>
                                    </div>
                                </a>
                            </li>
                            @empty
                            <li>
                                <a class="chat__item">
                                    <div class="item__inner">
                                        <div class="post__creator">
                                            @lang('No Tickets Available')
                                        </div>
                                    </div>
                                </a>
                            </li>
                            @endforelse
                        </ul>
                    </div>
                   </div>
                   @if ($tickets->hasPages())
                   <div class="card-footer">
                       {{$tickets->links()}}
                   </div>
                   @endif
               </div>
            </div>
            <div class="col-xl-8 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane show fade active" id="c1">
                                <div class="chat__msg">
                                    <div class="chat__msg-header py-2 border-bottom">
                                        <div class="post__creator align-items-center">
                                            
                                            <div class="post__creator-content">
                                                <h5 class="name d-inline-block">@lang('Ticket Number : #'){{request('messages')}}</h5>
                                                
                                                
                                            </div>
                                            <a class="profile-link" href="javascript:void(0)"></a>
                                        </div>
                                    </div>
                                    
                                    <div class="chat__msg-body">
                                        <ul class="msg__wrapper mt-3">
                                            @if (request('messages'))
                                                @forelse ($messages as $item)
                                                    @if ($item->admin_id == null)
                                                    <li class="outgoing__msg">
                                                        <div class="msg__item">
                                                            <div class="post__creator ">
                                                                <div class="post__creator-content">
                                                                    <p class="out__msg">{{__($item->message)}}</p>
                                                                    @if ($item->file)
                                                                        <div class="text-start">
                                                                            <a href="">{{$item->file}}</a>
                                                                        </div>
                                                                      @endif
                                                                    <span class="comment-date text--secondary">{{diffTime($item->created_at)}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    @else
                                                    <li class="incoming__msg">
                                                        <div class="msg__item">
                                                            <div class="post__creator">
                                                                <div class="post__creator-content">
                                                                    <p>{{__($item->message)}}</p>
                                                                    @if ($item->file)
                                                                        <div class="text-end ms-auto">
                                                                            <a href="">{{$item->file}}</a>
                                                                        </div>
                                                                    @endif
                                                                    <span class="comment-date text--secondary">{{diffTime($item->created_at)}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    @endif
                                                @empty
                                                <li class="incoming__msg">
                                                    <div class="msg__item">
                                                        <div class="post__creator">
                                                            <div class="post__creator-content">
                                                                <h6 class="text-center">@lang('No messages yet!!')</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                @endforelse
                                            @else
                                            <li>
                                                <div class="msg__item">
                                                    <div class="post__creator ">
                                                        <div class="post__creator-content ">
                                                           <h6 class="text-center">@lang('No messages yet')</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                    @if (request('messages'))
                                    <div class="chat__msg-footer">
                                        <form action="{{route('merchant.ticket.reply',request('messages'))}}" class="send__msg" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div class="input-group">
                                                <input id="upload-file" type="file" name="file" class="form-control d-none">
                                                <label class="-formlabel upload-file" for="upload-file"><i class="fas fa-cloud-upload-alt"></i>
                                            </div>
                                            <div class="input-group">
                                                <textarea class="form-control form--control" name="message"></textarea>
                                                <button class="border-0 outline-0 send-btn" type="submit"><i class="fab fa-telegram-plane"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
               <form action="{{route('merchant.ticket.open')}}" method="POST">
                @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">@lang('Open a ticket')</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>@lang('Subject')</label>
                                <input class="form-control" type="text" name="subject" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                        </div>
                    </div>
               </form>
            </div>
        </div>
  
@endsection