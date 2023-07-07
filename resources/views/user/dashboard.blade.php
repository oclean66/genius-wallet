@extends('layouts.user')
@section('title')
    @lang('User Dashboard')
@endsection
@section('breadcrumb')
    @lang('Dashboard')
@endsection
@section('content')
<div class="container-xl">
  <div class="row mb-3">
  
    <div class="col-sm-6 col-xl-3 mb-3">
      <div class="card card-sm">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-auto">
              <span class="bg-danger text-white avatar">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-right-left"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"  stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <line x1="21" y1="7" x2="3" y2="7"></line>
                  <path d="M18 10l3 -3l-3 -3"></path>
                  <path d="M6 20l-3 -3l3 -3"></path>
                  <line x1="3" y1="17" x2="21" y2="17"></line>
               </svg>
              </span>
            </div>
            <div class="col">
              <div class="font-weight-medium">
                {{numFormat($totalTransferMoney,2)}} {{$gs->curr_code}} <sup class="text-danger">*</sup>
              </div>
              <div class="text-muted">
                @lang('Total Money Transfered')
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3 mb-3">
      <div class="card card-sm">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-auto">
              <span class="bg-success text-white avatar">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-currency-dogecoin" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M6 12h6"></path>
                  <path d="M9 6v12"></path>
                  <path d="M6 18h6a6 6 0 1 0 0 -12h-6"></path>
               </svg>
              </span>
            </div>
            <div class="col">
              <div class="font-weight-medium">
                {{numFormat($totalDeposit,2)}} {{$gs->curr_code}} <sup class="text-danger">*</sup>
              </div>
              <div class="text-muted">
                @lang('Total Deposit')
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3 mb-3">
      <div class="card card-sm">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-auto">
              <span class="bg-warning text-white avatar">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-copy" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <rect x="8" y="8" width="12" height="12" rx="2"></rect>
                  <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2"></path>
               </svg>
              </span>
            </div>
            <div class="col">
              <div class="font-weight-medium">
                {{numFormat($totalWithdraw,2)}} {{$gs->curr_code}} <sup class="text-danger">*</sup>
              </div>
              <div class="text-muted">
                @lang('Total Withdraw')
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3 mb-3">
      <div class="card card-sm">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-auto">
              <span class="bg-info text-white avatar">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-right-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <line x1="21" y1="7" x2="3" y2="7"></line>
                  <path d="M18 10l3 -3l-3 -3"></path>
                  <path d="M6 20l-3 -3l3 -3"></path>
                  <line x1="3" y1="17" x2="21" y2="17"></line>
               </svg>
              </span>
            </div>
            <div class="col">
              <div class="font-weight-medium">
                {{numFormat($totalExchange,2)}} {{$gs->curr_code}} <sup class="text-danger">*</sup>
              </div>
              <div class="text-muted">
                @lang('Total Exchange')
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
  
  <div class="row row-deck row-cards">
    <div class="col-lg-9">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">@lang('Latest Transactions')</h3>
          </div>
         
          <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
              <thead>
                <tr>
                  <th>@lang('Date')</th>
                  <th>@lang('Description')</th>
                  <th>@lang('Remark')</th>
                  <th>@lang('Amount')</th>
                  <th>@lang('Details')</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($transactions as $item)
                  <tr>
                    <td data-label="@lang('Date')">{{dateFormat($item->created_at,'d-M-Y')}}</td>
                    <td data-label="@lang('Description')">
                      {{__($item->details)}}
                    </td>
                    <td data-label="@lang('Remark')">
                      <span class="badge badge-dark">{{ucwords(str_replace('_',' ',$item->remark))}}</span>
                    </td>
                    <td data-label="@lang('Amount')">
                     <span class="{{$item->type == '+' ? 'text-success':'text-danger'}}">{{$item->type}} {{amount($item->amount,$item->currency->type,2)}} {{$item->currency->code}}</span> 
                    </td>
                    <td data-label="@lang('Details')" class="text-end">
                        <button class="btn btn-primary btn-sm details" data-data="{{$item}}">@lang('Details')</button>
                    </td>
                  </tr>
                @empty
                <tr><td colspan="12" class="text-center">@lang('No data found')</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
    </div>
    <div class="col-lg-3">
      <div class="card wallet__card">
        <div class="card-header">
          <h4>@lang('Your Wallets')</h4>
        </div>
        <div class="card-body card-body-scrollable card-body-scrollable-shadow">
          <div class="divide-y">
            @foreach ($wallets as $item)
            <div>
              <div class="row align-items-center">
                <div class="col-auto">
                  <span class="bg-blue text-white avatar">
                    {{$item->currency->symbol}}
                  </span>
                </div>
                <div class="col">
                  <div class="{{$item->currency->default == 1 ? 'font-weight-bold text-success' : 'font-weight-medium'}}">
                    {{amount($item->balance,$item->currency->type,2)}} {{$item->currency->code}}
                  </div>
                  <div class="text-muted">
                    {{$item->currency->curr_name}}
                  </div>
                </div>
              </div>
            </div>
            @endforeach
            
          </div>
        </div>
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
