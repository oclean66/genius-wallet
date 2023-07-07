@extends('layouts.user')

@section('title')
    @lang('Request Money')
@endsection

@section('breadcrumb')
   @lang('Request Money')
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
                            <div class="form-label">@lang('Recipient Email') <span class="ms-2 check"></span></div>
                            <div class="input-group">
                                <input type="text" name="receiver" id="receiver" class="form-control shadow-none receiver" required>
                                <button type="button"  title="Scan QR code" class="input-group-text scan"><i class="fas fa-qrcode"></i></button>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-label">@lang('Select Wallet')</div>
                            <select class="form-select wallet shadow-none" name="wallet_id">
                                <option value="" selected>@lang('Select')</option>
                                @foreach ($wallets as $wallet)
                                <option value="{{$wallet->id}}" data-rate="{{$wallet->currency->rate}}" data-code="{{$wallet->currency->code}}" data-type="{{$wallet->currency->type}}">{{$wallet->currency->code}} -- ({{amount($wallet->balance,$wallet->currency->type,3)}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-label ">@lang('Amount : ') <code class="limit">@lang('min : '.$charge->minimum.' '.$gs->curr_code) -- @lang('max : '.$charge->maximum.' '.$gs->curr_code)</code> </div>
                            <input type="text" name="amount" id="amount" class="form-control shadow-none mb-2" disabled required>
                            <small class="text-danger charge"></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-label">&nbsp;</div>
                            <a href="#" class="btn btn-primary w-100 transfer">
                                @lang('Request')
                            </a>
                        </div>


                        <div class="modal modal-blur fade" id="modal-success" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                <div class="modal-status bg-primary"></div>
                                <div class="modal-body text-center py-4">
                                <i  class="fas fa-info-circle fa-3x text-primary mb-2"></i>
                                <h3>@lang('Preview Request')</h3>
                                <ul class="list-group mt-2">
                                    <li class="list-group-item d-flex justify-content-between">@lang('Request Amount')<span class="amount"></span></li>
                                    <li class="list-group-item d-flex justify-content-between">@lang('Total Charge')<span class="charge"></span></li>
                                    <li class="list-group-item d-flex justify-content-between">@lang('You will receive')<span class="total_amount"></span></li>
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
        <div class="col-md-12">
            <h2> @lang('Recent Requests')</h2>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                  <table class="table table-vcenter card-table table-striped">
                    <thead>
                      <tr>
                        <th>@lang('Recipient')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Charge')</th>
                        <th>@lang('You will get')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Date')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($recentRequests as $item)
                        <tr>
                          <td>{{$item->receiver->email}}</td>
                          <td>{{numFormat($item->request_amount)}} {{$item->currency->code}}</td>
                          <td>{{numFormat($item->charge)}} {{$item->currency->code}}</td>
                          <td>{{numFormat($item->final_amount)}} {{$item->currency->code}}</td>
                          <td>
                              @if ($item->status == 0)
                                  <span class="badge bg-warning">@lang('pending')</span>
                              @elseif ($item->status == 1)
                                   <span class="badge bg-success">@lang('accepted')</span>
                              @elseif ($item->status == 2)
                                   <span class="badge bg-danger">@lang('rejected')</span>
                              @endif
                          </td>
                          <td>{{dateFormat($item->created_at)}}</td>
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
        $('.receiver').on('focusout',function () { 
            var url = '{{ route('user.check.agent') }}';
            var value = $(this).val();
            var token = '{{ csrf_token() }}';
            var data = {receiver:value,_token:token}
           
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
                    $('.check').text(`@lang('Valid agent found.')`).addClass('text-success');
                    $('.transfer').attr('disabled',false)
                } else {
                    if($('.check').hasClass('text-success')){
                        $('.check').removeClass('text-success');
                    }
                    $('.check').text('@lang('Agent not found.')').addClass('text-danger');
                    
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
            $('#modal-success').find('.charge').text(charge().final +' '+ selected.data('code'))
            $('#modal-success').find('.total_amount').text((amount - charge().final).toFixed(3) +' '+selected.data('code'))
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
            var selected = $('.wallet option:selected')
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
            $('.charge').text("@lang('Total Charge') : " +amount(charge().fixed,type) +' '+code+' + '+charge().percent + '%')
        })
    </script>
@endpush