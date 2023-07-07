@extends('layouts.user')

@section('title')
   @lang('Module Off')
@endsection

@section('breadcrumb')
    @lang('Module Off')
@endsection

@section('content')
    <div class="container-xl">
        <div class="row">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="text-warnin"><i class="far fa-frown fa-3x"></i></h3>
                    <h3 class="text-warnin">@lang(@ucfirst(str_replace('_',' ',$module)).' is currently turned off!!')</h3>
                </div>
            </div>
        </div>
    </div>
@endsection