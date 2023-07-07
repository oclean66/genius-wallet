@extends('layouts.user')

@section('title')
    @lang('Merchant Payment')
@endsection

@section('breadcrumb')
  @lang('Payment')
@endsection

@section('content')
<div class="container-xl">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
            <div class="card-body">
                <form action="" id="form" method="post">
                  @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-label">@lang('Merchant Email') <span class="ms-2 check"></span></div>
                            <div class="input-group">
                                <input type="text" name="receiver" id="receiver" class="form-control shadow-none receiver" required>
                                <button type="button" data-bs-toggle="tooltip" data-bs-original-title="Scan QR code"  class="input-group-text scan"><i class="fas fa-qrcode"></i></button>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-label">@lang('Select Wallet')</div>
                            <select class="form-select wallet shadow-none" name="wallet">
                                <option value="" selected>@lang('Select')</option>
                                @foreach ($wallets as $wallet)
                                  <option value="{{$wallet->id}}" data-rate="{{$wallet->currency->rate}}" data-code="{{$wallet->currency->code}}">{{$wallet->currency->code}} -- ({{amount($wallet->balance,$wallet->currency->type,3)}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-label ">@lang('Amount : ')</div>
                            <input type="text" name="amount" id="amount" class="form-control shadow-none mb-2" disabled required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-label">&nbsp;</div>
                            <a href="#" class="btn btn-primary w-100 transfer">
                                @lang('Make Payment')
                            </a>
                        </div>


                        <div class="modal modal-blur fade" id="modal-success" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                <div class="modal-status bg-primary"></div>
                                <div class="modal-body text-center py-4">
                                <i  class="fas fa-info-circle fa-3x text-primary mb-2"></i>
                                <h3>@lang('Preview Payment')</h3>
                                <ul class="list-group mt-2">
                                    <li class="list-group-item d-flex justify-content-between">@lang('Payment Amount')<span class="amount"></span></li>
                                </ul>
                                </div>
                                <div class="modal-footer">
                                <div class="w-100">
                                    <div class="row">
                                    <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                                        @lang('Cancel')
                                        </a></div>
                                    <div class="col">
                                        <button type="button" class="btn btn-primary w-100 confirm">
                                           @lang('Confirm')
                                        </button>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>

    <div class="row row-deck row-cards mt-3">
        <div class="col-md-12 d-flex justify-content-between">
            <h2> @lang('Recent Payments')</h2>
            <a href="{{route('user.payment.history')}}" class="btn btn-primary">@lang('See All')</a>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                  <table class="table table-vcenter card-table table-striped">
                    <thead>
                      <tr>
                        <th>@lang('Transaction')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Details')</th>
                        <th>@lang('Date')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($recentPayments as $item)
                        <tr>
                          <td data-label="@lang('Transaction')">{{$item->trnx}}</td>
                          <td data-label="@lang('Amount')">{{amount($item->amount,$item->currency->type,2)}} {{$item->currency->code}}</td>
                          <td data-label="@lang('Details')">{{$item->details}}</td>
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
                @if ($recentPayments->hasPages())
                <div class="card-footer">
                    {{$recentPayments->links()}}
                </div>
               @endif
            </div>
        </div>
    </div>
</div>



@endsection

@push('script')

    <script>
        'use strict';

       
        $('.receiver').on('focusout',function () { 
            var url   = '{{ route('user.check.merchant') }}';
            var value = $(this).val();
            var token = '{{ csrf_token() }}';
            var data  = {receiver:value,_token:token}
           
            $.post(url,data,function(res) {
               
                if(res['data'] != null){
                    if($('.check').hasClass('text-danger')){
                        $('.check').removeClass('text-danger');
                    }
                    $('.check').text(`@lang('Valid merchant found.')`).addClass('text-success');
                    $('.transfer').attr('disabled',false)
                } else {
                    if($('.check').hasClass('text-success')){
                        $('.check').removeClass('text-success');
                    }
                    $('.check').text('@lang('Merchant not found.')').addClass('text-danger');
                    
                }
            });
        })

        $('.transfer').on('click',function () { 

            var amount = parseFloat($('#amount').val());
            var selected = $('.wallet option:selected')

            if($('#receiver').val() == '' || amount == '' || selected.val() == ''){
                toast('error','@lang('Please fill up the required fields')')
                return false;
            }
           
            $('#modal-success').find('.amount').text(amount +' '+selected.data('code'))
            $('#modal-success').modal('show')
        })

        $('.confirm').on('click',function () { 
            $('#form').submit()
            $(this).attr('disabled',true)
        })

      
      
        $('.wallet').on('change',function () { 
           if($('.wallet option:selected').val() == ''){
            $('#amount').attr('disabled',true)
              return false
            } else{
              $('#amount').attr('disabled',false)
            }
        })
    </script>
@endpush