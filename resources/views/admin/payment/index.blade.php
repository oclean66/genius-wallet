@extends('layouts.admin')

@section('title')
   @lang('Payment Gateways')
@endsection

@section('breadcrumb')
 <section class="section">
	<div class="section-header justify-content-between">
		<h1>@lang('Payment Gateways')</h1>
    <a href="{{route('admin.gateway.create')}}" class="btn btn-primary"><i class="fas fa-plus"></i> @lang('Add New')</a>
	</div>
</section>
@endsection

@section('content')
<div class="row">
    @foreach ($gateways as $item)
    <div class="col-md-6 col-lg-6 col-xl-3">
      <div class="card card-primary">
        <div class="card-header justify-content-center">
          <h4><i class="fas fa-money-check-alt"></i> {{$item->name}}</h4>
        </div>
        <div class="card-body">
            <a href="{{route('admin.gateway.edit',$item)}}" class="btn btn-primary btn-block"><i class="fas fa-edit"></i> @lang('Edit Gateway')</a>
        </div>
      </div>
    </div>
    @endforeach
</div>
@endsection
