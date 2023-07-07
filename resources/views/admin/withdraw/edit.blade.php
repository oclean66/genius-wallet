@extends('layouts.admin')

@section('title')
   @lang('Update Withdraw Method')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header justify-content-between">
        <h1>@lang('Update Withdraw Method')</h1>
        <a href="{{route('admin.withdraw')}}" class="btn btn-primary add"><i class="fa fa-backward"></i> @lang('Back')</a>
    </div>
</section>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin.withdraw.update',$method)}}" method="post">
                        @csrf
                            <div class="row">
                                <div class="form-group col-md-12 col-12">
                                    <label for="">@lang('Method Name') <span class="text-danger">*</span> </label>
                                    <input type="text" name="name" class="form-control" value="{{$method->name}}" required>
                                
                                </div> 
                                
                
                                <div class="form-group col-md-6 col-12">

                                    <label for="">@lang('Fixed charge')</label>
                                    <input type="text" name="fixed_charge" class="form-control" value="{{numFormat($method->fixed_charge)}}" required>
                                
                                </div>  
                                <div class="form-group col-md-6 col-12">

                                    <label for="">@lang('Percent charge')</label>
                                    <input type="text" name="percent_charge" class="form-control" value="{{numFormat($method->percent_charge)}}" required>
                                
                                </div>  

                                
                                <div class="form-group col-md-6 col-12">

                                    <label for="">@lang('Minimum Amount')</label>
                                    <input type="text" name="min_amount" class="form-control" value="{{numFormat($method->min_amount)}}" required>
                                
                                </div> 
                                
                                <div class="form-group col-md-6 col-12">

                                    <label for="">@lang('Maximum Amount')</label>
                                    <input type="text" name="max_amount" class="form-control" value="{{numFormat($method->max_amount)}}" required>
                                
                                </div> 
    
                                    <div class="form-group col-md-6 col-12">

                                    <label for="">@lang('Select Currency')</label>
                                    <select name="currency" class="form-control" required>
                                        <option value="">@lang('Select')</option>
                                        @foreach ($currencies as $item)
                                         <option value="{{ $item->id }}" {{$method->currency_id == $item->id ? 'selected':''}}>{{ $item->code }}</option>
                                        @endforeach
                                    </select>
                                
                                </div> 
                                    <div class="form-group col-md-6 col-12">

                                    <label for="">@lang('Status')</label>
                                    <select name="status" class="form-control">
                                        <option value="">@lang('Select')</option>
                                        <option value="0" {{$method->status == 0 ? 'selected':''}}>@lang('Inactive')</option>
                                        <option value="1" {{$method->status == 1 ? 'selected':''}}>@lang('Active')</option>
                                    </select>
                                
                                </div> 
                                
                                <div class="form-group col-md-12">
                                    <label for="">@lang('Instructions for Withdraw')</label>
                                    <textarea name="withdraw_instruction" id="" rows="5" class="form-control summernote">{{$method->withdraw_instruction}}</textarea>
                                </div>

                                <div class="form-group col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary btn-lg">@lang('Submit')</button>
                                </div>
                            </div>
                    
                    
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection