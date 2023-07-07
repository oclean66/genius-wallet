@extends('layouts.merchant')

@section('title')
   @lang('Module Off')
@endsection

@section('breadcrumb')
<section class="section">
    <div class="section-header">
        <h1>@lang('Module Off')</h1>
    </div>
</section>
@endsection

@section('content')

    <div class="row justify-content-center">
       <div class="col-md-8">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="display-1"><i class="far fa-frown fa-3x"></i></h5>
                <h5 class="text-warnin">@lang(@ucfirst(str_replace('_',' ',$module)).' is currently turned off!!')</h5>
            </div>
        </div>
       </div>
    </div>
   
@endsection