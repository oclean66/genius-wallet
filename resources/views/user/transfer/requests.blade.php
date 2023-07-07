@extends('layouts.user')

@section('title')
   @lang('Money Requests')
@endsection

@section('breadcrumb')
    @if (request()->routeIs('user.sent.requests'))
        @lang('Sent Requests')
    @elseif(request()->routeIs('user.received.requests'))
        @lang('Received Requests')
    @endif
@endsection

@section('content')
<div class="container-xl">
@if (request()->routeIs('user.sent.requests'))
<div class="row row-deck row-cards">
    <div class="col-12">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                      <tr>
                        <th>@lang('Recipient')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Charge')</th>
                        <th>@lang('You will get')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Date')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($sentRequests as $item)
                        <tr>
                          <td data-label="@lang('Recipient')">{{$item->receiver->email}}</td>
                          <td data-label="@lang('Amount')">{{numFormat($item->request_amount)}} {{$item->currency->code}}</td>
                          <td data-label="@lang('Charge')">{{numFormat($item->charge)}} {{$item->currency->code}}</td>
                          <td data-label="@lang('You will get')">{{numFormat($item->final_amount)}} {{$item->currency->code}}</td>
                          <td data-label="@lang('Status')">
                              @if ($item->status == 0)
                                  <span class="badge bg-warning">@lang('pending')</span>
                              @elseif ($item->status == 1)
                                   <span class="badge bg-success">@lang('accepted')</span>
                              @elseif ($item->status == 2)
                                   <span class="badge bg-danger">@lang('rejected')</span>
                              @endif
                          </td>
                          <td data-label="@lang('Date')">{{dateFormat($item->created_at)}}</td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center" colspan="12">@lang('No data found!')</td>
                        </tr>
                      @endforelse
                    </tbody>
                  </table>
            </div>
        </div>
    </div>
</div>
@elseif(request()->routeIs('user.received.requests'))
<div class="row row-deck row-cards">
    <div class="col-12">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                      <tr>
                        <th>@lang('Request From')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Date')</th>
                        <th>@lang('Action')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($receivedRequests as $item)
                        <tr>
                          <td data-label="@lang('Request From')">{{$item->sender->email}}</td>
                          <td data-label="@lang('Amount')">{{amount($item->request_amount,$item->currency->type,2)}} {{$item->currency->code}}</td>
                          <td data-label="@lang('Status')">
                              @if ($item->status == 0)
                                  <span class="badge bg-warning">@lang('pending')</span>
                              @elseif ($item->status == 1)
                                   <span class="badge bg-success">@lang('accepted')</span>
                              @elseif ($item->status == 2)
                                   <span class="badge bg-danger">@lang('rejected')</span>
                              @endif
                          </td>
                          <td data-label="@lang('Date')">{{dateFormat($item->created_at)}}</td>
                          <td data-label="@lang('Action')">
                            @if($item->status == 0)
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modal-success" class="btn btn-primary accept" data-item="{{$item}}" title="@lang('Accept')">
                                    <i class="fas fa-check"></i>
                                </a>
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modal-danger" class="btn btn-danger reject" data-item="{{$item}}" title="@lang('Reject')">
                                    <i class="fas fa-ban"></i>
                                </a>
                            @elseif($item->status == 1)
                                <a href="javascript:void(0)" class="btn btn-primary disabled"  title="@lang('Accept')">
                                    <i class="fas fa-check"></i>
                                </a>
                                <a href="javascript:void(0)" class="btn btn-danger disabled"  title="@lang('Reject')">
                                    <i class="fas fa-ban"></i>
                                </a>
                            @endif
                          </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center" colspan="12">@lang('No data found!')</td>
                        </tr>
                      @endforelse
                    </tbody>
                  </table>
            </div>
        </div>
    </div>
</div>
@endif

<div class="modal modal-blur fade" id="modal-danger" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-status bg-danger"></div>
        <div class="modal-body text-center  py-4">
            <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
          <h3>@lang('Are you sure?')</h3>
          <div class="text-muted">@lang('Do you want to reject this request?')</div>
        </div>
        <div class="modal-footer">
          <div class="w-100">
            <div class="row">
              <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                 @lang(' Cancel')
                </a></div>
               <div class="col">
                   <form action="{{route('user.reject.request')}}" method="POST">
                       @csrf
                       <input type="hidden" name="id">
                       <input type="hidden" name="sender_id">
                       <button type="submit" class="btn btn-danger w-100">@lang('Confirm')</button>
                   </form>
               </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="modal modal-blur fade" id="modal-success" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-status bg-primary"></div>
        <div class="modal-body text-center py-4">
        <i  class="fas fa-info-circle fa-3x text-primary mb-2"></i>
        <h3>@lang('Are you sure?')</h3>
        <div class="text-muted">@lang('Do you want to accept this request?')</div>
        </div>
        <div class="modal-footer">
        <div class="w-100">
            <div class="row">
            <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                @lang('Cancel')
                </a></div>
            <div class="col">
                <form action="{{route('user.accept.request')}}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <input type="hidden" name="sender_id">
                    <button type="submit" class="btn btn-primary w-100">@lang('Confirm')</button>
                </form>
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>

</div>
@endsection

@push('script')
    <script>
        'use strict';
        $('.accept').on('click',function () { 
            var data = $(this).data('item')
            $('#modal-success').find('input[name=id]').val(data.id)
            $('#modal-success').find('input[name=sender_id]').val(data.sender_id)
        })
        $('.reject').on('click',function () { 
            var data = $(this).data('item')
            $('#modal-danger').find('input[name=id]').val(data.id)
            $('#modal-danger').find('input[name=sender_id]').val(data.sender_id)
        })
    </script>
@endpush