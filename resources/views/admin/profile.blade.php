@extends('layouts.admin')

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
    <div class="card">
                <div class="card-body">
                    <form action="{{route('admin.profile.update')}}" class="row" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-4 col-xl-3">
                            <div class="form-group">
                                <label class="col-form-label">@lang('Profile Picture')</label>
                                <div id="image-preview" class="image-preview"
                                    style="background-image:url({{ getPhoto( $data->photo) }});">
                                    <label for="image-upload" id="image-label">@lang('Choose File')</label>
                                    <input type="file" name="photo" id="image-upload" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-xl-9">
                            <div class="form-group">
                                <label>@lang('Name')</label>
                                <input class="form-control" type="text" name="name" value="{{$data->name}}" required>
                            </div>
                            <div class="form-group">
                                <label>@lang('Email')</label>
                                <input class="form-control" type="email" name="email" value="{{$data->email}}" required>
                            </div>
                            <div class="form-group">
                                <label>@lang('Phone')</label>
                                <input class="form-control" type="text" name="phone" value="{{$data->phone}}" required>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary btn-lg">@lang('Submit')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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