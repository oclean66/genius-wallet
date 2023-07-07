@extends('layouts.admin')

@section('title')
   @lang('Profit Report')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header">
        <h1>@lang('Profit Report')</h1>
    </div>
</section>
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-end">
                <form action="" class="mb-2">
                    <div class="form-group mr-2 mb-0">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="@lang('Transaction ID')">
                            <div class="input-group-append">
                                <button class="input-group-text btn btn-primary text-white border-0" id="my-addon"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                 </form>


                <form action="" class="mb-2">
                    <div class="form-group mr-2 mb-0">
                        <div class="input-group has_append">
                            <input name="range" type="text" data-range="true" data-multiple-dates-separator=" - " data-language="en" class="datepicker-here form-control" data-position='bottom right' placeholder="@lang('From date - To date')" autocomplete="off" value="{{@$range}}">
                        
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
               
                <div class="form-group mb-2">
                    <select name="" class="form-control" onChange="window.location.href=this.value">
                        <option value="{{filter('remark','')}}">@lang('All Remark')</option>
                      
                        <option value="{{filter('remark','transfer_money')}}" {{request('remark') == 'transfer_money' ? 'selected':''}}>@lang('Transfer Money')</option>
                      
                        <option value="{{filter('remark','request_money')}}" {{request('remark') == 'request_money' ? 'selected':''}}>@lang('Request Money')</option>
                       
                        <option value="{{filter('remark','money_exchange')}}" {{request('remark') == 'money_exchange' ? 'selected':''}}>@lang('Money Exchange')</option>
                       
                        <option value="{{filter('remark','invoice_payment')}}" {{request('remark') == 'invoice_payment' ? 'selected':''}}>@lang('Invoice Payment')</option>
                     
                        <option value="{{filter('remark','merchant_payment')}}" {{request('remark') == 'merchant_payment' ? 'selected':''}}>@lang('Merchant Payment')</option>
                      
                        <option value="{{filter('remark','merchant_api_payment')}}" {{request('remark') ==  'merchant_api_payment' ? 'selected':''}}>@lang('Merchant API Payment')</option>
                      
                        <option value="{{filter('remark','escrow_return')}}" {{request('remark') == 'escrow_return' ? 'selected':''}}>@lang('Escrow Return')</option>
                     
                        <option value="{{filter('remark','make_escrow')}}" {{request('remark') == 'make_escrow' ? 'selected':''}}>@lang('Make Escrow')</option>

                        <option value="{{filter('remark','withdraw_money')}}" {{request('remark') == 'withdraw_money' ? 'selected':''}}>@lang('Withdraw Money')</option>

                        <option value="{{filter('remark','redeem_voucher')}}" {{request('remark') == 'redeem_voucher' ? 'selected':''}}>@lang('Redeem Voucher')</option>

                        <option value="{{filter('remark','create_voucher')}}" {{request('remark') == 'create_voucher' ? 'selected':''}}>@lang('Create Voucher')</option>
                    </select>
                </div>
                 
            </div>
            <div class="card-body text-center">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>@lang('Sl')</th>
                            <th>@lang('Remark')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('In Default Currency')</th>
                            <th>@lang('Transaction ID')</th>
                            <th>@lang('Date')</th>
                           
                        </tr>
                        @forelse ($logs as $key => $log)
                            <tr>
                                <td data-label="@lang('Sl')">{{$key + $logs->firstItem()}}</td>
                    
                                 <td data-label="@lang('Remark')">
                                   <span class="badge badge-dark">{{ucwords(str_replace('_',' ',$log->remark))}}</span>
                                 </td>
                                
                                 <td data-label="@lang('Amount')">{{amount($log->charge,$log->currency->type,2)}} {{$log->currency->code}} </td>
                                 <td data-label="@lang('In Default Currency')"><span class="text-success">{{amountConv($log->charge,$log->currency)}} {{$gs->curr_code}}</span></td>
                                 <td data-label="@lang('Transaction ID')">{{$log->trnx}}</td>
                                 <td data-label="@lang('Date')">{{dateFormat($log->created_at)}}</td>
                            </tr>
                         @empty

                            <tr>
                                <td class="text-center" colspan="100%">@lang('No Data Found')</td>
                            </tr>

                        @endforelse
                    </table>
                </div>
            </div>
            @if ($logs->hasPages())
                {{ $logs->links('admin.partials.paginate') }}
            @endif
        </div>
    </div>
</div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{asset('assets/admin/css/datepicker.min.css')}}">
@endpush

@push('script')
<script src="{{ asset('assets/admin/js/datepicker.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/datepicker.en.js') }}"></script>
    <script>
    'use strict';
    (function($){
        if(!$('.datepicker-here').val()){
            $('.datepicker-here').datepicker();
        }
    })(jQuery)
    </script>
@endpush