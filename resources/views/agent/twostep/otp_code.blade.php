@extends('layouts.agent_auth')

@section('title')
   @lang('Two Step Authentication')
@endsection

@section('content')
   
<div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
    <div class="card card-primary logincard">
      <div class="card-header">
        <h4> @lang('Two Step Verification')</h4>
        </div>
      
      <div class="card-body">
        <form action="" method="POST">
            @csrf
            <div class="form-group">
               <h6>@lang('Please check your phone number to get OTP code')</h6>
            </div>
            <div class="form-group">
                <label>@lang('OTP Code')</label>
                <input class="form-control" type="text" name="code" required>
            </div>
            <div class="form-group d-flex justify-content-between">
                <a href="{{route('agent.two.step.resend')}}" class="text-left">@lang('Didn\'t get code? Resend.')</a>
               <button type="submit" class="btn btn-primary btn-lg">@lang('Submit')</button>
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