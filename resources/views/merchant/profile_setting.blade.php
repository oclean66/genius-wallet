@extends('layouts.merchant')

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
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label class="col-form-label">@lang('Profile Picture')</label>
                        <div id="image-preview" class="image-preview ml-auto mr-auto"
                            style="background-image:url({{ getPhoto( @$user->photo) }});">
                            <label for="image-upload" id="image-label">@lang('Choose File')</label>
                            <input type="file" name="photo" id="image-upload" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>@lang('Business Name')</label>
                             <input class="form-control" name="business_name" type="text" value="{{@$user->business_name}}" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Your Name')</label>
                            <input class="form-control" name="name" type="text" value="{{@$user->name}}" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Email')</label>
                            <input class="form-control"  type="email" value="{{@$user->email}}" disabled>
                        </div>
                        <div class="form-group">
                            <label>@lang('Phone')</label>
                            <input class="form-control" disabled type="text" value="{{@$user->phone}}" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Country')</label>
                            <input class="form-control" disabled type="text" value="{{@$user->country}}" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('City')</label>
                            <input class="form-control" name="city" type="text" value="{{@$user->city}}" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Address')</label>
                            <input class="form-control" name="address" type="text" value="{{@$user->address}}" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Zip')</label>
                            <input class="form-control" name="zip" type="text" value="{{@$user->zip}}" required>
                        </div>
                        <div class="form-group mt-3 text-right">
                            <button type="submit" class="btn btn-primary btn-lg">@lang('Submit')</button>
                        </div>
                    </div>
                </div>
            
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