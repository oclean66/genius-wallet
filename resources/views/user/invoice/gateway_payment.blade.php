@extends('layouts.user')

@section('title')
   @lang('Invoice Payment')
@endsection

@section('breadcrumb')
    @lang('Invoice Payment')
@endsection

@section('content')
    <div class="container-xl">
        <div class="row">
            <div class="col-md-12">
                <form action="{{route('user.deposit.submit')}}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">@lang('Payment methods')</label>
                                <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column">
                                    @foreach ($methods as $item)
                                    <label class="form-selectgroup-item flex-fill">
                                      <input type="radio" name="gateway" value="{{$item->id}}" class="form-selectgroup-input">
                                      <div class="form-selectgroup-label d-flex align-items-center p-3">
                                        <div class="me-3">
                                          <span class="form-selectgroup-check"></span>
                                        </div>
                                        <div class="font-weight-bold">
                                            {{$item->name}}
                                        </div>
                                      </div>
                                    </label>
                                    @endforeach
                                </div>
    
                                <input type="hidden" name="curr_code" value="{{$invoice->currency->code}}">
                                 <input type="hidden" name="amount" value="{{$invoice->final_amount}}">
                                <div class="mt-3 text-end">
                                    <button type="button" class="btn btn-primary pay"><i class="fas fa-money-check-alt me-2"></i> @lang('Pay')</button>
                                </div>

                                <div class="modal modal-blur fade" id="modal-success" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        <div class="modal-status bg-primary"></div>
                                        <div class="modal-body text-center py-4">
                                        <i  class="fas fa-info-circle fa-3x text-primary mb-2"></i>
                                        <h3>@lang('Are You Confirm ?')</h3>
                                        
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
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict';
        $('.pay').on('click',function () { 
            if(!$('input[name=gateway]:checked').val()){
                toast('error','@lang('Please select the payment method first.')')
                return false
            }
             $('#modal-success').modal('show')
        })
    </script>
@endpush