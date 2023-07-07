@extends('layouts.merchant')

@section('title')
   @lang('Transactions')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header">
        <h1>@lang('Transactions')</h1>
    </div>
</section>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <form action="" class="row justify-content-md-end flex-grow-1">
                  <div class="col-12 col-md-6 col-xl-3 mb-2">
                    <select  class="form-control w-100" onChange="window.location.href=this.value">
                        <option value="{{filter('remark','')}}">@lang('Select Remark')</option>

                        <option value="{{filter('remark','merchant_payment')}}" {{request('remark')=='merchant_payment' ? 'selected':''}}>@lang('Merchant Payment')</option>
                        
                        <option value="{{filter('remark','merchant_api_payment')}}" {{request('remark')=='merchant_api_payment' ? 'selected':''}}>@lang('Merchant API Payment')</option>

                        <option value="{{filter('remark','withdraw_money')}}" {{request('remark')=='withdraw_money' ? 'selected':''}}>@lang('Withdraw Money')</option>
                    </select>

                  </div>
                    <div class="col-12 col-md-6 col-xl-3 mb-2">
                      <div class="input-group has_append w-100">
                          <input type="text" class="form-control" placeholder="@lang('Transaction ID')" name="search" value="{{$search ?? ''}}"/>
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
                            <th>@lang('Transaction ID')</th>
                            <th>@lang('Description')</th>
                            <th>@lang('Remark')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Details')</th>
                          </tr>
                        </thead>
                        <tbody>
                          @forelse($transactions as $trx)
                   
                            <tr>
                              <td data-label="@lang('Date')">{{dateFormat($trx->created_at,'d-M-Y')}}</td>
                              <td data-label="@lang('Transaction ID')">{{$trx->trnx}}</td>
                              <td data-label="@lang('Description')">
                                {{__($trx->details)}}
                              </td>
                              <td data-label="@lang('Remark')">
                                <span class="badge badge-dark">{{ucwords(str_replace('_',' ',$trx->remark))}}</span>
                              </td>
                              <td data-label="@lang('Amount')">
                                  <span class="{{$trx->type == '+' ? 'text-success':'text-danger'}}">{{$trx->type}} {{amount($trx->amount,$trx->currency->type,2)}} {{$trx->currency->code}}</span> 
                              </td>
                              <td data-label="@lang('Details')" class="text-end">
                                  <button class="btn btn-primary btn-sm details" data-data="{{$trx}}">@lang('Details')</button>
                              </td>
                            </tr>
                          @empty
                          <tr><td class="text-center" colspan="12">@lang('No data found!')</td></tr>
                          @endforelse
                        </tbody>
                      </table>
                    </div>
                </div>
                @if ($transactions->hasPages())
                  {{ $transactions->links('merchant.partials.paginate') }}
                @endif
            </div> 
           
        </div>
    </div>
</div>

<div class="modal fade" id="modal-success" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-body text-center py-4">
        <h3>@lang('Transaction Details')</h3>
        <p class="trx_details"></p>
        <ul class="list-group mt-2">
           
        </ul>
        </div>
        <div class="modal-footer">
        <div class="w-100">
            <div class="row">
            <div class="col"><a href="#" class="btn w-100 btn-dark" data-dismiss="modal">
                @lang('Close')
                </a></div>
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
   
      $('.details').on('click',function () { 
        var url = "{{url('merchant/transaction/details/')}}"+'/'+$(this).data('data').id
        $('.trx_details').text($(this).data('data').details)
        $.get(url,function (res) { 
          if(res == 'empty'){
            $('.list-group').html('<p>@lang('No details found!')</p>')
          }else{
            $('.list-group').html(res)
          }
          $('#modal-success').modal('show')
        })
      })
    </script>
@endpush

@push('style')
    <style>
        .form-control{
            height: calc(1.8em + 0.75rem + 2px);
        }
    </style>
@endpush