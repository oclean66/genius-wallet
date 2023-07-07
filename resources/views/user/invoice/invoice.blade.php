@extends('layouts.user')

@section('title')
   @lang('Invoice')
@endsection

@section('breadcrumb')
      @lang('Invoice : '.$invoice->number)
@endsection
@push('extra')
  <a href="{{route('user.invoice.index')}}" class="btn btn-primary"><i class="fas fa-backward me-1"></i> @lang(' Back')</a>
@endpush


@section('content')
<div class="container-xl">
    <div class="card card-lg">
      <div class="card-body">
        <div class="row">
          <div class="col-6">
            <p class="h3">@lang('From')</p>
            <address>
              {{@$contact->content->address}}<br>
              {{@$contact->content->email}}<br>
              {{@$contact->content->phone}}<br>
            </address>
          </div>
          <div class="col-6 text-end">
            <p class="h3">@lang('To')</p>
            <address>
                {{$invoice->address}}<br>
                {{$invoice->email}} 
            </address>
          </div>
          <div class="col-12 my-5">
            <h1>@lang('Invoice : ') {{$invoice->number}}</h1>
          </div>
        </div>
        <table class="table table-transparent table-responsive">
          <thead>
            <tr>
              <th class="text-center" style="width: 1%">@lang('SL')</th>
              <th>@lang('Item')</th>
              <th class="text-end" style="width: 1%">@lang('Amount')</th>
            </tr>
          </thead>
          @foreach ($invoice->items as $k => $value)
          <tr>
            <td class="text-center">{{++$k}}</td>
            <td>
              <p class="strong mb-1">{{ $value->name}}</p>
            </td>
            <td class="text-end">{{$invoice->currency->symbol}}{{ numFormat($value->amount) }}</td>
          </tr>
          @endforeach
         <tr>
             
             <td colspan="12" class="text-end fw-bold">@lang('Total : '.$invoice->currency->symbol.numFormat($invoice->final_amount))</td>
         </tr>
        </table>
        <p class="text-muted text-center mt-5">@lang('Thank you very much for doing business with us. We look forward to working with
            you again!') <br> <small class="mt-5">@lang('All right reserved ') <a href="{{url('/')}}">{{$gs->title}}</a></small></p>
            
      </div>
    </div>
</div>
@endsection