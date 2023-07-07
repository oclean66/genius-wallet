@extends('layouts.merchant_auth')
@section('title')
    @lang('Merchant Login')
@endsection
@section('content')
   
<div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
    <div class="card card-primary logincard">
      <div class="card-header d-flex justify-content-between">
          <h4>@lang('Merchant Login')</h4>
          <a href="{{url('/')}}">@lang('Home')</a>
        </div>
      
      <div class="card-body">
          @if(session()->has('error'))
            <div class="my-2 text-center creds  p-2">
              <span class="text-danger  mt-2">{{ session('error') }}</span>
            </div>
         @endif
        <form method="POST" action="" class="needs-validation">
            @csrf
                <div class="form-group">
                    <label for="email">@lang('Email')</label>
                    <input id="email" type="email" class="form-control  @error('email') is-invalid  @enderror" name="email" tabindex="1" required>
                    @error('email')
                     <span class="text-danger mt-2">{{ $message }}</span>
                    @enderror
                </div>
    
                <div class="form-group">
                    <label for="password" class="control-label">@lang('Password')</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid  @enderror " name="password" tabindex="2">  
                    @error('password')
                        <span class="text-danger mt-2">{{ $message }}</span>
                    @enderror    
                </div>

                @if ($gs->recaptcha)
                <div class="form-group">
                    <div class="g-recaptcha" data-sitekey="{{$gs->recaptcha_key}}" data-callback="verifyCaptcha"></div>
                    <div id="g-recaptcha-error"></div>
                </div>
                @endif
    
                <div class="form-group text-right">
                <a href="{{route('merchant.forgot.password')}}" class="float-left mt-3">
                    @lang('Forgot Password')?
                </a>
                <button type="submit" class="btn btn-primary btn-lg btn-icon icon-right" tabindex="4">
                    @lang('Login')
                </button>
                </div>
                <div class="form-group text-center">
                    <a href="{{route('merchant.register')}}" class="float-left mt-3">
                        @lang('Don\'t have an account?')
                    </a>
                </div>
            </form>
      </div>
    </div>
   
  </div>

@endsection

@push('style')
    <style>
        .logincard{
            margin-top: 250px !important;
            border-radius: 3px
        }
    </style>
@endpush

@push('script')
<script src="https://www.google.com/recaptcha/api.js"></script>
    <script>
        'use strict';
        function recaptcha() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML = '<span class="text-danger">@lang("Captcha field is required.")</span>';
                return false;
            }
            return true;
        }
    </script>
@endpush