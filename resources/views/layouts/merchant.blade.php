<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$gs->title.'-'}}@yield('title')</title>
    <link rel="shortcut icon" href="{{getPhoto($gs->favicon)}}">
    <link rel="stylesheet" href="{{asset('assets/merchant/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/merchant/css/font-awsome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/merchant/css/selectric.css')}}">
    <link rel="stylesheet" href="{{asset('assets/merchant/css/jquery-ui.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/merchant/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/merchant/css/tagify.css') }}">
    <link rel="stylesheet" href="{{asset('assets/merchant/css/summernote.css')}}">
    <link rel="stylesheet" href="{{asset('assets/merchant/css/bootstrap-iconpicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/merchant/css/colorpicker.css')}}">
    <link rel="stylesheet" href="{{asset('assets/merchant/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/merchant/css/components.css')}}">
    <link rel="stylesheet" href="{{asset('assets/merchant/css/custom.css')}}">

    @stack('style')
</head>
<body>
  
<div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
         @include('merchant.partials.topbar')
      <div class="main-sidebar">
         @include('merchant.partials.sidebar')
      </div>

      <!-- Main Content -->
      <div class="main-content">
        @yield('breadcrumb')
        @include('merchant.partials.kyc_info')
        @yield('content')
      </div>
      
    </div>
  </div>



  
  <!-- Modal -->
  <div class="modal fade" id="cleardb" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <form action="" method="post">
      @csrf
    
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">@changeLang('Clear Database')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <div class="modal-body">
          <div class="container-fluid">
              <p>@changeLang('Are You Sure To Clear Database')</p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">@changeLang('Close')</button>
          <button type="submit" class="btn btn-danger">@changeLang('Clear Database')</button>
        </div>
      </div>
      </form>
    </div>
  </div>



    @include('notify.alert')
    <script src="{{asset('assets/merchant/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/merchant/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/merchant/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/merchant/js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets/merchant/js/nicescroll.min.js')}}"></script>
    <script src="{{asset('assets/merchant/js/summernote.js')}}"></script>
    <script src="{{asset('assets/merchant/js/select2.min.js')}}"></script>
    <script src="{{asset('assets/merchant/js/tagify.js') }}"></script>
    <script src="{{asset('assets/merchant/js/sortable.js') }}"></script>
    <script src="{{asset('assets/merchant/js/moment-a.js')}}"></script>
    <script src="{{asset('assets/merchant/js/stisla.js')}}"></script>
    <script src="{{asset('assets/merchant/js/bootstrap-iconpicker.bundle.min.js')}}"></script>
    <script src="{{asset('assets/merchant/js/colorpicker.js')}}"></script>
    <script src="{{asset('assets/merchant/js/jquery.uploadpreview.min.js')}}"></script>
    <script src="{{asset('assets/merchant/js/chart.min.js')}}"></script>
    <script src="{{asset('assets/merchant/js/scripts.js')}}"></script>
    <script src="{{asset('assets/merchant/js/custom.js')}}"></script>


    <script>

      var form_error   = "{{ __('Please fill all the required fields') }}";
      var mainurl = "{{ url('/') }}";
      var lang  = {
            'new': '{{ __('ADD NEW') }}',
            'edit': '{{ __('EDIT') }}',
            'details': '{{ __('DETAILS') }}',
            'update': '{{ __('Status Updated Successfully.') }}',
            'sss': '{{ __('Success !') }}',
            'active': '{{ __('Activated') }}',
            'deactive': '{{ __('Deactivated') }}',
            'loading': '{{ __('Please wait Data Processing...') }}',
            'submit': '{{ __('Submit') }}',
            'enter_name': '{{ __('Enter Name') }}',
            'enter_price': '{{ __('Enter Price') }}',
            'per_day': '{{ __('Per Day') }}',
            'per_month': '{{ __('Per Month') }}',
            'per_year': '{{ __('Per Year') }}',
            'one_time': '{{ __('One Time') }}',
            'enter_title': '{{ __('Enter Title') }}',
            'enter_content': '{{ __('Enter Content') }}',
            'extra_price_name' : '{{__('Enter Name')}}',
            'extra_price' : '{{__('Enter Price')}}',
            'policy_title' : '{{__('Enter Title')}}',
            'policy_content' : '{{__('Enter Content')}}',
        };
  
    </script>


    <script>
      $(function(){
        'use strict'
        $('.reason').on('click',function(){
          $('#modal-reason').find('.reason-text').val($(this).data('reason'))
          $('#modal-reason').modal('show')
        })

      })
    
    $('.summernote').summernote()
    </script>
    @stack('script')
    
</body>
</html>