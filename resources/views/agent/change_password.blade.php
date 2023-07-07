@extends('layouts.agent')

@section('title')
   @lang('Profile Setting')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header">
        <h1>@lang('Profile Setting')</h1>
    </div>
</section>
@endsection

@section('content')
<form action="" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="" method="POST">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>@lang('Old Password')</label>
                            <input class="form-control" name="old_pass" type="password" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('New Password')</label>
                            <input class="form-control"  type="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Confirm Password')</label>
                            <input class="form-control" type="password" name="password_confirmation"  required>
                        </div>
                        <div class="form-group mt-3 text-right">
                            <button type="submit" class="btn btn-primary btn-lg">@lang('Submit')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</form>
@endsection

@push('script')
    <script>
        'use strict';
        $.uploadPreview({
                input_field: "#image-upload", // Default: .image-upload
                preview_box: "#image-preview", // Default: .image-preview
                label_field: "#image-label", // Default: .image-label
                label_default: "{{__('Choose File')}}", // Default: Choose File
                label_selected: "{{__('Update Image')}}", // Default: Change File
                no_label: false, // Default: false
                success_callback: null // Default: null
            });
    </script>
@endpush