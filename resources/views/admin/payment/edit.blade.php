@extends('layouts.admin')

@section('title')
   @lang('Edit Gateway : '.$paymentgateway->name)
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header justify-content-between">
        <h1> @lang('Edit Gateway : '.$paymentgateway->name)</h1>
        <a href="{{route('admin.gateway')}}" class="btn btn-primary"><i class="fas fa-backward"></i> @lang('Back')</a>
    </div>
</section>
@endsection

@section('content')

<div class="row justify-content-center mt-3">
   <div class="col-lg-12">
      <div class="card mb-4">
         <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Edit Gateway') }}</h6>
         </div>
         <div class="card-body">
            <div class="gocover" style="background: url({{asset('assets/images/'.$gs->dashboard_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
            <form  action="{{route('admin.gateway.update',$paymentgateway)}}" method="POST" enctype="multipart/form-data">
             @csrf
              
              @include('admin.partials.form-both')
              @if($paymentgateway->type == 'automatic')
             
              <div class="form-group row mb-3">
                <label for="name" class="col-sm-4 col-form-label">{{ __('Name') }}</label>
                <div class="col-sm-8">
                   <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('Gateway Name') }}" value="{{$paymentgateway->name}}">
                </div>
              </div>

              
               @if($paymentgateway->information != null)
                  @foreach($paymentgateway->convertAutoData() as $pkey => $pdata)
                     @if($pkey != 'sandbox_check')
                        <div class="form-group row mb-3">
                        <label  class="col-sm-4 col-form-label">{{ __( $paymentgateway->name.' '.ucwords(str_replace('_',' ',$pkey)) ) }} *</label>
                        <div class="col-sm-8">
                           <input type="text" class="form-control" name="pkey[{{ __($pkey) }}]" value="{{ $pdata }}" {{ $pdata == 1 ? "checked":"" }}>
                        </div>
                     </div>
                     @endif
                  @endforeach
                  
                  @foreach($paymentgateway->convertAutoData() as $pkey => $pdata)
                     @if ($pkey == 'sandbox_check')
                     <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="pkey[{{ __($pkey) }}]" class="custom-control-input" value="1" id="customCheck1" {{ $pdata == 1 ? "checked":"" }}>
                        <label class="custom-control-label" for="customCheck1">{{ __( $paymentgateway->name.' '.ucwords(str_replace('_',' ',$pkey)) ) }} *</label>
                    </div>
                     @endif
                  @endforeach
                  <div class="form-group row mb-3">
                     <label for="name" class="col-sm-4 col-form-label">{{ __('Status') }}</label>
                     <div class="col-sm-8">
                       <select name="status" class="form-control">
                          <option value="1" {{$paymentgateway->status == 1 ? 'selected':''}}>@lang('Active')</option>
                          <option value="0" {{$paymentgateway->status == 0 ? 'selected':''}}>@lang('Inactive')</option>
                       </select>
                     </div>
                   </div>

                  <hr>
                  @php
                     $setCurrency = $paymentgateway->currency_id;
                     if($setCurrency == 0){
                        $setCurrency = [];
                     }
                  @endphp
                  @foreach(DB::table('currencies')->get() as $dcurr)
                 
                     <input  name="currency_id[]" {{in_array($dcurr->id,$setCurrency) ? 'checked' : ''}} type="checkbox" id="currency_id{{$dcurr->id}}" value="{{$dcurr->id}}">
                     <label class=" mr-4" for="currency_id{{$dcurr->id}}">{{$dcurr->code}}</label>
                
                  @endforeach
                  
                @endif
          
                @else

              
               <div class="row">
                  <div class="form-group col-md-6">
                     <label for="name">{{ __('Name') }}</label>
                     <input type="text" class="form-control" name="title" id="name" placeholder="{{ __('Name') }}" value="{{$paymentgateway->title}}">
                 </div>   
             
                 <div class="form-group col-md-6">
                     <label for="subtitle">{{ __('Subtitle') }}</label>
                     <input type="text" class="form-control" id="subtitle" name="subtitle" placeholder="{{ __('Subtitle') }}" value="{{$paymentgateway->subtitle}}">
                 </div>
                 
                 <div class="form-group col-md-6">
                     <label for="subtitle">{{ __('Fixed Charge') }}</label>
                     <div class="input-group">
                        <input type="text" class="form-control" id="fixed_charge" name="fixed_charge" placeholder="{{ __('Fix Charge') }}"  value="{{numFormat($paymentgateway->fixed_charge)}}">
                        <div class="input-group-append">
                           <span class="input-group-text" id="my-addon">{{$gs->curr_code}}</span>
                        </div>
                     </div>
                 </div>
                 
                 <div class="form-group col-md-6">
                     <label for="subtitle">{{ __('Percent Charge') }}</label>
                     <div class="input-group">
                        <input type="text" class="form-control" id="subtitle" name="percent_charge" placeholder="{{ __('Percent Charge') }}" value="{{$paymentgateway->percent_charge}}">
                        <div class="input-group-append">
                           <span class="input-group-text" id="my-addon">%</span>
                        </div>
                     </div>
                 </div>

                 <div class="form-group col-md-12 mb-3">
                  <label for="name">{{ __('Status') }}</label>
                    <select name="status" class="form-control">
                       <option value="1" {{$paymentgateway->status == 1 ? 'selected':''}}>@lang('Active')</option>
                       <option value="0" {{$paymentgateway->status == 0 ? 'selected':''}}>@lang('Inactive')</option>
                    </select>
                  
                </div>
                 
                 <div class="form-group col-md-12">
                     <label for="details">{{ __('Description') }}</label>
                     <textarea id="area1" class="form-control summernote" rows="10" name="details" placeholder="{{ __('Description ') }}">{{$paymentgateway->details}}</textarea>
                 </div>

                 <hr>
                 @php
                     $setCurrency = $paymentgateway->currency_id;
                     if($setCurrency == 0){
                        $setCurrency = [];
                     }
                  @endphp
                  <div class="col-md-12">
                  @foreach(DB::table('currencies')->get() as $dcurr)
                        <input  name="currency_id[]" {{in_array($dcurr->id,$setCurrency) ? 'checked' : ''}} type="checkbox" id="currency_id{{$dcurr->id}}" value="{{$dcurr->id}}">
                     <label class="mr-4" for="currency_id{{$dcurr->id}}">{{$dcurr->code}}</label>
                     @endforeach
                  </div>
               </div>
               @endif
          
                <div class="form-group row mt-4 ">
                  <div class="col-sm-12 text-right">
                     <button type="submit" class="btn btn-primary btn-lg">{{__('Update')}}</button>
                  </div>
               </div>
          </form>
         </div>
      </div>
   </div>
</div>
@endsection