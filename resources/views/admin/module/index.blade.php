@extends('layouts.admin')

@section('title')
   @lang('Manage Module')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header">
        <h1>@lang('Manage Module')</h1>
    </div>
</section>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card border-left border-primary">
            <div class="card-body">
                <span class="font-weight-bold">@lang('Note : ') </span> <code  class="text-warning">@lang('Turning off any module means user can not access the module until its turned back on. And Turning on Know Your Customer(KYC) restriction means if user is not KYC verified, Can not access the module. If you want KYC restriction working properly, Please active the KYC from General settings first.')</code>
            </div>
        </div>
    </div>
    @foreach ($modules as $module)
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 currency--card">
        <div class="card">
            <div class="card-body">
                <label class="cswitch d-flex justify-content-between align-items-center">
                    <input class="cswitch--input update" value="{{$module->module}}" type="checkbox" {{$module->status == 1 ? 'checked':''}} /><span class="cswitch--trigger wrapper"></span>
                    <span class="cswitch--label font-weight-bold">@lang(ucwords(str_replace('-',' ',$module->module)).' Module')</span>
                </label>
                <hr>
                <label class="cswitch d-flex justify-content-between align-items-center">
                    <input class="cswitch--input kyc" value="{{$module->module}}" type="checkbox" {{$module->kyc == 1 ? 'checked':''}} /><span class="cswitch--trigger wrapper"></span>
                    <span class="cswitch--label font-weight-bold">@lang('KYC Restriction')</span>
                </label>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

@push('script')
     <script>
            'use strict';
            (function ($) {
               $('.update').on('change', function () {
                var url = "{{route('admin.update.module')}}"
                var val = $(this).val()
                var token = "{{csrf_token()}}"
                var data = {
                    module:val,
                    _token:token
                }
                $.post(url,data,function(response) {
                    if(response.error){
                        toast('error',response.error)
                        return false;
                    }
                    toast('success',response.success)
                })
               });
               $('.kyc').on('change', function () {
                var url = "{{route('admin.update.module')}}"
                var val = $(this).val()
                var token = "{{csrf_token()}}"
                var data = {
                    module:val,
                    kyc:1,
                    _token:token
                }
                $.post(url,data,function(response) {
                    if(response.error){
                        toast('error',response.error)
                        return false;
                    }
                    toast('success',response.success)
                })
               });
            })(jQuery);
     </script>
@endpush