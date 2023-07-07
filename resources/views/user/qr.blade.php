@extends('layouts.user')

@section('title')
   @lang('User QR code')
@endsection

@section('breadcrumb')
  @lang('Your QR code')
@endsection

@section('content')
    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-center my-5">
                        <div>
                            
                            <img src="{{generateQR(auth()->user()->email)}}" class="w-25" alt="">
                        </div>
                        <h3 class="mt-4">{{auth()->user()->email}}</h3>
                        <div class="mt-3">
                            <a href="{{route('user.download.qr',auth()->user()->email)}}" class="btn btn-primary ">@lang('Download')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
