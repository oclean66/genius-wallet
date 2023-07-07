@extends('layouts.admin')

@section('title')
   @lang('Fund Requests')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header">
        <h1>@lang('Fund Requests')</h1>
    </div>
</section>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header justify-content-between flex-grow-1">
                <form action="" class="row justify-content-md-end flex-grow-1">
                    <div class="col-12 col-md-6 col-xl-3 mb-2">
                      <div class="input-group has_append w-100">
                          <input type="text" class="form-control" placeholder="@lang('Unique ID')" name="search" value="{{$search ?? ''}}"/>
                          <div class="input-group-append">
                              <button class="input-group-text bg-primary border-0"><i class="fas fa-search text-white"></i></button>
                          </div>
                      </div>
                  </div>
                </form>
            </div>
            <div class="card-body text-center">
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable">
                        <thead>
                          <tr>
                            <th>@lang('Date')</th>
                            <th>@lang('Unique ID')</th>
                            <th>@lang('Agent')</th>
                            <th>@lang('Requested Amount')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Collection Status')</th>
                            <th>@lang('Action')</th>
                          </tr>
                        </thead>
                        <tbody>
                          @forelse($requests as $req)
                   
                            <tr>
                              <td data-label="@lang('Date')">{{dateFormat($req->created_at,'d-M-Y')}}</td>
                              <td data-label="@lang('Unique ID')">{{$req->unique_code}}</td>
                              <td data-label="@lang('Agent')"><a href="">{{$req->agent->name}}</a></td>
                              <td data-label="@lang('Requested Amount')">
                                {{numFormat($req->amount)}} {{$req->currency->code}}
                              </td>
                              <td data-label="@lang('Status')">
                                <div class="d-flex align-items-center justify-content-center">
                                    @if($req->status == 1)
                                    <span class="badge badge-success m-1">@lang('Accepted')</span>
                                    @elseif($req->status == 2)
                                        <span class="badge badge-danger m-1">@lang('Rejected')</span>
                                        <button class="btn btn-sm bg-dark text-white reason m-1" data-reason="{{$req->reject_reason}}"><i class="fas fa-info"></i></button>
                                    @else
                                        <span class="badge badge-warning m-1">@lang('Pending')</span>
                                    @endif
                                </div>
                             </td>
                             <td data-label="@lang('Collection Status')">
                                <div class="d-flex align-items-center justify-content-center">
                                    @if($req->collect == 1)
                                        <span class="badge badge-success m-1">@lang('Collected')</span>
                                    @else
                                        <span class="badge badge-warning m-1">@lang('Due')</span>
                                    @endif
                                </div>
                             </td>
                             <td data-label="@lang('Action')">
                                <div class="d-flex flex-wrap align-items-center justify-content-end justify-content-lg-center">
                                    @if($req->collect != 1)
                                       <button class="btn btn-info btn-sm details m-1 collect" data-url="{{route('admin.agent.fund.collect',$req->id)}}" data-toggle="tooltip" title="@lang('Mark as collected')"><i class="fas fa-caret-square-up"></i></button>
                                    @else
                                    <button class="btn btn-info btn-sm details m-1 disabled"><i class="fas fa-caret-square-up"></i></button>
                                    @endif
                                    @if($req->status != 1 && $req->status != 2)
                                       <button class="btn btn-success btn-sm details m-1 accept" data-url="{{route('admin.agent.fund.accept',$req->id)}}" data-toggle="tooltip" title="@lang('Accept')"><i class="fas fa-check-circle"></i></button>
                                    @else
                                    <button class="btn btn-success btn-sm details m-1 disabled"><i class="fas fa-check-circle"></i></button>
                                    @endif
                                    @if($req->status == 0)
                                        <button class="btn btn-danger btn-sm reject m-1" data-toggle="tooltip" title="@lang('Reject')" data-url="{{route('admin.agent.fund.reject',$req->id)}}"><i class="fas fa-ban"></i></button>
                                  
                                    @else
                                    <button class="btn btn-danger btn-sm  m-1 disabled"><i class="fas fa-ban"></i></button>
                                    @endif
                                </div>
                             </td>
                            </tr>
                          @empty
                          <tr><td class="text-center" colspan="12">@lang('No data found!')</td></tr>
                          @endforelse
                        </tbody>
                      </table>
                    </div>
                </div>
                @if ($requests->hasPages())
                  {{ $requests->links('admin.partials.paginate') }}
                @endif
            </div> 
           
        </div>
    </div>
</div>


<div class="modal fade" id="collect" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <form action="" method="post">
             @csrf
            <div class="modal-content">
               
                <div class="modal-body">
                    <div class="container-fluid mt-4">
                        <h5>@lang('Are you collected the fund')?</h5>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn-primary btn-sm" >@lang('Yes')</button>
                </div>
            </div>
       </form>
    </div>
</div>
<div class="modal fade" id="accept" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <form action="" method="post">
             @csrf
            <div class="modal-content">
               
                <div class="modal-body">
                    <div class="container-fluid mt-4">
                        <h5>@lang('Are you sure to accept fund request')?</h5>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary btn-sm" >@lang('Accept')</button>
                </div>
            </div>
       </form>
    </div>
</div>


<div class="modal fade" id="reject" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="" method="post">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">@lang('Reject reason')</h5>
          </div>
          <div class="modal-body">
            <div>
              <textarea class="form-control" name="reject_reason" rows="5" required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-dark ms-auto" data-dismiss="modal">@lang('Close')</button>
            <button type="submit" class="btn btn-primary ms-auto">@lang('Submit')</button>
          </div>
        </div>
      </form>
    </div>
</div>
<div class="modal fade" id="reason" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
    
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">@lang('Reject reasons')</h5>
          </div>
          <div class="modal-body">
            <div>
              <textarea class="form-control r-reasons" rows="5" disabled></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-dark ms-auto" data-dismiss="modal">@lang('Close')</button>
          </div>
        </div>
      
    </div>
</div>
@endsection
@push('script')
    <script>
        'use strict';
        $('.reject').on('click',function() { 
            $('#reject').find('form').attr('action',$(this).data('url'))
            $('#reject').modal('show')
        })
        $('.reason').on('click',function() { 
            $('#reason').find('.r-reasons').val($(this).data('reason'))
            $('#reason').modal('show')
        })
        $('.accept').on('click',function() { 
            $('#accept').find('form').attr('action',$(this).data('url'))
            $('#accept').modal('show')
        })
        $('.collect').on('click',function() { 
            $('#collect').find('form').attr('action',$(this).data('url'))
            $('#collect').modal('show')
        })
    </script>
@endpush