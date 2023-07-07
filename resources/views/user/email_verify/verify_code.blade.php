@extends('layouts.auth')

@section('title')
   @lang('Verify Email')
@endsection

@section('content')
    <section class="account-section">
        <div class="account-wrapper auth--page login-wrapper">
            <div class="account-right">
                <div class="w-100">
                    <div class="section-title">
                        <h3 class="title">@lang('Email Verify Code')</h3>
                        <p>@lang('Enter the verification code that has been sent to your email.')</p>
                    </div>
                    <form class="row g-4" action="" method="post">
                        @csrf
                        <div class="col-sm-12 form-group">
                            <label class="form--label" for="email">@lang('Verify Code')</label>
                            <input type="text" name="code" class="form-control form--control bg--section" id="email" placeholder="@lang('Password Reset Code')"  required>
                        </div>

                        <div class="col-sm-12 form-group">
                            <div class="d-flex flex-wrap justify-content-between">
                                <div>
                                    <a href="{{route('user.verify.email.resend')}}">@lang('Resend Code')</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 form-group">
                            <button type="submit" class="cmn--btn">@lang('Verify Code')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
