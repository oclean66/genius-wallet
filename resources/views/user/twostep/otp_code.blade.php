@extends('layouts.auth')

@section('title')
   @lang('Two Step Authentication')
@endsection

@section('content')
   
<div class="card card-primary logincard">
    <div class="card-header">
    <h4> @lang('Two Step Verification')</h4>
    </div>
    
    <div class="card-body">
    <form action="" method="POST">
        @csrf
        <div class="form-group">
            <h4>@lang('Please check your phone number to get OTP code')</h4>
        </div>
        <div class="form-group mb-2">
            <label class="mb-1">@lang('OTP Code')</label>
            <input class="form-control" type="text" name="code" required>
        </div>
        <div class="form-group d-flex justify-content-between">
            <a href="{{route('user.two.step.resend')}}" class="text-left">@lang('Didn\'t get code? Resend.')</a>
            <button type="submit" class="btn btn-primary">@lang('Submit')</button>
        </div>
    </form>
    </div>
</div>
   


@endsection
