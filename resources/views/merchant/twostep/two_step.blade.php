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
        @if ($gs->two_fa)
            @if (merchant()->two_fa_status)
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center">
                        <h1 class="text-success display-1"><i class="far fa-check-circle fa-2x"></i></h1>
                        <h5>@lang('Your two step authentication is activated')</h5>
                        <span><a href="javascript:void(0)" data-toggle="modal" data-target="#deactivated">@lang('Deactivate Two Step Authentication')</a></span>
                    </div>
                </div>
            </div>
            @else
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>@lang('Activate Two Step Authentication')</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>@lang('Provide Your Password')</label>
                                <input class="form-control" type="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label>@lang('Confirm Password')</label>
                                <input class="form-control" type="password" name="password_confirmation" required>
                            </div>
                            <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary btn-lg">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        @else
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <h1 class="text-warning display-1"><i class="fas fa-exclamation-triangle"></i></h1>
                    <h5>@lang('Your two step authentication is temporary unavailable.')</h5>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deactivated" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Deativate Two Step Authentication')</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Provide Your Password')</label>
                            <input class="form-control" type="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Confirm Password')</label>
                            <input class="form-control" type="password" name="password_confirmation" required>
                        </div>
                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection