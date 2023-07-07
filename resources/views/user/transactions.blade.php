@extends('layouts.user')

@section('title')
   @lang('Transactions')
@endsection

@section('breadcrumb')
  @lang('Transactions')
@endsection

@push('extra')
   <form action="" class="d-flex justify-content-end">
    <div class="form-group me-2">
      <select  class="form-control me-2 shadow-none" onChange="window.location.href=this.value">
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

            <option value="{{filter('remark','withdraw_reject')}}" {{request('remark') == 'withdraw_reject' ? 'selected':''}}>@lang('Withdraw Reject')</option>
  
            <option value="{{filter('remark','redeem_voucher')}}" {{request('remark') == 'redeem_voucher' ? 'selected':''}}>@lang('Redeem Voucher')</option>
  
            <option value="{{filter('remark','create_voucher')}}" {{request('remark') == 'create_voucher' ? 'selected':''}}>@lang('Create Voucher')</option>

            <option value="{{filter('remark','deposit')}}" {{request('remark') == 'deposit' ? 'selected':''}}>@lang('Deposit')</option>

      </select>
    </div>
    <div class="form-group">
      <div class="input-group">
          <input type="text" class="form-control shadow-none" value="{{$search ?? ''}}" name="search" placeholder="@lang('Transaction ID')">
              <button class="input-group-text btn btn-primary text-white" id="my-addon"><i class="fas fa-search"></i>
              </button>
      </div>
  </div>
   </form>
@endpush

@section('content')
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">@lang('Transactions')</h3>
                  </div>
                 
                  <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable">
                      <thead>
                        <tr>
                          <th>@lang('Date')</th>
                          <th>@lang('Transaction ID')</th>
                          <th>@lang('Remark')</th>
                          <th>@lang('Amount')</th>
                          <th>@lang('Details')</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($transactions as $item)
                          <tr>
                            <td data-label="@lang('Date')">{{dateFormat($item->created_at,'d-M-Y')}}</td>
                            <td data-label="@lang('Transaction ID')">
                              {{__($item->trnx)}}
                            </td>
                            <td data-label="@lang('Remark')">
                              <span class="badge badge-dark">{{ucwords(str_replace('_',' ',$item->remark))}}</span>
                            </td>
                            <td data-label="@lang('Amount')">
                                <span class="{{$item->type == '+' ? 'text-success':'text-danger'}}">{{$item->type}} {{amount($item->amount,$item->currency->type,2)}} {{$item->currency->code}}</span> 
                            </td>
                            <td data-label="@lang('Details')">
                                <button class="btn btn-primary btn-sm details" data-data="{{$item}}">@lang('Details')</button>
                            </td>
                          </tr>
                        @empty
                        <tr><td class="text-center" colspan="12">@lang('No data found!')</td></tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                  @if ($transactions->hasPages())
                      <div class="card-footer">
                          {{$transactions->links()}}
                      </div>
                  @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="modal-success" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-primary"></div>
            <div class="modal-body text-center py-4">
            <i  class="fas fa-info-circle fa-3x text-primary mb-2"></i>
            <h3>@lang('Transaction Details')</h3>
            <p class="trx_details"></p>
            <ul class="list-group mt-2">
               
            </ul>
            </div>
            <div class="modal-footer">
            <div class="w-100">
                <div class="row">
                <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
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
        var url = "{{url('user/transaction/details/')}}"+'/'+$(this).data('data').id
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