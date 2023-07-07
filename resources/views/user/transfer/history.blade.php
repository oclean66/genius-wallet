@extends('layouts.user')

@section('title')
   @lang('Transfer Money History')
@endsection

@section('breadcrumb')
   @lang('Transfer Money History')
@endsection

@push('extra')
<form action="">
    <div class="input-group">
        <input class="form-control shadow-none text-dark" type="text" placeholder="@lang('Transaction id')" name="search" value="{{$search ?? ''}}">
        <button type="submit" class="input-group-text bg-primary text-white border-0"><i class="fas fa-search"></i></button>
    </div>
</form>
<a href="{{route('user.transfer.money')}}" class="btn btn-primary"><i class="fas fa-backward me-2"></i> @lang('Back')</a>
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
                        <th>@lang('Transaction')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Details')</th>
                        <th>@lang('Date')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($transfers as $item)
                        <tr>
                          <td data-label="@lang('Transaction')">{{$item->trnx}}</td>
                          <td data-label="@lang('Amount')">{{numFormat($item->amount)}} {{$item->currency->code}}</td>
                          <td data-label="@lang('Details')">{{$item->details}}</td>
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
                @if ($transfers->hasPages())
                    <div class="card-footer">
                        {{$transfers->links()}}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection