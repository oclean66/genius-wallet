@extends('layouts.admin')

@section('title')
   @lang('Charges')
@endsection

@section('breadcrumb')
 <section class="section">
  <div class="section-header d-flex justify-content-between">
    <h1>@lang('Manage Charges')</h1>
    <form action="">
      <div class="input-group has_append">
        <input type="text" class="form-control" placeholder="@lang('Name')" name="search" value="{{$search ?? ''}}"/>
        <div class="input-group-append">
            <button class="input-group-text bg-primary border-0"><i class="fas fa-search text-white"></i></button>
        </div>
    </div>
    </form>
</div>
</section>
@endsection

@section('content')
<div class="row">
    @forelse ($charges as $charge)
    <div class="col-sm-6 col-lg-4 col-xl-3 currency--card">
      <div class="card card-primary">
        <div class="card-header">
          <h4>{{$charge->name}}</h4>
        </div>
        <div class="card-body">
          <ul class="list-group mb-3">
            @foreach ($charge->data as $key => $value)
              @if ($key == 'percent_charge' || $key == 'fixed_charge')
                <li class="list-group-item d-flex justify-content-between">@lang(ucwords(str_replace('_',' ',$key)).' :')
                  <span class="font-weight-bold">{{@$value}}
                    @if ($key == 'percent_charge')
                        %
                    @else
                      {{$gs->curr_code}}
                    @endif 
                  </span>
                </li>
              @elseif($key == 'commission')
              <li class="list-group-item d-flex justify-content-between">@lang(ucwords(str_replace('_',' ',$key)).' :')
                 <span class="font-weight-bold">{{@$value}} %</span>
              </li>
              @endif
            @endforeach
          </ul>
          @if (access('edit charge'))
            <a href="{{route('admin.edit.charge',$charge->id)}}" class="btn btn-primary btn-block"><i class="fas fa-edit"></i> @lang('Edit Charge')</a>
          @endif
        </div>
      </div>
    </div>
    @empty
    <div class="col-md-12 text-center">
        <h5>@lang('No data found')</h5>
    </div>
    @endforelse
</div>
@endsection