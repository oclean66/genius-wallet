@extends('layouts.user')

@section('title')
   @lang('Exchange Money')
@endsection

@section('breadcrumb')
   @lang('Exchange Money')
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
                        <div class="col-md-12 mb-3">
                            <div class="form-label">@lang('Amount')</div>
                            <input type="text" name="amount" class="form-control amount shadow-none" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-label">@lang('From Currency')</div>
                            <select class="form-select from shadow-none" name="from_wallet_id">
                                <option value="" selected>@lang('Select')</option>
                                @foreach ($wallets as $wallet)
                                  <option value="{{$wallet->id}}" data-curr="{{$wallet->currency->id}}" data-rate="{{$wallet->currency->rate}}" data-code="{{$wallet->currency->code}}" data-type="{{$wallet->currency->type}}">{{$wallet->currency->code}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-label">@lang('To Currency')</div>
                            <select class="form-select to shadow-none" name="to_wallet_id" disabled>
                                <option value="" selected>@lang('Select')</option>
                                @foreach ($currencies as $curr)
                                  <option value="{{$curr->id}}" data-rate="{{$curr->rate}}" data-code="{{$curr->code}}"  data-type="{{$curr->type}}">{{$curr->code}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 mb-3 info d-none">
                            <ul class="list-group mt-2">
                                <li class="list-group-item d-flex justify-content-between font-weight-bold">@lang('From Currency : ')<span class="fromCurr"></span></li>

                                <li class="list-group-item d-flex justify-content-between font-weight-bold">@lang('To Currency : ')<span class="toCurr"></span></li>

                                <li class="list-group-item d-flex justify-content-between font-weight-bold">@lang('Exchange Amount : ')<span class="exAmount"></span></li>


                                <li class="list-group-item d-flex justify-content-between font-weight-bold">@lang('Exchange Charge : ')<span class="exCharge"></span></li>
                                
                                <li class="list-group-item d-flex justify-content-between font-weight-bold">@lang('Will get : ')<span class="total_amount"></span></li>
                            </ul>
                        </div>

                      
                        <div class="col-md-12 mb-3">
                            <div class="form-label">&nbsp;</div>
                            <a href="#" class="btn btn-primary exchange w-100">
                                @lang('Exchange') 
                            </a>
                        </div>


                        <div class="modal modal-blur fade" id="modal-success" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                <div class="modal-status bg-primary"></div>
                                <div class="modal-body text-center py-4">
                                    <i  class="fas fa-info-circle fa-3x text-primary mb-2"></i>
                                    <h3>@lang('Are you sure to exchange?')</h3>
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
            <h2> @lang('Recent Exchanges')</h2>
            <a href="{{route('user.exchange.history')}}" class="btn btn-primary">@lang('See All')</a>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                  <table class="table table-vcenter card-table table-striped">
                    <thead>
                      <tr>
                        <th>@lang('From Currency')</th>
                        <th>@lang('From Amount')</th>
                        <th>@lang('To Currency')</th>
                        <th>@lang('To Amount')</th>
                        <th>@lang('Charge')</th>
                        <th>@lang('Date')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($recentExchanges as $item)
                        <tr>
                          <td data-label="@lang('From Currency')">{{@$item->fromCurr->code}}</td>
                          <td data-label="@lang('From Amount')">{{amount($item->from_amount,$item->fromCurr->type,2)}} {{@$item->fromCurr->code}}</td>
                          <td data-label="@lang('To Currency')">{{@$item->toCurr->code}}</td>
                          <td data-label="@lang('To Amount')">{{amount($item->to_amount,$item->toCurr->type,2)}} {{@$item->toCurr->code}}</td>
                          <td data-label="@lang('Charge')">{{amount($item->charge,$item->fromCurr->type,2)}} {{$item->fromCurr->code}}</td>
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
        $('.from').on('change',function () { 
            if($('.amount').val() == ''){
                toast('error','@lang('Please provide the amount first')')
                return false
            }
            else if($('.amount').val() < 0){
                toast('error','@lang('Please provide the valid amount')')
                return false
            }

            var from = $('.from option:selected');
            var to = $('.to option:selected');

            if(from.data('curr') ==  to.val()){
                toast('error','@lang('Exchange can not be possible within same currency.')')
                $('.info').addClass('d-none')
                return false
            }else{
                if( to.val() != '') $('.info').removeClass('d-none')
                
            }

            exchange();
            $('.to').attr('disabled',false)
        })

        $('.amount').on('keyup',function () { 
            exchange();
        })

        function exchange() {
            var from = $('.from option:selected');
            var to = $('.to option:selected');

            var amount = parseFloat($('.amount').val())
            var fromCode = from.data('code')
            var toCode   = to.data('code')
            var fromRate = parseFloat(from.data('rate'))
            var toRate =  parseFloat(to.data('rate'))
            var defaultAmount = amount/fromRate;
            var finalAmount = defaultAmount * toRate;

            var charge   = (parseFloat('{{$charge->fixed_charge}}')* fromRate) + (amount * ('{{$charge->percent_charge}}'/100))

            $('.fromCurr').text(fromCode)
            $('.toCurr').text(toCode)
            $('.exAmount').text(amount +' '+ fromCode)
            $('.exCharge').text(charge.toFixed(8) +' '+ fromCode)
            $('.total_amount').text(finalAmount.toFixed(8) +' '+ toCode)
        }

        $('.to').on('change',function () { 
            var from = $('.from option:selected');
            var to = $('.to option:selected');

            if(from.data('curr') ==  to.val()){
                toast('error','@lang('Exchange can not be possible within same currency.')')
                $('.info').addClass('d-none')
                return false
            }

            exchange();
            if(to.val() != ''){
                $('.info').removeClass('d-none')
            }else{
                $('.info').addClass('d-none')
            }
        })

        $('.exchange').on('click',function () { 
            var from = $('.from option:selected');
            var to = $('.to option:selected').val();
            var amount = $('.amount').val()

            if(from.val() == '' || to == '' || amount == '' ){
                toast('error','@lang('Please provide the necessary informations')')
                return false
            }
            if(from.data('curr') ==  to){
                toast('error','@lang('Exchange can not be possible within same currency.')')
                return false
            }
            $('#modal-success').modal('show')
        })

        $('.confirm').on('click',function () { 
            $('#form').submit()
            $(this).attr('disabled',true)
        })

    </script>
@endpush