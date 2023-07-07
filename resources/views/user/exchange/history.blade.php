@extends('layouts.user')

@section('title')
   @lang('Exchange History')
@endsection

@section('breadcrumb')
   @lang('Exchange History')
@endsection

@push('extra')
<form action="">
    <div class="input-group">
        <input class="form-control shadow-none text-dark" type="text" placeholder="@lang('Transaction id')" name="search" value="{{$search ?? ''}}">
        <button type="submit" class="input-group-text bg-primary text-white border-0"><i class="fas fa-search"></i></button>
    </div>
</form>
<a href="{{route('user.exchange.money')}}" class="btn btn-primary"><i class="fas fa-backward me-2"></i> @lang('Back')</a>
@endpush

@section('content')
<div class="container-xl">
    <div class="row row-deck row-cards">

        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                  <table class="table table-vcenter card-table table-striped">
                    <thead>
                      <tr>
                        <th>@lang('Transaction Id')</th>
                        <th>@lang('From Currency')</th>
                        <th>@lang('From Amount')</th>
                        <th>@lang('To Currency')</th>
                        <th>@lang('To Amount')</th>
                        <th>@lang('Charge')</th>
                        <th>@lang('Date')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($exchanges as $item)
                        <tr>
                          <td data-label="@lang('Transaction Id')">{{@$item->trnx}}</td>
                          <td data-label="@lang('From Currency')">{{@$item->fromCurr->code}}</td>
                          <td data-label="@lang('From Amount')">{{amount($item->from_amount,$item->fromCurr->type,2)}} {{@$item->fromCurr->code}}</td>
                          <td data-label="@lang('To Currency')">{{@$item->toCurr->code}}</td>
                          <td data-label="@lang('To Amount')">{{amount($item->to_amount,$item->toCurr->type,2)}} {{@$item->toCurr->code}}</td>
                          <td data-label="@lang('Charge')">{{amount($item->charge,$item->fromCurr->type,2)}} {{$item->fromCurr->code}}</td>
                          <td data-label="@lang('Date')">{{dateFormat($item->created_at)}}</td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center" colspan="12">@lang('No data found!')</td>
                        </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
                @if ($exchanges->hasPages())
                    <div class="card-footer">
                        {{$exchanges->links()}}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection