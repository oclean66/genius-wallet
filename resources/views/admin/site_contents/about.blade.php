@extends('layouts.admin')

@section('title')
   @lang(ucfirst($section->name).' Section')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header d-flex justify-content-between">
        <h1>@lang(ucfirst($section->name).' Section')</h1>
        <a href="{{route('admin.frontend.index')}}" class="btn btn-primary"><i class="fa fa-backward"></i> @lang('Back')</a>
    </div>
</section>
@endsection

@section('content')
    <div class="row">
        @if ($section->content)
        <div class="col-md-12">
           <div class="card">
               <div class="card-header">
                    <h6>@lang(ucfirst($section->name).' Content')</h6>
               </div>
               <div class="card-body">
                    <form action="{{route('admin.frontend.content.update',$section->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                           @if (@$section->content->image)
                           <div class="form-group col-md-12">
                                <label for="">@lang('Background Image')</label>
                                <div class="gallery gallery-fw"  data-item-height="450">
                                    <img class="gallery-item imageShow" data-image="{{getPhoto(@$section->content->image)}}">
                                </div>
                                <div class="custom-file">
                                    <input type="file" name="image"  class="custom-file-input imageUpload mb-2" id="customFile">
                                    <code class="text-danger">@lang('Image size : 700 x 570 px')</code>
                                    <input type="hidden" name="image_size" value="700x570">
                                    <label class="custom-file-label" for="customFile">@lang('Choose file')</label>
                                </div>
                            </div>
                           @endif
                            <div class="form-group col-md-6">
                                <label for="">@lang('Title')</label>
                                <input type="text" name="title" class="form-control" placeholder="@lang('About Title')" value="{{@$section->content->title}}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">@lang('Heading')</label>
                                <input type="text" name="heading" class="form-control" placeholder="@lang('About Heading')" value="{{@$section->content->heading}}" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="">@lang('Short Details')</label>
                                <textarea  name="short_details" class="form-control" required>{{@$section->content->short_details}}</textarea>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="">@lang('Feature 1 Icon')</label>
                                <div class="input-group">
                                    <input type="text" class="form-control icon-value1" name="feature_1_icon"
                                    value="{{@$section->content->feature_1_icon}}">
                                    <span class="input-group-append">
                                        <button class="btn btn-outline-secondary iconpicker1" data-icon="fas fa-home"
                                            role="iconpicker"></button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">@lang('Feature 1 Title')</label>
                                <input type="text" name="feature_1_title" class="form-control" placeholder="@lang('Feature Title')" value="{{@$section->content->feature_1_title}}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">@lang('Feature 1 Details')</label>
                                <input type="text" name="feature_1_details" class="form-control" placeholder="@lang('Feature Details')" value="{{@$section->content->feature_1_details}}" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="">@lang('Feature 2 Icon')</label>
                                <div class="input-group">
                                    <input type="text" class="form-control icon-value2" name="feature_2_icon"
                                    value="{{@$section->content->feature_2_icon}}">
                                    <span class="input-group-append">
                                        <button class="btn btn-outline-secondary iconpicker2" data-icon="fas fa-home"
                                            role="iconpicker"></button>
                                    </span>
                                </div>
                            </div>

                           
                            <div class="form-group col-md-6">
                                <label for="">@lang('Feature 2 Title')</label>
                                <input type="text" name="feature_2_title" class="form-control" placeholder="@lang('Feature Title')" value="{{@$section->content->feature_2_title}}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">@lang('Feature 2 Details')</label>
                                <input type="text" name="feature_2_details" class="form-control" placeholder="@lang('Feature Details')" value="{{@$section->content->feature_2_details}}" required>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">@lang('Submit')</button>
                            </div>
                        </div>
                    </form>
               </div>
           </div>
        </div>
        @endif
      
    </div>
@endsection

@push('script')
    <script>
        'use strict'
        function imageShow(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $(input).parents('.form-group').find('.imageShow').attr('src',e.target.result)
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(".imageUpload").on('change', function () {
            imageShow(this);
        });

        $('.iconpicker1').iconpicker();
        $('.iconpicker2').iconpicker();

        $('.iconpicker1').on('change', function(e) {
            $('.icon-value1').val(e.icon)
        })
        $('.iconpicker2').on('change', function(e) {
            $('.icon-value2').val(e.icon)
        })
    </script>
@endpush