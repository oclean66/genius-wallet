@extends('layouts.admin')

@section('title')
   @lang('Update '.$charge->name.' Charge')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header d-flex justify-content-between">
      <h1>  @lang('Update '.$charge->name.' Charge')</h1>
      <a href="{{route('admin.manage.charge')}}" class="btn btn-primary btn-sm"><i class="fas fa-backward"></i> @lang('Back')</a>
    </div>
</section>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin.update.charge',$charge->id)}}" method="post">
                        @csrf
                        @if($charge->data)
                        @foreach ($charge->data as $key => $value)
                            <div class="form-group">
                              <label for="">{{ucwords(str_replace('_',' ',$key))}} 
                                @if ($key == 'percent_charge' || $key == 'commission')
                                  (%)
                                @endif 
                                <span class="text-danger">*</span></label>
                                <input type="text" name="{{$key}}" class="form-control" value="{{@$value}}">
                            </div>
                        @endforeach
                            @if ($charge->name == 'Transfer Money')
                            <code>(@lang('Put 0 for no limit'))</code>
                            @endif
                        @endif
                        @if (access('update charge'))
                        <div class="form-group text-right">
                            <button class="btn btn-primary btn-lg">@lang('Update')</button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection