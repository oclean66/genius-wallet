@extends('layouts.merchant_auth')

@section('title')
    @lang('Merchant Email Verification')
@endsection

@section('content')
   
<div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
    <div class="card card-primary logincard">
      <div class="card-header d-flex justify-content-between">
          <h4>@lang('Email Verification Code')</h4>
          <a href="{{url('/')}}">@lang('Home')</a>
        </div>
      
      <div class="card-body">
          @if(session()->has('error'))
            <div class="my-2 text-center creds  p-2">
              <span class="text-danger  mt-2">{{ session('error') }}</span>
            </div>
         @endif
        <form method="POST" action="{{route('merchant.verify.email')}}" class="needs-validation">
            @csrf
                <div class="form-group">
                    <label for="email">@lang('Verification Code')</label>
                    <input id="email" type="text" class="form-control  @error('code') is-invalid  @enderror" name="code" tabindex="1" required>
                    @error('code')
                     <span class="text-danger mt-2">{{ __($message) }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <a href="{{route('merchant.verify.email.resend')}}">@lang('Resend Code')</a>
                </div>
    
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary btn-lg btn-icon icon-right" tabindex="4">
                        @lang('Submit')
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
            margin-top: 250px !important;
            border-radius: 3px
        }
    </style>
@endpush