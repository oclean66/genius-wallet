@extends('layouts.admin')

@section('title')
   @lang('Add New Payment Gateway')
@endsection

@section('breadcrumb')
 <section class="section">
	<div class="section-header justify-content-between">
		<h1>@lang('Add New Payment Gateway')</h1>
    <a href="{{route('admin.gateway')}}" class="btn btn-primary"><i class="fas fa-backward"></i> @lang('Back')</a>
	</div>
</section>
@endsection
@section('content')

<div class="row justify-content-center mt-3">
   <div class="col-lg-12">
      <!-- Form Basic -->
      <div class="card mb-4">
         <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Add New Payment Gateway Form') }}</h6>
         </div>
         <div class="card-body">
            <div class="gocover" style="background: url({{asset('assets/images/'.$gs->dashboard_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
            <form action="{{route('admin.gateway.store')}}" enctype="multipart/form-data" method="POST">
              @csrf
              @include('admin.partials.form-both')
               <div class="row">
                  <div class="form-group col-md-6">
                     <label for="name">{{ __('Name') }}</label>
                     <input type="text" class="form-control" name="title" id="name" placeholder="{{ __('Name') }}" value="{{old('title')}}">
                 </div>   
             
                 <div class="form-group col-md-6">
                     <label for="subtitle">{{ __('Subtitle') }}</label>
                     <input type="text" class="form-control" id="subtitle" name="subtitle" placeholder="{{ __('Subtitle') }}" value="{{old('subtitle')}}">
                 </div>
                 
                 <div class="form-group col-md-6">
                     <label for="subtitle">{{ __('Fixed Charge') }}</label>
                     <div class="input-group">
                        <input type="text" class="form-control" id="fixed_charge" name="fixed_charge" placeholder="{{ __('Fix Charge') }}" value="{{old('fixed_charge')}}">
                        <div class="input-group-append">
                           <span class="input-group-text" id="my-addon">{{$gs->curr_code}}</span>
                        </div>
                     </div>
                 </div>
                 
                 <div class="form-group col-md-6">
                     <label for="subtitle">{{ __('Percent Charge') }}</label>
                     <div class="input-group">
                        <input type="text" class="form-control" id="subtitle" name="percent_charge" placeholder="{{ __('Percent Charge') }}" value="{{old('percent_charge')}}">
                        <div class="input-group-append">
                           <span class="input-group-text" id="my-addon">%</span>
                        </div>
                     </div>
                 </div>
                 
                 <div class="form-group col-md-12">
                     <label for="details">{{ __('Description') }}</label>
                     <textarea id="area1" class="form-control summernote" rows="10" name="details" placeholder="{{ __('Description ') }}">{{old('details')}}</textarea>
                 </div>
               </div>
              

        
              @foreach(DB::table('currencies')->get() as $dcurr)
                 
              <input  name="currency_id[]" type="checkbox" id="currency_id{{$dcurr->id}}" value="{{$dcurr->id}}">
              <label class="mr-4" for="currency_id{{$dcurr->id}}">{{$dcurr->code}}</label>
         
              @endforeach
              <div class="form-group text-right mt-4">
              <button type="submit" id="insertButton" class="btn btn-primary btn-lg">{{ __('Submit') }}</button>
              </div>
          </form>
         </div>
      </div>
      <!-- Form Sizing -->
      <!-- Horizontal Form -->
   </div>
</div>
<!--Row-->
@endsection