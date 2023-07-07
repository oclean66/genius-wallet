@extends('layouts.agent_auth')
@section('title')
@lang('Agent Register')
@endsection
@section('content')
<div class="col-lg-10 col-xl-8">
    <div class="card card-primary logincard">
      <div class="card-header d-flex justify-content-between">
          <h4>@lang('Agent Register')</h4>
          <a href="{{url('/')}}">@lang('Home')</a>
        </div>
      
      <div class="card-body">
          @if(session()->has('error'))
            <div class="my-2 text-center creds  p-2">
              <span class="text-danger  mt-2">{{ session('error') }}</span>
            </div>
         @endif
        <form method="POST" action="" class="needs-validation" enctype="multipart/form-data">
            @csrf
                <div class="form-group">
                    <label>@lang('Name of Business Institution')</label>
                    <input  type="text" class="form-control  @error('business_name') is-invalid  @enderror" name="business_name" tabindex="1" required value="{{old('business_name')}}">
                    @error('business_name')
                     <span class="text-danger mt-2">{{ __($message) }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>@lang('Address of Business Institution')</label>
                    <input  type="text" class="form-control  @error('business_address') is-invalid  @enderror" name="business_address" tabindex="1" required value="{{old('business_address')}}">
                    @error('business_address')
                     <span class="text-danger mt-2">{{ __($message) }}</span>
                    @enderror
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>@lang('National ID Number')</label>
                        <input  type="text" class="form-control  @error('nid') is-invalid  @enderror" name="nid" tabindex="1" required value="{{old('nid')}}">
                        @error('nid')
                         <span class="text-danger mt-2">{{ __($message) }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>@lang('Nation ID Photo')</label>
                        <input  type="file" class="form-control  @error('nid_photo') is-invalid  @enderror" name="nid_photo" tabindex="1" required value="{{old('nid_photo')}}">
                        @error('nid_photo')
                         <span class="text-danger mt-2">{{ __($message) }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>@lang('Your Name')</label>
                        <input type="text" class="form-control  @error('name') is-invalid  @enderror" name="name" tabindex="1" required value="{{old('name')}}">
                        @error('name')
                         <span class="text-danger mt-2">{{ __($message) }}</span>
                        @enderror
                    </div>
    
                    <div class="form-group col-md-6">
                        <label for="email">@lang('Email')</label>
                        <input id="email" type="email" class="form-control  @error('email') is-invalid  @enderror" name="email" tabindex="1" value="{{old('email')}}" required>
                        @error('email')
                         <span class="text-danger mt-2">{{ __($message) }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>@lang('Country')</label>
                        <select name="country" class="form-control country" required>
                            <option value="">@lang('Select')</option>
                            @foreach ($countries as $item)
                             <option value="{{$item->name}}" data-dial_code="{{$item->dial_code}}" {{@$info->geoplugin_countryCode == $item->code ? 'selected':''}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
    
                    <div class="form-group col-md-6">
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
                </div>
               <div class="form-group">
                   <label>@lang('Your Address')</label>
                    <input type="text" class="form-control  @error('address') is-invalid  @enderror" name="address" tabindex="1" value="{{old('address')}}" required>
                    @error('address')
                    <span class="text-danger mt-2">{{ $message }}</span>
                    @enderror
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="password" class="control-label">@lang('Password')</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid  @enderror" name="password" tabindex="2">  
                        @error('password')
                            <span class="text-danger mt-2">{{ $message }}</span>
                        @enderror    
                    </div>
                    <div class="form-group col-md-6">
                        <label for="password" class="control-label">@lang('Confirm Password')</label>
                        <input id="password" type="password" class="form-control @error('password_confirmation') is-invalid  @enderror" name="password_confirmation" tabindex="2">  
                        @error('password_confirmation')
                            <span class="text-danger mt-2">{{ $message }}</span>
                        @enderror    
                    </div>
                </div>
                   @if($gs->recaptcha)
                    <div class="col-sm-12 form-group">
                        {!! NoCaptcha::display() !!}
                        {!! NoCaptcha::renderJs() !!}
                        @error('g-recaptcha-response')
                        <p class="my-2 text-danger">{{$message}}</p>
                        @enderror
                    </div>
                    @endif
    
                <div class="form-group text-right">
                <a href="{{route('agent.login')}}" class="float-left mt-3">
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
        .form-control{
            line-height: 1.2!important
        }
    </style>
@endpush


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

