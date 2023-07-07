@extends('layouts.admin')

@section('title')
   @lang('Deposits')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header">
        <h1>@lang('Deposits')</h1>
        
    </div>
</section>
@endsection

@section('content')
        
<div class="row">
    <div class="col-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-end">
                <form action="" class="d-flex flex-wrap justify-content-end">
                    <div class="form-group m-1 flex-grow-1">
                     <select class="form-control" onChange="window.location.href=this.value">
                         <option value="{{filter('status','')}}">@lang('All Deposits')</option>
                     
                         <option value="{{filter('status','pending')}}" {{request('status')=='pending'?'selected':''}}>@lang('Pending Deposits')</option>
                      
                         <option value="{{filter('status','completed')}}" {{request('status')=='completed'?'selected':''}}>@lang('Completed Deposits')</option>

                         <option value="{{filter('status','rejected')}}" {{request('status')=='rejected'?'selected':''}}>@lang('Rejected Deposits')</option>
                     </select>
                    </div>
                    
                     <div class="form-group m-1 flex-grow-1">
                         <div class="input-group">
                             <input type="text" class="form-control" name="search" value="{{$search ?? ''}}" placeholder="@lang('Transaction ID')">
                             <div class="input-group-append">
                                 <button class="input-group-text btn btn-primary text-white" id="my-addon"><i class="fas fa-search"></i></button>
                             </div>
                         </div>
                     </div>
                </form>
            </div>
            <div class="card-body text-center">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>@lang('Transaction ID')</th>
                            <th>@lang('User')</th>
                            <th>@lang('Amount(With Charge)')</th>
                            <th>@lang('Charge')</th>
                            <th>@lang('Method')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Details')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        @forelse ($deposits as $info)
                            <tr>

                                 <td data-label="@lang('Transaction ID')">
                                   {{$info->txn_id}}
                                 </td>
                                 <td data-label="@lang('User')">{{$info->user->email}}</td>
                                 <td data-label="@lang('Amount')">{{amount($info->amount,$info->currency->type,2)}} {{$info->currency->code}}</td>
                                 <td data-label="@lang('Charge')">{{amount($info->charge,$info->currency->type,2)}} {{$info->currency->code}}</td>
                                 <td data-label="@lang('Method')">{{$info->gateway->name}}</td>
                                 <td data-label="@lang('Status')">
                                    @if ($info->status == 'pending')
                                    <span class="badge badge-warning"> {{ucfirst($info->status)}} </span>
                                    @elseif($info->status == 'rejected')
                                    <span class="badge badge-danger"> {{ucfirst($info->status)}} </span>
                                    @else
                                    <span class="badge badge-success"> {{ucfirst($info->status)}} </span>
                                    @endif
                                 </td>
                               
                                 <td data-label="@lang('Details')">
                                    <small>{{$info->trx_details  ?? 'N/A'}}</small>
                                 </td>

                                 <td data-label="@lang('Action')">
                                    <div class="d-flex flex-wrap flex-lg-nowrap align-items-center justify-content-end justify-content-lg-center">
                                        @if ($info->status == 'pending')
                                        <a href="javascript:void()" class="btn btn-primary approve btn-sm m-1" data-id="{{$info->id}}" data-toggle="tooltip" title="@lang('Approve')"><i class="fas fa-check"></i></a>
     
                                        <a href="javascript:void()" class="btn btn-danger reject btn-sm m-1" data-id="{{$info->id}}"data-toggle="tooltip" title="@lang('Reject')"><i class="fas fa-ban"></i></a>
                                        @else
                                        <a href="javascript:void()" class="btn btn-primary disabled btn-sm m-1"><i class="fas fa-check"></i></a>
     
                                        <a href="javascript:void()" class="btn btn-danger disabled btn-sm m-1"><i class="fas fa-ban"></i></a>
                                        @endif
                                    </div>
                                 </td>
                            </tr>
                         @empty

                            <tr>
                                <td class="text-center" colspan="100%">@lang('No Data Found')</td>
                            </tr>

                        @endforelse
                    </table>
                </div>
            </div>
            @if ($deposits->hasPages())
                {{ $deposits->links() }}
            @endif
        </div>
    </div>
</div>

<div id="approveModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{route('admin.approve.deposit')}}" method="POST">
            @csrf
            <input type="hidden" name="id">
            <div class="modal-content">
                <div class="modal-body">
                    <h6 class="mt-3">@lang('Are you sure to approve?')</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div id="rejectModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{route('admin.reject.deposit')}}" method="POST">
            @csrf
            <input type="hidden" name="id">
            <div class="modal-content">
                <div class="modal-body">
                    <h6 class="mt-3">@lang('Are you sure to reject?')</h6>

                    <div class="form-group">
                        <label>@lang('Reject Reasons')</label>
                        <textarea name="reject_reason" class="form-control mt-2"  rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-danger">@lang('Confirm')</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('script')
    <script>
        'use strict';
        $('.approve').on('click',function () { 
            $('#approveModal').find('input[name=id]').val($(this).data('id'))
            $('#approveModal').modal('show')
        })
        $('.reject').on('click',function () { 
            $('#rejectModal').find('input[name=id]').val($(this).data('id'))
            $('#rejectModal').modal('show')
        })
    </script>
@endpush

