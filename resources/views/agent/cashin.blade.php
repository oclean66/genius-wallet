@extends('layouts.agent')

@section('title')
@lang('User Cash In')
@endsection

@section('breadcrumb')
   <section class="section">
    <div class="section-header">
        <h1> @lang('User Cash In')</h1>
    </div>
</section>
@endsection

@section('content')

    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card">
            <div class="card-body">
                <form action="" id="form" method="post">
                  @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-label">@lang('Receiver Email') <span class="ms-2 check"></span></div>
                            <div class="input-group">
                                <input type="text" name="receiver" id="receiver" class="form-control shadow-none receiver" required>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-label">@lang('Select Wallet')</div>
                             <select class="form-control wallet shadow-none" name="wallet_id">
                                <option value="" selected>@lang('Select')</option>
                                @foreach ($wallets as $wallet)
                                  <option value="{{$wallet->id}}" data-rate="{{$wallet->currency->rate}}" data-code="{{$wallet->currency->code}}" data-type="{{$wallet->currency->type}}">{{$wallet->currency->code}} -- ({{amount($wallet->balance,$wallet->currency->type,3)}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="form-label ">@lang('Amount : ') <code class="limit">@lang('min : '.$charge->minimum.' '.$gs->curr_code) -- @lang('max : '.$charge->maximum.' '.$gs->curr_code)</code> </div>
                            <input type="text" name="amount" id="amount" class="form-control shadow-none mb-2" disabled required>
                            <small class="text-danger charge"></small>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-label">&nbsp;</div>
                            <a href="#" class="btn btn-primary w-100 transfer">
                                @lang('Confirm')
                            </a>
                        </div>


                        <div class="modal fade" id="modal-success" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body text-center py-4">
                                <h3>@lang('Preview Cash In')</h3>
                                <ul class="list-group mt-2">
                                    <li class="list-group-item d-flex justify-content-between">@lang('Cash In Amount')<span class="amount"></span></li>
                                </ul>
                                </div>
                                <div class="modal-footer">
                                <div class="w-100">
                                    <div class="row">
                                    <div class="col"><a href="javascript:void(0)" class="btn btn-dark w-100" data-dismiss="modal">
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

   
@endsection

@push('script')
    <script>
        'use strict';
        $('.receiver').on('focusout',function () { 
            var url   = '{{ route('agent.user.check.receiver') }}';
            var value = $(this).val();
            var token = '{{ csrf_token() }}';
            var data  = {receiver:value,_token:token}
           
            $.post(url,data,function(res) {
                if(res.self){
                    if($('.check').hasClass('text-success')){
                        $('.check').removeClass('text-success');
                    }
                    $('.check').addClass('text-danger').text(res.self);
                    $('.transfer').attr('disabled',true)
                    return false
                }
                if(res['data'] != null){
                    if($('.check').hasClass('text-danger')){
                        $('.check').removeClass('text-danger');
                    }
                    $('.check').text(`@lang('Valid receiver found.')`).addClass('text-success');
                    $('.transfer').attr('disabled',false)
                } else {
                    if($('.check').hasClass('text-success')){
                        $('.check').removeClass('text-success');
                    }
                    $('.check').text('@lang('Receiver not found.')').addClass('text-danger');
                    
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
            if($('#amount').val() < limit().minLimit || $('#amount').val() > limit().maxLimit){
                toast('error','@lang('Please follow the limit.')')
                return false;
            }
           
            $('#modal-success').find('.amount').text(amount +' '+selected.data('code'))
            $('#modal-success').find('.total_amount').text((amount).toFixed(3) +' '+selected.data('code'))
            $('#modal-success').modal('show')
        })

        $('.confirm').on('click',function () { 
            $('#form').submit()
            $(this).attr('disabled',true)
        })

        function limit() { 
            var selected = $('.wallet option:selected')
            var rate = selected.data('rate')

            var minimum = '{{$charge->minimum}}'
            var maximum = '{{$charge->maximum}}'

            var minLimit = minimum * rate;
            var maxLimit = maximum * rate;

            return {'minLimit': minLimit,'maxLimit': maxLimit};
        }

      

        $('.wallet').on('change',function () { 
            var selected = $('.wallet option:selected');
           if(selected.val() == ''){
            $('#amount').attr('disabled',true)
              return false
            } else{
              $('#amount').attr('disabled',false)
            }

            var type = selected.data('type')
            var code = $('.wallet option:selected').data('code')
            var minLimit = limit().minLimit;
            var maxLimit = limit().maxLimit;
            $('.limit').text("@lang('min') : "+amount(minLimit,type)+' '+code+" -- @lang('max') : "+amount(maxLimit,type)+' '+code)
           
        })
    </script>
@endpush