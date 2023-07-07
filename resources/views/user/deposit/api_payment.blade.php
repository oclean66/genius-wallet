
<html lang="en">
    <head>
      <meta charset="utf-8"/>
      <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
      <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
      <title>{{__($gs->title)}}-@yield('title')</title>
      <!-- CSS files -->
      <link rel="shortcut icon" href="{{getPhoto($gs->favicon)}}">
      <link rel="stylesheet" href="{{asset('assets/admin/css/font-awsome.min.css')}}">
  
      <link href="{{asset('assets/user/')}}/css/tabler.min.css" rel="stylesheet"/>
      <link href="{{asset('assets/user/')}}/css/tabler-flags.min.css" rel="stylesheet"/>
      <link href="{{asset('assets/user/')}}/css/tabler-payments.min.css" rel="stylesheet"/>
      <link href="{{asset('assets/user/')}}/css/tabler-vendors.min.css" rel="stylesheet"/>
      <link href="{{asset('assets/user/')}}/css/demo.min.css" rel="stylesheet"/>
      <link href="{{asset('assets/user/')}}/css/custom.css" rel="stylesheet"/>
      @stack('style')
   
    </head>
    
    <body>
      <div class="wrapper">
    
            <div class="page-wrapper">
              <div class="container-xl">
                <!-- Page title -->
                <div class="page-header text-white d-print-none">
                  <div class="row align-items-center">
                 
                   
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                      <div class="btn-list">
                          
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="page-body">
                <div class="container-xl">
                    <div class="row row-deck row-cards mb-5">
                        <div class="col-12">
                            <div class="card">
                            <div class="card-body">
                                <form action="{{route('user.deposit.submit')}}" id="form" method="post">
                                  @csrf
                                    <div class="row">
                                       
                                        <div class="col-md-12">
                                            <ul class="list-group mt-2">
                                                <li class="list-group-item d-flex justify-content-between font-weight-bold">@lang('Requested Amount : ')<span class="exAmount"> {{round($payment->amount)}} {{$currency->code}}</span></li>
                
                                                <li class="list-group-item d-flex justify-content-between font-weight-bold">@lang('Currency : ')<span class="">{{$currency->code}}</span></li>
                
                                                <li class="list-group-item d-flex justify-content-between font-weight-bold">@lang('Gateway : ')<span class="">{{$gateway->name}}</span></li>
                                                
                                                <li class="list-group-item d-flex justify-content-between font-weight-bold">@lang('Charge : ')<span class="">{{$charge}} {{$currency->code}}</span></li>
                                       
                
                                                <li class="list-group-item d-flex justify-content-between font-weight-bold">@lang('Total Amount : ')<span class="">{{$totalAmount}} {{$currency->code}}</span></li>
                                            </ul>
                                        </div>
                                       
                                        <input type="hidden" name="curr_code" value="{{$currency->code}}">
                                        <input type="hidden" name="amount" value="{{round($payment->amount)}}">
                                        <input type="hidden" name="gateway" value="{{$gateway->id}}">
                                       
                                      
                
                                       
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
                </div>
              </div>
            </div>
        </div>
  
  
        <script src="{{asset('assets/admin/js/jquery.min.js')}}"></script>
        <script src="{{asset('assets/user/')}}/libs/apexcharts/dist/apexcharts.min.js"></script>
        <!-- Tabler Core -->
        <script src="{{asset('assets/user/')}}/js/tabler.min.js"></script>
        <script src="{{asset('assets/user/')}}/js/demo.min.js"></script>
  
        <script>
            'use strict';
            
             $('.confirm').on('click',function () { 
                 var selectedMethod = $('.method option:selected')
                 var selectedWallet = $('.wallet option:selected')
                 $('#modal-success').modal('show')
             })
     
        </script>
     
  
    
     
        @include('notify.alert')

  
  </body>
  </html>
  







  