@extends('layouts.merchant')

@section('title')
   @lang('Two Step Authentication')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header">
        <h1>@lang('Two Step Authentication')</h1>
    </div>
</section>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @if (merchant()->two_fa_status == 1)
                      <h4>@lang('Deactivate Two Step Authentication')</h4>
                    @else
                      <h4>@lang('Activate Two Step Authentication')</h4>
                    @endif
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="form-group">
                           <h6>@lang('Please check your phone number to get OTP code. Your phone number is : '.merchant()->phone)</h6>
                        </div>
                        <div class="form-group">
                            <label>@lang('OTP Code')</label>
                            <input class="form-control" type="text" name="code" required>
                        </div>
                        <div class="form-group d-flex justify-content-between">
                            <a href="{{route('merchant.two.step.resend')}}" class="text-left">@lang('Didn\'t get code? Resend.')</a>
                           <button type="submit" class="btn btn-primary btn-lg">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection