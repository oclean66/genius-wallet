<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>{{__($gs->title)}} - @lang('Invoice : '.$invoice->number) </title>
    <!-- CSS files -->
    <link rel="shortcut icon" href="{{getPhoto($gs->favicon)}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/font-awsome.min.css')}}">

    <link href="{{asset('assets/user/')}}/css/tabler.min.css" rel="stylesheet"/>
    <link href="{{asset('assets/user/')}}/css/tabler-flags.min.css" rel="stylesheet"/>
    <link href="{{asset('assets/user/')}}/css/tabler-payments.min.css" rel="stylesheet"/>
    <link href="{{asset('assets/user/')}}/css/tabler-vendors.min.css" rel="stylesheet"/>
    <link href="{{asset('assets/user/')}}/css/demo.min.css" rel="stylesheet"/>
    @stack('style')
  </head>
  
  <body>
    <div class="wrapper mb-3">
          <div class="page-wrapper">
            <div class="container-xl">
              <!-- Page title -->
              <div class="page-header text-white d-print-none">
                <div class="row align-items-center">
                  <div class="col">
                    <h2 class="page-title text-dark">
                        @lang('Invoice')
                    </h2>
                  </div>
                  <!-- Page title actions -->
                  <div class="col-auto ms-auto d-print-none">
                    <a href="{{route('user.invoice.payment',encrypt($invoice->number))}}" class="btn btn-secondary">
                     <i class="fas fa-file-invoice-dollar me-2"></i>
                      @lang('Pay Invoice')
                    </a>
                    <button type="button" class="btn btn-primary ms-2" onclick="javascript:window.print();">
                      <i class="fas fa-print me-2"></i>
                      @lang('Print Invoice')
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="page-body">
                <div class="container-xl">
                    <div class="card card-lg">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-6">
                            <p class="h3">@lang('From')</p>
                            <address>
                              {{@$contact->content->address}}<br>
                              {{@$contact->content->email}}<br>
                              {{@$contact->content->phone}}<br>
                            </address>
                          </div>
                          <div class="col-6 text-end">
                            <p class="h3">@lang('To')</p>
                            <address>
                                {{$invoice->address}}<br>
                                {{$invoice->email}} 
                            </address>
                          </div>
                          <div class="col-12 my-5">
                            <h1>@lang('Invoice : ') {{$invoice->number}}</h1>
                          </div>
                        </div>
                        <table class="table table-transparent table-responsive">
                          <thead>
                            <tr>
                              <th class="text-center" style="width: 1%">@lang('SL')</th>
                              <th>@lang('Item')</th>
                              <th class="text-end" style="width: 1%">@lang('Amount')</th>
                            </tr>
                          </thead>
                          @foreach ($invoice->items as $k => $value)
                          <tr>
                            <td class="text-center">{{++$k}}</td>
                            <td>
                              <p class="strong mb-1">{{ $value->name}}</p>
                            </td>
                            <td class="text-end">{{$invoice->currency->symbol}}{{ numFormat($value->amount) }}</td>
                          </tr>
                          @endforeach
                         <tr>
                             
                             <td colspan="12" class="text-end fw-bold">@lang('Total : '.$invoice->currency->symbol.numFormat($invoice->final_amount))</td>
                         </tr>
                        </table>
                        <p class="text-muted text-center mt-5">@lang('Thank you very much for doing business with us. We look forward to working with
                            you again!')<br> <small class="mt-5">@lang('All right reserved ') <a href="{{url('/')}}">{{$gs->title}}</a></small></p>
                      </div>
                    </div>
                </div>
            </div>
          </div>
      </div>
      <script src="{{asset('assets/admin/js/jquery.min.js')}}"></script>
      <!-- Tabler Core -->
      <script src="{{asset('assets/user/')}}/js/tabler.min.js"></script>
      <script src="{{asset('assets/user/')}}/js/demo.min.js"></script>
 
      @include('notify.alert')
      @stack('script')

</body>
</html>