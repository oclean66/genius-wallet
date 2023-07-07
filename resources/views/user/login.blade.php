@extends('layouts.auth')

@section('title')
   @lang('User Login')
@endsection


@section('content')

<section class="account-section overflow-hidden">
    <div class="account-wrapper auth--page login-wrapper">

        <div class="account-right">
            <div class="w-100">
                <div class="section-title text-center">
                    <a href="{{url('/')}}" class="logo">
                      <img src="{{getPhoto($gs->header_logo)}}" alt="logo">
                    </a>
                    <h4 class="title mt-3 mb-0">@lang('User Login')</h4>
                </div>
                <form class="row g-4" action="{{route('user.login')}}" method="post" onsubmit="return recaptcha();">
                    @csrf
                    <div class="col-sm-12 form-group">
                        <label class="form--label" for="email">@lang('Email')</label>
                        <input type="text" name="email" class="form-control form--control bg--section" id="email"
                               placeholder="@lang('Email Address')" value="{{old('email')}}" required>
                    </div>
                    <div class="col-sm-12 form-group">
                        <label class="form--label" for="password">@lang('Password')</label>
                        <input type="password" name="password" class="form-control form--control bg--section" id="password"
                               placeholder="@lang('Password')" required>
                    </div>

                    @if ($gs->recaptcha)
                        <div class="col-sm-12 form-group mb-3">
                            <div class="g-recaptcha" data-sitekey="{{$gs->recaptcha_key}}" data-callback="verifyCaptcha"></div>
                            <div id="g-recaptcha-error"></div>
                        </div>
                    @endif

                    <div class="col-sm-12 form-group">
                        <div class="d-flex flex-wrap justify-content-between">
                            <div>
                                <a href="{{route('user.register')}}">@lang('Don\'t have any Account ?')</a>
                            </div>
                            <div>
                                <a href="{{route('user.forgot.password')}}" class="text--base">@lang('Forget Password ?')</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 form-group">
                        <button type="submit" class="cmn--btn">@lang('Login')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>
@endsection


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
