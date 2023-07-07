@extends('layouts.admin')

@section('title')
   @lang('Add New Currency')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header d-flex justify-content-between">
        <h1>@lang('Add New Currency')</h1>
        <a href="{{route('admin.currency.index')}}" class="btn btn-primary btn-sm"><i class="fas fa-backward"></i> @lang('Back')</a>
    </div>
</section>
@endsection

@section('content')
<div class="row justify-content-center">

    <div class="col-md-8">
       <div class="card">
            <div class="card-body">
                @include('admin.partials.form-both')
                <form action="" method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>@lang('Currency Name')</label>
                            <input class="form-control" type="text" name="curr_name" required value="{{old('curr_name')}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>@lang('Currency Code')</label>
                            <input class="form-control" type="text" name="code" required value="{{old('code')}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>@lang('Currency Symbol')</label>
                            <input class="form-control" type="text" name="symbol"  required value="{{old('symbol')}}">
                        </div>
              
                        <div class="form-group col-md-6">
                            <label>@lang('Currency Rate')</label>
                            <div class="input-group has_append">
                                <div class="input-group-prepend">
                                    <div class="input-group-text cur_code">1 {{$gs->curr_code}}</div>
                                </div>
                                <input type="text" class="form-control" placeholder="0" name="rate" value="{{ old('rate') }}"/>
                                <div class="input-group-append">
                                    <div class="input-group-text"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>@lang('Currency Type')</label>
                            <select class="form-control" name="type" required>
                                <option value="" selected>--@lang('Select Type')--</option>
                                <option value="1">@lang('FIAT')</option>
                                <option value="2">@lang('CRYPTO')</option>
                            </select>
                        </div>
                       
                        <div class="form-group col-md-6">
                            <label>@lang('Set As Default') </label>
                            <select class="form-control" name="default" required>
                                <option value="" selected>--@lang('Select')--</option>
                                <option value="1">@lang('Yes')</option>
                                <option value="0">@lang('No')</option>
                            </select>
                        </div>
                       
                        <div class="form-group col-md-12">
                            <label>@lang('Status') </label>
                            <select class="form-control" name="status" required>
                                <option value="" selected>--@lang('Select')--</option>
                                <option value="1">@lang('Active')</option>
                                <option value="0">@lang('Inactive')</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group text-right col-md-12">
                        <button class="btn  btn-primary btn-lg" type="submit">@lang('Submit')</button>
                    </div>
                </form>
            </div>
       </div>
    </div>
</div>
@endsection