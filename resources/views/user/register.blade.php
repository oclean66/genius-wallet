@extends('layouts.auth')

@section('title')
   @lang('User Register')
@endsection


@section('content')
<section class="account-section register">
    <div class="account-wrapper auth--page ">

        <div class="account-right">
            <div class="w-100">
                <div class="section-title text-center">
                    <a href="{{url('/')}}" class="logo">
                        <img src="{{getPhoto($gs->header_logo)}}" alt="logo">
                    </a>
                    <h4 class="title mt-3 mb-0">@lang('Create an Account')</h4>

                </div>
                <form class="row g-4" action="" method="POST" onsubmit="return recaptcha();">
                    @csrf
                    <div class="col-sm-6 form-group">
                        <label class="form--label">@lang('Name')</label>
                        <input type="text" class="form-control form--control bg--section" name="name" placeholder="@lang('Enter name')" required value="{{old('name')}}">
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="form--label">@lang('Email address')</label>
                        <input type="email" class="form-control form--control bg--section" name="email" placeholder="@lang('Enter email')" required value="{{old('email')}}">
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="form--label">@lang('Country')</label>
                        <select name="country" class="form-control form--control bg--section country" required>
                            <option value="">@lang('Select')</option>
                            @foreach ($countries as $item)
                                <option value="{{$item->name}}" data-dial_code="{{$item->dial_code}}" {{@$info->geoplugin_countryCode == $item->code ? 'selected':''}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-6 form-group">
                         <label class="form--label">@lang('Phone No.')</label>
                        <input type="hidden" name="dial_code">
                        <div class="input-group">
                            <span class="input-group-text d_code"></span>
                            <input type="text" name="phone"  class="form-control form--control bg--section" placeholder="@lang('Phone No.')" required value="{{old('phone')}}">
                        </div>
                    </div>

                    <div class="col-sm-12 form-group">
                        <label class="form--label">@lang('Address')</label>
                        <input type="text" name="address" value="{{old('address')}}" class="form-control form--control bg--section" placeholder="@lang('Address')" required>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="form--label">@lang('Password')</label>
                        <div class="input-group input-group-flat">
                            <input type="password" name="password" class="form-control form--control bg--section"  placeholder="@lang('Password')"  autocomplete="off" required>
                        </div>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="form--label">@lang('Confirm Password')</label>
                        <div class="input-group input-group-flat">
                            <input type="password" name="password_confirmation" class="form-control form--control bg--section"  placeholder="@lang('Confirm Password')"  autocomplete="off">
                        </div>
                    </div>
                    @if ($gs->recaptcha)
                        <div class="col-sm-12 form-group">
                          
                            {!! NoCaptcha::display() !!}
                            {!! NoCaptcha::renderJs() !!}
                            @error('g-recaptcha-response')
                            <p class="my-2 text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    @endif
                    <div class="col-xl-12 form-group">
                        <div class="d-flex flex-wrap justify-content-between">

                            <div>
                                <a href="{{route('user.login')}}">@lang('Already have an Account ?')</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 form-group">
                        <button type="submit" class="cmn--btn">@lang('Create Account')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script')

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

    
    </script>
@endpush
