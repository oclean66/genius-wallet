@extends('layouts.merchant')

@section('title')
   @lang('KYC Form')
@endsection

@section('breadcrumb')
<section class="section">
    <div class="section-header">
        <h1>@lang('KYC Form')</h1>
    </div>
</section>
   
@endsection

@section('content')
    <div class="container-xl">
        <div class="card">
            <div class="card-body p-3">
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        @foreach ($kycForm as $field)
                            @if ($field->type == 1)
                                <div class="form-group col-md-12 mb-3">
                                    <label for="" class="mb-2">
                                        @lang($field->label) @if($field->required == 1) 
                                         <code>@lang('(required)')</code> @else <code>@lang('(optional)')</code> @endif
                                    </label>
                                    <input type="text" class="form-control shadow-none" name="{{$field->name}}" {{$field->required == 1 ? 'required':''}}>
                                </div>
                            @elseif($field->type == 2)
                                <div class="form-group col-md-12 mb-3">
                                    <label for="" class="mb-2">@lang($field->label) <small class="text-danger">@lang('(allowed extention : .png, .jpg, .jpeg)')</small> @if($field->required == 1) 
                                        <code>@lang('(required)')</code> @else <code>@lang('(optional)')</code> @endif</label>
                                    <input type="file" class="form-control shadow-none" name="{{$field->name}}" {{$field->required == 1 ? 'required':''}}>
                                </div>
                            @elseif($field->type == 3)
                                <div class="form-group col-md-12 mb-3">
                                    <label for="" class="mb-2">@lang($field->label)@if($field->required == 1) 
                                        <code>@lang('(required)')</code> @else <code>@lang('(optional)')</code> @endif</label>
                                    <textarea  class="form-control shadow-none" rows="5" name="{{$field->name}}" {{$field->required == 1 ? 'required':''}}></textarea>
                                </div>
                            @endif
                        @endforeach

                        <div class="form-group col-md-12 mb-3 text-end">
                            <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection