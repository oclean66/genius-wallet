@extends('layouts.user')

@section('title')
   @lang('Dispute Messages')
@endsection

@section('breadcrumb')
 @lang('Dispute Messages') 
@endsection

@push('extra')
    <a href="{{session('route') ?? route('user.escrow.index')}}" class="btn btn-primary"><i class="fas fa-backward me-2"></i> @lang('Back')</a>
@endpush
@section('content')
  
    <div class="container-xl">
        <div class="row justify-content-center pb-5">
            <div class="col-xl-4 col-lg-5 mb-4">
               <div class="card">
                   <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex flex-wrap justify-content-between font-weight-bold">
                                @lang('Invited To : ')
                                <span>{{$escrow->recipient->email}}</span>
                            </li>
                            <li class="list-group-item d-flex flex-wrap justify-content-between font-weight-bold">
                                @lang('Amount : ')
                                <span>{{numFormat($escrow->amount)}} {{$escrow->currency->code}}</span>
                            </li>
                            <li class="list-group-item d-flex flex-wrap justify-content-between font-weight-bold">
                                @lang('Charge : ')
                                <span>{{numFormat($escrow->charge)}} {{$escrow->currency->code}}</span>
                            </li>
                            <li class="list-group-item d-flex flex-wrap justify-content-between font-weight-bold">
                                @lang('Charge Bearer : ')
                                <span>
                                    @if ($escrow->pay_charge == 1)
                                    {{ $escrow->user->email}}
                                    @else
                                    {{ $escrow->recipient->email}}
                                    @endif
                                </span>
                            </li>
                            <li class="list-group-item font-weight-bold">
                                @lang('Description : ')
                                <textarea rows="5" class="form-control" disabled>{{$escrow->description}}</textarea>
                            </li>
                           
                        </ul>
                    
                   </div>
                  
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
                                                <h4 class="name d-inline-block">@lang('Escrow : #'.$escrow->trnx)</h4>
                                            </div>
                                            <a class="profile-link" href="javascript:void(0)"></a>
                                        </div>
                                    </div>
                                    
                                    <div class="chat__msg-body">
                                        <ul class="msg__wrapper mt-3">
                                    
                                                @forelse ($messages as $item)
                                                    @if ($item->user_id != null)
                                                        @if ($item->user_id == auth()->id())
                                                        <li class="outgoing__msg">
                                                            <div class="msg__item">
                                                                <div class="post__creator ">
                                                                    <div class="post__creator-content">
                                                                        <p class="out__msg">
                                                                            {{__($item->message)}}
                                                                        </p>
                                                                        @if ($item->file)
                                                                            <div class="text-start">
                                                                                <a href="{{route('user.escrow.file.download',$item->id)}}">{{$item->file}}</a>
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
                                                                        
                                                                        <p class="bg-info text-white">
                                                                            <small><u>{{$item->user->email}}</u> :</small><br>
                                                                            {{__($item->message)}}
                                                                           
                                                                        </p>
                                                                        @if ($item->file)
                                                                            <div>
                                                                                <a href="{{route('user.escrow.file.download',$item->id)}}">{{$item->file}}</a>
                                                                            </div>
                                                                        @endif
                                                                        <span class="comment-date text--secondary">{{diffTime($item->created_at)}}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        @endif
                                                    @else
                                                    <li class="incoming__msg">
                                                        <div class="msg__item">
                                                            <div class="post__creator">
                                                                <div class="post__creator-content">
                                                                    <p>
                                                                        <small><u>@lang('Admin')</u> :</small><br>
                                                                        {{__($item->message)}}
                                                                    </p>
                                                                    @if ($item->file)
                                                                        <div>
                                                                            <a href="{{route('user.escrow.file.download',$item->id)}}">{{$item->file}}</a>
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
                                                                <h4 class="text-center">@lang('No messages yet!!')</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                @endforelse
                                        
                                           
                                        </ul>
                                    </div>
                                   
                                    <div class="chat__msg-footer">
                                        <form action="" class="send__msg" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div class="input-group">
                                                <input id="upload-file" type="file" name="file" class="form-control d-none">
                                                <label class="-formlabel upload-file" for="upload-file"><i class="fas fa-cloud-upload-alt"></i>
                                            </div>
                                            <div class="input-group">
                                                <textarea class="form-control form--control shadow-none" name="message"></textarea>
                                                <button class="border-0 outline-0 send-btn" type="submit"><i class="fab fa-telegram-plane"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection


