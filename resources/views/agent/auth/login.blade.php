@extends('layouts.agent_auth')
@section('title')
    @lang('Agent Login')
@endsection
@section('content')
   
<div class="col-lg-6 col-xl-6">
    <div class="card card-primary logincard">
      <div class="card-header d-flex justify-content-between">
          <h4>@lang('Agent Login')</h4>
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
                    <a href="{{route('agent.forgot.password')}}" class="float-left mt-3">
                        @lang('Forgot Password')?
                    </a>
                    <button type="submit" class="btn btn-primary btn-icon icon-right" tabindex="4">
                        @lang('Login')
                    </button>
                </div>
                <div class="form-group text-center">
                    <a href="{{route('agent.register')}}" class="float-left mt-3">
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
