@extends('layouts.merchant')

@section('title')
   @lang('Merchant Dashboard')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header">
        <h1>@lang('Merchant Dashboard')</h1>
    </div>
</section>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 mb-3">
        <h6>@lang('Wallets')</h6>
    </div>
    @foreach ($wallets as $item)
    <div class="col-xl-3 col-md-6 currency--card">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary text-white">
                {{$item->currency->code}}
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>@lang($item->currency->curr_name)</h4>
                </div>
                <div class="card-body">
                    {{amount($item->balance,$item->currency->type,2)}} {{$item->currency->code}}
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row row-deck row-cards">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>@lang('Recent Transactions')</h4>
            </div>
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
                      @forelse($recentTransactions as $trx)
               
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
        </div>
    </div>


<div class="row row-deck row-cards">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>@lang('Recent Withdraws')</h4>
            </div>
            <div class="table-responsive">
            <table class="table table-vcenter card-table table-striped">
                <thead>
                <tr>
                    <th>@lang('Transaction')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Charge')</th>
                    <th>@lang('Total Amount')</th>
                    <th>@lang('Method Name')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Date')</th>
                </tr>
                </thead>
                <tbody>
                 @forelse ($recentWithdraw as $item)
                    <tr>
                        <td data-label="@lang('Transaction')">{{$item->trx}}</td>
                        <td data-label="@lang('Amount')">{{amount($item->amount,$item->currency->type,2)}} {{$item->currency->code}}</td>
                        <td data-label="@lang('Charge')">{{amount($item->charge,$item->currency->type,2)}} {{$item->currency->code}}</td>
                        <td data-label="@lang('Total Amount')">{{amount($item->total_amount,$item->currency->type,2)}} {{$item->currency->code}}</td>
                        <td data-label="@lang('Method Name')">{{$item->method->name}}</td>
                        <td data-label="@lang('Status')">
                            @if($item->status == 1)
                                <span class="badge bg-success">@lang('Accepted')</span>
                            @elseif($item->status == 2)
                                <span class="badge badge-danger">@lang('Rejected')</span>
                                <button class="btn btn-sm bg-dark text-white reason" data-bs-toggle="modal" data-bs-target="#modal-team" data-reason="{{$item->reject_reason}}"><i class="fas fa-info"></i></button>
                            @else
                                <span class="badge badge-warning">@lang('Pending')</span>
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

      $('.reason').on('click',function() { 
            $('#modal-team').find('.reject-reason').val($(this).data('reason'))
            $('#modal-team').modal('show')
        })
    </script>
@endpush