@extends('layouts.user')

@section('title')
    @lang('Withdraw Money')
@endsection

@section('breadcrumb')
   @lang('Withdraw Money')
@endsection

@section('content')
<div class="container-xl">
    <div class="row row-deck row-cards mb-5">
        <div class="col-12">
            <div class="card">
            <div class="card-body">
                <form action="" id="form" method="post">
                  @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="form-label ">@lang('Amount : ') <code class="limit"></code> </div>
                            <input type="text" name="amount" id="amount" class="form-control shadow-none mb-2"  required>
                            <small class="text-danger charge"></small>
                        </div>

                        <div class="col-md-6">
                            <div class="form-label">@lang('Select Wallet')</div>
                            <select class="form-select wallet shadow-none" name="wallet_id">
                                <option value="" selected>@lang('Select')</option>
                                @foreach ($wallets as $wallet)
                                  <option value="{{$wallet->id}}" data-currency="{{$wallet->currency->id}}" data-code="{{$wallet->currency->code}}">{{$wallet->currency->code}} -- ({{amount($wallet->balance,$wallet->currency->type,3)}})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <div class="form-label">@lang('Select Withdraw Method')</div>
                            <select class="form-select method shadow-none" name="method_id" disabled>
                                <option value="" selected>@lang('Select')</option>
                            </select>
                        </div>

                        <div class="col-md-12 my-3 info d-none">
                            <ul class="list-group mt-2">
                                <li class="list-group-item d-flex justify-content-between font-weight-bold">@lang('Withdraw Amount : ')<span class="exAmount"></span></li>

                                <li class="list-group-item d-flex justify-content-between font-weight-bold">@lang('Total Charge : ')<span class="exCharge"></span></li>
                                
                                <li class="list-group-item d-flex justify-content-between font-weight-bold">@lang('Total Amount : ')<span class="total_amount"></span></li>
                            </ul>

                            <div class="mt-4 text-center">
                                <h5 class="text-danger">
                                    @lang('Withdraw instruction')
                                </h5>
                                <p class="instruction mt-2"></p>
                            </div>

                            <div class="mt-3 text-center">
                                <h5 class="text-danger">
                                    @lang('Provide your withdraw account details.')
                                </h5>

                                <textarea name="user_data" id="" class="form-control shadow-none" cols="30" rows="10" required></textarea>
                            </div>
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
                                <h3>@lang('Confirm Withdraw')</h3>
                               
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

            $.get('{{route('user.withdraw.methods')}}',{currency:$('.wallet option:selected').data('currency')},function (res) { 
                if(res == 'empty'){
                    toast('error','@lang('No withdraw method found associate with this currency.')')
                    $('.method').attr('disabled',true)
                    return false;
                }

                var html = `<option value="">@lang('Select')</option>`;
                $.each(res, function (i, val) { 
                    html += `<option value="${val.id}" data-min="${val.min_amount}" data-max="${val.max_amount}" data-fixed="${val.fixed_charge}" data-percent="${val.percent_charge}" data-ins="${val.withdraw_instruction}">${val.name}</option>`
                });

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

            $('.limit').text('@lang('Min') : '+min+' '+code+' --- '+ 'Max : '+max+' '+code)
            $('.charge').text('@lang('Total Charge') : '+fixed+' '+code+' + '+percent+'%')

            if(min > amount || max < amount){
                toast('error','@lang('Please follow the limit')')
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
                toast('error','@lang('Please select the withdraw method first.')')
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