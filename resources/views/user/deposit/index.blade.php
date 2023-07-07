@extends('layouts.user')

@section('title')
    @lang('Deposit')
@endsection

@section('breadcrumb')
@lang('Deposit')
@endsection

@section('content')
<div class="container-xl">
    <div class="row row-deck row-cards mb-5">
        <div class="col-12">
            <div class="card">
            <div class="card-body">
                <form action="{{route('user.deposit.submit')}}" id="form" method="post">
                  @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="form-label ">@lang('Amount : ')  </div>
                            <input type="text" name="amount" id="amount" class="form-control shadow-none mb-2"  required>
                        </div>

                        <div class="col-md-6">
                            <div class="form-label">@lang('Select Wallet')</div>
                            <select class="form-select wallet shadow-none" name="wallet">
                                <option value="" selected>@lang('Select')</option>
                                @foreach ($wallets as $wallet)
                                  <option value="{{$wallet->id}}" data-currency="{{$wallet->id}}" data-code="{{$wallet->code}}">{{$wallet->code}}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="curr_code">
                        <div class="col-md-6">
                            <div class="form-label">@lang('Select Gateway')</div>
                            <select class="form-select method shadow-none" name="gateway" disabled>
                                <option value="" selected>@lang('Select')</option>
                            </select>
                        </div>

                        <div class="col-md-12 my-3 info d-none">
                            <ul class="list-group mt-2">
                                <li class="list-group-item d-flex justify-content-between font-weight-bold">@lang('Amount : ')<span class="exAmount"></span></li>
                            </ul>
                        </div>
                      
                        <div class="col-md-12 mb-3">
                            <div class="form-label">&nbsp;</div>
                            <a href="#" class="btn btn-primary w-100 confirm">
                                @lang('Confirm')
                            </a>
                        </div>


                        <div class="modal modal-blur fade" id="modal-success" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                <div class="modal-status bg-primary"></div>
                                <div class="modal-body text-center py-4">
                                <i  class="fas fa-info-circle fa-3x text-primary mb-2"></i>
                                <h3>@lang('Confirm Payment')</h3>
                               
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
      <div class="col-md-12 d-flex justify-content-between">
          <h2> @lang('Recent Deposits')</h2>
          <a href="{{route('user.deposit.history')}}" class="btn btn-primary">@lang('See All')</a>
      </div>
      <div class="col-12">
          <div class="card">
              <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                      <tr>
                        <th>@lang('Amount')</th>
                        <th>@lang('Charge')</th>
                        <th>@lang('Method')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Date')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($deposits as $item)
           
                        <tr>
                          <td data-label="@lang('Amount')">{{amount($item->amount,$item->currency->type,2)}} {{$item->currency->code}}</td>
                          <td data-label="@lang('Charge')">{{amount($item->charge,$item->currency->type,2)}} {{$item->currency->code}}</td>
                          <td data-label="@lang('Method')">{{$item->gateway->name}}</td>
                          <td data-label="@lang('Status')"><span class="badge {{$item->status == 'completed' ? 'bg-success':'bg-warning'}}">{{$item->status}}</span></td>
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
       $('.wallet').on('change',function () { 

            if($('#amount').val() == ''){
                toast('error','@lang('Please provide the amount first.')')
                return false;
            }

            $.get('{{route('user.gateway.methods')}}',{currency:$('.wallet option:selected').data('currency')},function (res) { 
                if(res == 'empty'){
                    toast('error','@lang('No payment method found associate with this currency.')')
                    $('.method').attr('disabled',true)
                    return false;
                }

                var html = `<option value="">@lang('Select')</option>`;
                $.each(res, function (i, val) { 
                    html += `<option value="${val.id}">${val.name}</option>`
                });
                $('input[name=curr_code]').val( $('.wallet option:selected').data('code'))
                $('.method').attr('disabled',false)
                $('.method').html(html)
            })
        })



        $('.method').on('change',function () { 
            var amount = parseFloat($('#amount').val())
            var selected = $('.method option:selected')

            if(selected.val() == '' ){
                $('.info').addClass('d-none')
                return false;
            }
            if($('#amount').val() == ''){
                toast('error','@lang('Please provide the amount first.')')
                return false;
            }
            if($('.wallet').val() == ''){
                toast('error','@lang('Please select the wallet first.')')
                return false;
            }

            var wallet = $('.wallet option:selected')
            var min = parseFloat(selected.data('min')).toFixed(8)
            var max = parseFloat(selected.data('max')).toFixed(8)
            var fixed = parseFloat(selected.data('fixed')).toFixed(8)
            var percent = parseFloat(selected.data('percent')).toFixed()
            var code = wallet.data('code')

            var totalCharge = parseFloat(fixed)+parseFloat((amount * (percent/100)))

            $('.limit').text('Min : '+min+' '+code+' --- '+ 'Max : '+max+' '+code)
            $('.charge').text('Total Charge : '+fixed+' '+code+' + '+percent+'%')

            if(min > amount || max < amount){
                toast('error','Please follow the limit')
                return false;
            }

            $('.info').removeClass('d-none')
            $('.exAmount').text(amount +' '+ code)
            $('.exCharge').text(totalCharge +' '+ code)
            $('.total_amount').text(amount+totalCharge +' '+ code)
            $('.instruction').html(selected.data('ins'))
        })

        $('.confirm').on('click',function () { 
            var selectedMethod = $('.method option:selected')
            var selectedWallet = $('.wallet option:selected')

            if(selectedMethod.val() == '' ){
                $('.info').addClass('d-none')
                toast('error','@lang('Please select the payment method first.')')
                return false;
            }
            if($('#amount').val() == ''){
                toast('error','@lang('Please provide the amount first.')')
                return false;
            }
            if(selectedWallet.val() == ''){
                toast('error','@lang('Please select the wallet first.')')
                return false;
            }

            $('#modal-success').modal('show')
        })
   </script>
@endpush