@extends('layouts.admin')

@section('title')
   @lang('Dispute Details')
@endsection

@section('breadcrumb')
<section class="section">
    <div class="section-header justify-content-between">
        <h1>@lang('Dispute Details')</h1>
        <a href="{{route('admin.escrow.disputed')}}" class="btn btn-primary"><i class="fas fa-backward"></i> @lang('Back')</a>
    </div>
</section>
@endsection

@section('content')
<div class="row justify-content-center pb-5">
    <div class="col-xl-5">
       <div class="card">
        <div class="card mt-3">
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item d-flex flex-wrap justify-content-center justify-content-md-end">
                        @if ($escrow->status != 1 && $escrow->status != 4)
                            @if (access('escrow close'))
                               <a href="javascript:void(0)" data-toggle="modal" data-target="#closeModal" class="btn btn-danger m-2">@lang('Close Dispute')</a>
                            @endif
                            @if (access('escrow return payment'))
                                <a href="javascript:void(0)" class="btn btn-primary m-2 pay" data-id="{{$escrow->user->id}}">@lang('Pay To Invitor')</a>
                                <a href="javascript:void(0)" class="btn btn-dark pay m-2" data-id="{{$escrow->recipient->id}}">@lang('Pay To Invitee')</a>
                            @endif
                        @elseif($escrow->returned_to != null)
                            @lang('Payment returned to '.$escrow->returned_to)
                        @endif
                    </li>
                    <li class="list-group-item d-flex justify-content-between font-weight-bold">
                        @lang('Disputed By : ')
                        <span>{{$escrow->disputedBy->email}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between font-weight-bold">
                        @lang('Invited By : ')
                        <span>{{$escrow->user->email}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between font-weight-bold">
                        @lang('Invited To : ')
                        <span>{{$escrow->recipient->email}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between font-weight-bold">
                        @lang('Amount : ')
                        <span>{{amount($escrow->amount,$escrow->currency->type,2)}} {{$escrow->currency->code}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between font-weight-bold">
                        @lang('Charge : ')
                        <span>{{amount($escrow->charge,$escrow->currency->type,2)}} {{$escrow->currency->code}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between font-weight-bold">
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
    </div>
    <div class="col-xl-7">
        <div class="card">
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane show fade active" id="c1">
                        <div class="chat__msg">
                            <div class="chat__msg-header  border-bottom">
                                <div class="post__creator align-items-center">
                                    <div class="post__creator-content">
                                        <h6 class="name d-inline-block">@lang('Escrow : #'.$escrow->trnx)</h6>
                                    </div>
                                    <a class="profile-link" href="javascript:void(0)"></a>
                                </div>
                            </div>
                            
                            <div class="chat__msg-body">
                                <ul class="msg__wrapper mt-3">
                            
                                        @forelse ($messages as $item)
                                            @if ($item->user_id != null)
                                                @if ($item->user_id == $escrow->user_id)
                                                <li class="incoming__msg">
                                                    <div class="msg__item">
                                                        <div class="post__creator ">
                                                            <div class="post__creator-content">
                                                                <p class="">
                                                                    <small><u>{{$item->user->email}}</u> :</small><br>
                                                                    {{__($item->message)}}
                                                                </p>
                                                                @if ($item->file)
                                                                    <div class="text-start">
                                                                        <a href="{{route('admin.escrow.file.download',$item->id)}}">{{$item->file}}</a>
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
                                                                        <a href="{{route('admin.escrow.file.download',$item->id)}}">{{$item->file}}</a>
                                                                    </div>
                                                                @endif
                                                                <span class="comment-date text--secondary">{{diffTime($item->created_at)}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                @endif
                                            @else
                                            <li class="outgoing__msg">
                                                <div class="msg__item">
                                                    <div class="post__creator">
                                                        <div class="post__creator-content">
                                                            <p class="out__msg">
                                                                {{__($item->message)}}
                                                            </p>
                                                            @if ($item->file)
                                                                <div>
                                                                    <a href="{{route('admin.escrow.file.download',$item->id)}}">{{$item->file}}</a>
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
                           
                           @if ($escrow->status == 3)
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
                           @endif
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if (access('escrow return payment'))
<div id="confirmModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{route('admin.escrow.return.payment')}}" method="POST">
            @csrf
            <input type="hidden" name="id">
            <input type="hidden" name="escrow_id" value="{{$escrow->id}}">
            <div class="modal-content">
                <div class="modal-body">
                    <h6 class="mt-3">@lang('Are you sure to return payment?')</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@if (access('escrow close'))
<div id="closeModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{route('admin.escrow.close',$escrow->id)}}" method="get">
            <div class="modal-content">
                <div class="modal-body">
                    <h6 class="mt-3">@lang('Are you sure to close?')</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endsection


@push('script')
    <script>
        'use strict';
        $('.pay').on('click',function () { 
            $('#confirmModal').find('input[name=id]').val($(this).data('id'))
            $('#confirmModal').modal('show')
        })
    </script>
@endpush
