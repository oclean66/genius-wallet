@extends('layouts.user')

@section('title')
   @lang('Profile Settings')
@endsection

@section('breadcrumb')
   @lang('Profile Settings')
@endsection

@section('content')
    <div class="container-xl">
        <form action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row ">
                <div class="col-md-4">
                    <div class="card card-sm">
                        <a href="#" class="d-block"><img src="{{getPhoto($user->photo)}}" width="300px" height="300px" class="card-img-top imageShow"></a>
                        <div class="card-body">
                          <input type="file" class="form-control imageUpload file-type mb-1" name="photo">
                        </div>
                      </div>
                </div>
                <div class="col-md-8">
                   <div class="card">
                       <div class="card-body row">
                            <div class="form-group col-md-6 mb-2">
                                <label class="mb-2">@lang('Name')</label>
                                <input class="form-control" type="text" name="name" value="{{$user->name}}" required>
                            </div>
                            <div class="form-group col-md-6 mb-2">
                                <label class="mb-2">@lang('Email')</label>
                                <input class="form-control" type="email" value="{{$user->email}}" disabled>
                            </div>
                            <div class="form-group col-md-6 mb-2">
                                <label class="mb-2">@lang('Phone')</label>
                                <input class="form-control" name="phone" type="text" value="{{$user->phone}}" required>
                            </div>
                            <div class="form-group col-md-6 mb-2">
                                <label class="mb-2">@lang('Country')</label>
                                <input class="form-control" type="text" value="{{$user->country}}" disabled>
                            </div>
                            <div class="form-group col-md-6 mb-2">
                                <label class="mb-2">@lang('City')</label>
                                <input class="form-control" type="text" name="city" value="{{$user->city}}">
                            </div>
                            <div class="form-group col-md-6 mb-2">
                                <label class="mb-2">@lang('Zip')</label>
                                <input class="form-control" type="text" name="zip" value="{{$user->zip}}">
                            </div>
                            <div class="form-group col-md-12 mb-2">
                                <label class="mb-2">@lang('Address')</label>
                                <input class="form-control" type="text" name="address" value="{{$user->address}}">
                            </div>
                            <div class="form-group col-md-12 mb-2 text-end">
                                <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                            </div>
                       </div>
                   </div>
                </div>
            </div>
        </form>
       
        <form action="{{route('user.change.pass')}}" method="POST">
            @csrf
            <div class="row justify-content-end">
                <div class="col-md-8">
                   <div class="card">
                       <div class="card-body row">
                        <div class="form-group col-md-12 mb-2">
                            <h3>@lang('Change Password')</h3>
                        </div>
                            <div class="form-group col-md-12 mb-2">
                                <label class="mb-2">@lang('Old Password')</label>
                                <input class="form-control" type="password" name="old_pass" required>
                            </div>
                            <div class="form-group col-md-12 mb-2">
                                <label class="mb-2">@lang('New Password')</label>
                                <input class="form-control" type="password" name="password" required>
                            </div>
                            <div class="form-group col-md-12 mb-2">
                                <label class="mb-2">@lang('Confirm Password')</label>
                                <input class="form-control" type="password" name="password_confirmation" required>
                            </div>
                           
                            <div class="form-group col-md-12 mb-2 text-end">
                                <button type="submit" class="btn btn-primary">@lang('Change')</button>
                            </div>
                       </div>
                   </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script')
    <script>
        'use strict';
        function imageUpload(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $(input).parents('.card').find('.imageShow').attr('src',e.target.result)
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(".imageUpload").on('change', function () {
            imageUpload(this);
        });
    </script>
@endpush