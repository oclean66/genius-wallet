@extends('layouts.user')

@section('title')
   @lang('Invoice Payment')
@endsection

@section('content')
    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        @lang('Invoice Payment')
                    </div>
                    <form action="" method="POST">
                        @csrf
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between">@lang('Name :') <span class="font-weight-bold">{{$invoice->invoice_to}}</span> </li>
                                <li class="list-group-item d-flex justify-content-between">@lang('Email :') <span class="font-weight-bold">{{$invoice->email}}</span> </li>
                                <li class="list-group-item d-flex justify-content-between">@lang('Amount :') <span class="font-weight-bold">{{amount($invoice->final_amount,$invoice->currency->type,2).' '.$invoice->currency->code}}</span> </li>
                            </ul>
                         
                            <div class="text-center mt-3">
                                <div class="form-selectgroup">
                                    <label class="form-selectgroup-item">
                                        <input type="radio" name="payment" value="gateway" class="form-selectgroup-input" checked="">
                                        <span class="form-selectgroup-label">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-credit-card" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <rect x="3" y="5" width="18" height="14" rx="3"></rect>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                                <line x1="7" y1="15" x2="7.01" y2="15"></line>
                                                <line x1="11" y1="15" x2="13" y2="15"></line>
                                            </svg>
                                            @lang('Pay with gateways')</span>
                                    </label>
                                    <label class="form-selectgroup-item">
                                    <input type="radio" name="payment" value="wallet" class="form-selectgroup-input">
                                    <span class="form-selectgroup-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-wallet" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"></path>
                                            <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"></path>
                                        </svg>
                                        @lang('Pay with system wallet')
                                        </span>
                                    </label>
                                
                                </div>
                            </div>
    
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-block">@lang('Next') <i class="ms-2 fas fa-long-arrow-alt-right"></i></button>
                            </div>
                      
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection