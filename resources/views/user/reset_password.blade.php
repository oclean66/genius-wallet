@extends('layouts.auth')

@section('title')
    @lang('Reset Password')
@endsection


@section('content')
    <section class="account-section">
        <div class="account-wrapper auth--page login-wrapper">
            <div class="account-right">
                <div class="w-100">
                    <div class="section-title">
                        <h3 class="title">@lang('Reset Password')</h3>
                        <p>@lang('Renew your account password')</p>
                    </div>
                    <form class="row g-4" action="" method="post">
                        @csrf
                        <div class="col-sm-12 form-group">
                            <label class="form--label" for="email">@lang('Email')</label>
                            <input type="text" value="{{session('email')}}" class="form-control form--control bg--section" id="email" disabled>
                        </div>
                        <div class="col-sm-12 form-group">
                            <label class="form--label" for="email">@lang('Password')</label>
                            <input type="password" name="password" class="form-control form--control bg--section" id="email"
                                   placeholder="@lang('Password')"  required>
                        </div>
                        <div class="col-sm-12 form-group">
                            <label class="form--label" for="email">@lang('Confirm Password')</label>
                            <input type="password" name="password_confirmation" class="form-control form--control bg--section" id="email"
                                   placeholder="@lang('Confirm Password')"  required>
                        </div>

                        <div class="col-sm-12 form-group">
                            <div class="d-flex flex-wrap justify-content-between">
                                <div>
                                    <a href="{{route('user.login')}}">@lang('Take me to login page.')</a>
                                </div>

                            </div>
                        </div>
                        <div class="col-xl-12 form-group">
                            <button type="submit" class="cmn--btn">@lang('Reset Password')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
