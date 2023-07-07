@extends('layouts.agent')

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
                <button class="btn btn-primary" data-toggle="modal" data-target="#fund"><i class="fas fa-plus"></i> @lang('New Fund Request')</button>
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
                            <th>@lang('Requested Amount')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Collection Status')</th>
                          </tr>
                        </thead>
                        <tbody>
                          @forelse($requests as $req)
                   
                            <tr>
                              <td data-label="@lang('Date')">{{dateFormat($req->created_at,'d-M-Y')}}</td>
                              <td data-label="@lang('Unique ID')">{{$req->unique_code}}</td>
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
                            </tr>
                          @empty
                          <tr><td class="text-center" colspan="12">@lang('No data found!')</td></tr>
                          @endforelse
                        </tbody>
                      </table>
                    </div>
                </div>
                @if ($requests->hasPages())
                  {{ $requests->links('merchant.partials.paginate') }}
                @endif
            </div> 
           
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="fund" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{route('agent.fund.request.submit')}}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('New fund request')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('Request Amount')</label>
                        <input class="form-control" type="text" name="amount" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('Select Currency')</label>
                        <select class="form-control js-example-basic-single" name="currency" required>
                            @foreach ($currencies as $item)
                              <option value="{{$item->id}}">{{$item->code}}</option>
                            @endforeach
                        </select>
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

<div class="modal modal-blur fade" id="modal-team" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">@lang('Reject Reasons')</h5>
          
        </div>
        <div class="modal-body">
          <div>
            <textarea class="form-control reject-reason" rows="5" disabled></textarea>
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
        $('.reason').on('click',function() { 
            $('#modal-team').find('.reject-reason').val($(this).data('reason'))
            $('#modal-team').modal('show')
        })

        $(document).ready(function() {
           $('.js-example-basic-single').select2({
            dropdownParent: $('#fund')
           });
        });
    </script>
@endpush