@extends('layouts.user')

@section('title')
   @lang('Deposit History')
@endsection

@section('breadcrumb')
    @lang('Deposit History')
@endsection

@section('content')
<div class="container-xl">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                  <table class="table table-vcenter card-table table-striped">
                    <thead>
                      <tr>
                        <th>@lang('Amount')</th>
                        <th>@lang('Charge')</th>
                        <th>@lang('Method')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Date')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($deposits as $item)
           
                        <tr>
                          <td data-label="@lang('Amount')">{{amount($item->amount,$item->currency->type,2)}} {{$item->currency->code}}</td>
                          <td data-label="@lang('Charge')">{{amount($item->charge,$item->currency->type,2)}} {{$item->currency->code}}</td>
                          <td data-label="@lang('Method')">{{$item->gateway->name}}</td>
                          <td data-label="@lang('Status')"><span class="badge {{$item->status == 'completed' ? 'bg-success':'bg-warning'}}">{{$item->status}}</span></td>
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
            </div>
        </div>
    </div>
</div>
@endsection