@extends('layouts.agent')

@section('title')
   @lang('QR Code')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header">
        <h1>@lang('QR Code')</h1>
    </div>
</section>
@endsection

@section('content')
        <div class="qr--code">
            <div class="card">
                <div class="card-body text-center">
                    <div>
                        <img src="{{generateQR(agent()->email)}}" class="w-100" alt="">
                    </div>
                    <h6 class="mt-4">{{agent()->email}}</h6>
                    <div class="mt-3">
                        <a href="{{route('agent.download.qr',agent()->email)}}" class="btn btn-primary btn-lg btn-download">@lang('Download')</a>
                    </div>
                </div>
            </div>
        </div>
@endsection
