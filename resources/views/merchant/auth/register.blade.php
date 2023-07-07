@extends('layouts.merchant_auth')
@section('title')
@lang('Merchant Register')
@endsection
@section('content')
<div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
    <div class="card card-primary logincard">
      <div class="card-header d-flex justify-content-between">
          <h4>@lang('Merchant Register')</h4>
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
                    <label>@lang('Business Name')</label>
                    <input  type="text" class="form-control  @error('business_name') is-invalid  @enderror" name="business_name" tabindex="1" required value="{{old('business_name')}}">
                    @error('business_name')
                     <span class="text-danger mt-2">{{ __($message) }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>@lang('Your Name')</label>
                    <input type="text" class="form-control  @error('name') is-invalid  @enderror" name="name" tabindex="1" required value="{{old('name')}}">
                    @error('name')
                     <span class="text-danger mt-2">{{ __($message) }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">@lang('Email')</label>
                    <input id="email" type="email" class="form-control  @error('email') is-invalid  @enderror" name="email" tabindex="1" value="{{old('email')}}" required>
                    @error('email')
                     <span class="text-danger mt-2">{{ __($message) }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>@lang('Country')</label>
                    <select name="country" class="form-control country" required>
                        <option value="">@lang('Select')</option>
                        @foreach ($countries as $item)
                         <option value="{{$item->name}}" data-dial_code="{{$item->dial_code}}" {{$info->geoplugin_countryCode == $item->code ? 'selected':''}}>{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                   <label>@lang('Phone')</label>
                    <div class="input-group">
                        <input type="hidden" name="dial_code">
                        <div class="input-group-prepend">
                            <span class="input-group-text d_code"></span>
                        </div>
                        <input  type="phone" class="form-control @error('phone') is-invalid  @enderror" name="phone" tabindex="2">
                        @error('phone')
                         <span class="text-danger mt-2">{{ $message }}</span>
                        @enderror   
                    </div>
               </div>
               <div class="form-group">
                   <label>@lang('Address')</label>
                    <input type="text" class="form-control  @error('address') is-invalid  @enderror" name="address" tabindex="1" value="{{old('address')}}" required>
                    @error('address')
                    <span class="text-danger mt-2">{{ $message }}</span>
                    @enderror
                </div>
 
                <div class="form-group">
                    <label for="password" class="control-label">@lang('Password')</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid  @enderror" name="password" tabindex="2">  
                    @error('password')
                        <span class="text-danger mt-2">{{ $message }}</span>
                    @enderror    
                </div>
                <div class="form-group">
                    <label for="password" class="control-label">@lang('Confirm Password')</label>
                    <input id="password" type="password" class="form-control @error('password_confirmation') is-invalid  @enderror" name="password_confirmation" tabindex="2">  
                    @error('password_confirmation')
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
                <a href="{{route('merchant.login')}}" class="float-left mt-3">
                    @lang('Already have an account ? Login')
                </a>
                <button type="submit" class="btn btn-primary btn-lg btn-icon icon-right" tabindex="4">
                    @lang('Register')
                </button>
                </div>
            </form>
      </div>
    </div>
   
  </div>

@endsection

@push('style')
    <style>
        .logincard{
            margin-top: 60px !important;
            border-radius: 3px
        }
    </style>
@endpush


@push('script')
<script src="https://www.google.com/recaptcha/api.js"></script>
    <script>
      'use strict';
     function auto() { 
        var code = $('.country option:selected').data('dial_code')
        $('.d_code').text(code)
        $('input[name=dial_code]').val(code)
      }
      auto();
      $('.country').on('change',function () { 
        auto();
      })

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

