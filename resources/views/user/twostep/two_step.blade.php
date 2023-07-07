@extends('layouts.user')

@section('title')
   @lang('Two Step Authentication')
@endsection

@section('breadcrumb')

    @lang('Two Step Authentication')

@endsection

@section('content')
<div class="container-xl">
    <div class="row justify-content-center">
        @if ($gs->two_fa)
              @if (auth()->user()->two_fa_status)
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <h1 class="text-success display-1"><i class="far fa-check-circle"></i></h1>
                            <h1>@lang('Your two step authentication is activated')</h1>
                            <span><a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#deactivated">@lang('Deactivate Two Step Authentication')</a></span>
                        </div>
                    </div>
                </div>
                @else
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>@lang('Activate Two Step Authentication')</h4>
                        </div>
                        <div class="card-body">
                            <form action="" method="POST">
                                @csrf
                                <div class="form-group mb-2">
                                    <label class="mb-1">@lang('Provide Your Password')</label>
                                    <input class="form-control" type="password" name="password" required>
                                </div>
                                <div class="form-group  mb-3">
                                    <label class="mb-1">@lang('Confirm Password')</label>
                                    <input class="form-control" type="password" name="password_confirmation" required>
                                </div>
                                <div class="form-group text-end">
                                <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @else
        <div class="col-md-12">
            <div class="card">
                <div class="card-body text-center">
                    <h1 class="text-warning display-2"><i class="fas fa-exclamation-triangle"></i></h1>
                    <h2>@lang('Two step authentication is temporary unavailable.')</h2>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

    <!-- Modal -->
    <div class="modal fade" id="deactivated" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Deativate Two Step Authentication')</h5>
                           
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label class="mb-1">@lang('Provide Your Password')</label>
                            <input class="form-control" type="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label class="mb-1">@lang('Confirm Password')</label>
                            <input class="form-control" type="password" name="password_confirmation" required>
                        </div>
                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection