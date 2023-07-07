@extends('layouts.admin')

@section('title')
   @lang('Manage Addon')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header">
        <h1>@lang('Manage Addon')</h1>
    </div>
</section>
@endsection

@section('content')
    <div class="row justify-content-center">
        
        <div class="col-md-8">
            <div class="card border-left border-primary">
                <div class="card-body">
                    <span class="font-weight-bold">@lang('Warning') :  </span> <code class="text-warning">@lang('We strongly recommend that, please backup your whole system and database before you update the system.')</code>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5>@lang('Install Update') </h5> 
                    <h6 class="text-primary">@lang('System version : '.sysVersion())</h6>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{route('admin.update.install')}}" method="POST"   enctype="multipart/form-data">
                        @csrf   
                        <div class="form-group">
                            <label for="addon_zip">@lang('Zip File')</label>
                            <div class="custom-file">
                                <input type="file" name="addon" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">@lang('Install / Update')</button>
                        </div>
                    </form>
   
                </div>
            </div>

        </div>
    </div>
@endsection
@push('style')
   <style>
       .form-control {
         line-height: 1.2 !important
       }
   </style>
@endpush