@extends('layouts.user')

@section('title')
    @lang('Create Voucher')
@endsection

@section('breadcrumb')
   @lang('Create Voucher')
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
                        <div class="col-md-6">
                            <div class="form-label ">@lang('Amount : ') <code class="limit">@lang('min : '.$charge->minimum.' '.$gs->curr_code) -- @lang('max : '.$charge->maximum.' '.$gs->curr_code)</code> </div>
                            <input type="text" name="amount" id="amount" class="form-control shadow-none mb-2"  required>
                            <small class="text-danger charge"></small>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label">@lang('Select Wallet')</div>
                            <select class="form-select wallet shadow-none" name="wallet_id">
                                <option value="" selected>@lang('Select')</option>
                                @foreach ($wallets as $wallet)
                                  <option value="{{$wallet->id}}" data-rate="{{$wallet->currency->rate}}" data-code="{{$wallet->currency->code}}">{{$wallet->currency->code}} -- ({{amount($wallet->balance,$wallet->currency->type,2)}})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 my-3 info d-none">
                            <ul class="list-group mt-2">
                       
                                <li class="list-group-item d-flex justify-content-between font-weight-bold">@lang('Amount : ')<span class="exAmount"></span></li>


                                <li class="list-group-item d-flex justify-content-between font-weight-bold">@lang('Total Charge : ')<span class="exCharge"></span></li>
                                
                                <li class="list-group-item d-flex justify-content-between font-weight-bold">@lang('Total Amount : ')<span class="total_amount"></span></li>
                            </ul>
                        </div>
                      
                        <div class="col-md-12 mb-3">
                            <div class="form-label">&nbsp;</div>
                            <a href="#" class="btn btn-primary w-100 create">
                                @lang('Create')
                            </a>
                        </div>


                        <div class="modal modal-blur fade" id="modal-success" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                <div class="modal-status bg-primary"></div>
                                <div class="modal-body text-center py-4">
                                <i  class="fas fa-info-circle fa-3x text-primary mb-2"></i>
                                <h3>@lang('Confirm Voucher')</h3>
                               
                                </div>
                                <div class="modal-footer">
                                <div class="w-100">
                                    <div class="row">
                                    <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                                        @lang('Cancel')
                                        </a></div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary w-100 confirm">
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
        <div class="col-md-12">
            <h2> @lang('Recent Vouchers')</h2>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                  <table class="table table-vcenter card-table table-striped">
                    <thead>
                      <tr>
                        <th>@lang('Voucher Code')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Date')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($recentVouchers as $item)
                        <tr>
                          <td data-label="@lang('Voucher Code')">{{$item->code}}</td>
                          <td data-label="@lang('Amount')">{{numFormat($item->amount)}} {{$item->currency->code}}</td>
                          <td data-label="@lang('Status')">
                            @if ($item->status == 0)
                               <span class="badge bg-secondary">@lang('unused')</span>
                            @elseif ($item->status == 1)
                                <span class="badge bg-success">@lang('used')</span>
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
</div>

@endsection

@push('script')
    <script>
        'use strict';
        function limit() { 
            var selected = $('.wallet option:selected')
            var rate = selected.data('rate')

            var minimum = '{{$charge->minimum}}'
            var maximum = '{{$charge->maximum}}'

            var minLimit = minimum * rate;
            var maxLimit = maximum * rate;

            return {'minLimit': minLimit,'maxLimit': maxLimit};
        }

        function charge() { 
            var selected = $('.wallet option:selected')
            var rate = selected.data('rate')
            var amount = $('#amount').val()

            var fixedCharge   = '{{$charge->fixed_charge}}'
            var percentCharge = '{{$charge->percent_charge}}'
            var fixed = fixedCharge * rate

            var finalCharge =  fixed + (amount * (percentCharge/100))

            return {'final':finalCharge,'fixed':fixed,'percent':percentCharge};
        }

        $('.wallet').on('change',function () { 
           
            var code = $('.wallet option:selected').data('code')
            var minLimit = limit().minLimit;
            var maxLimit = limit().maxLimit;

            $('.limit').text("@lang('min : ')"+minLimit.toFixed(8)+' '+code+" -- @lang('max : ')"+maxLimit.toFixed(8)+' '+code)
            $('.charge').text("Total Charge : " + charge().fixed.toFixed(8) +' '+code+' + '+charge().percent + '%')

            if($('#amount').val() == ''){
              toast('error','@lang('Please provide the amount first')')
              return false
            } 

            if(minLimit > $('#amount').val() || maxLimit < $('#amount').val()){
                toast('error','@lang('Please follow the limit')')
                $('.info').addClass('d-none')
                return false
            }

            var totalAmount = parseFloat($('#amount').val())+parseFloat(charge().final);

            $('.exAmount').text($('#amount').val() +' '+ code)
            $('.exCharge').text(charge().final.toFixed(8) +' '+ code)
            $('.total_amount').text(totalAmount.toFixed(8) + ' '+code)

            $('.info').removeClass('d-none')

        })

        $('.create').on('click',function () { 
            if($('#amount').val() == ''){
              toast('error','@lang('Please provide the amount first')')
              return false
            } 
            if($('.wallet option:selected').val() == ''){
              toast('error','@lang('Please select the wallet.')')
              return false
            }
            $('#modal-success').modal('show')
        })
    </script>
@endpush