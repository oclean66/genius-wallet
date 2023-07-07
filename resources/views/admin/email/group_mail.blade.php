@extends('layouts.admin')

@section('title')
   @lang('Group Email')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header">
        <h1>@lang('Group Email')</h1>
    </div>
</section>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
       <div class="card mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
             <h6 class="m-0 font-weight-bold text-primary">{{ __('Group Mail Form') }}</h6>
          </div>
          <div class="card-body">
             <div class="gocover" style="background: url({{asset('assets/images/'.$gs->dashboard_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
             <form action="{{route('admin.group.submit')}}" enctype="multipart/form-data" method="POST">
                 @csrf
  
                 <div class="row">
                     <div class="col-md-12">
                         <div class="form-group">
                             <label for="type">{{ __('Select Type') }}</label>
                             <select class="form-control  mb-3" id="type" name="type">
                                 <option value="" selected disabled>{{__('Select Type')}}</option>
                                 <option value="user">{{__('All User')}}</option>
                                 <option value="merchant">{{__('All Merchant')}}</option>
                             </select>
                           </div>        
                     </div>
                     <div class="col-md-12">
                     <div class="form-group">
                         <label for="subject">{{ __('Email Subject') }}</label>
                         <input type="text" class="form-control" name="subject" id="subject" placeholder="{{ __('Email Subject') }}">
                     </div>
                     </div>
             
                     <div class="col-md-12">
                        <div class="form-group">
                            <label for="body">{{ __('Message') }}</label>
                            <textarea id="body" class="form-control summernote" name="message" rows="5" placeholder="{{ __('Description') }}"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12 text-right">
                        <button type="submit"  class="btn btn-primary btn-lg">{{ __('Submit') }}</button>
                    </div>
             </form>
          </div>
       </div>
    </div>
 </div>
@endsection