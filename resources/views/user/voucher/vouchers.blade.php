@extends('layouts.user')

@section('title')
   @lang('My vouchers')
@endsection

@section('breadcrumb')
   @lang('My vouchers')  
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
                            <th>@lang('Voucher Code')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Date')</th>
                          </tr>
                        </thead>
                        <tbody>
                          @forelse ($vouchers as $item)
                            <tr>
                              <td data-label="@lang('Voucher Code')">{{$item->code}}</td>
                              <td data-label="@lang('Amount')">{{numFormat($item->amount)}} {{$item->currency->code}}</td>
                              <td data-label="@lang('Status')">
                                @if ($item->status == 0)
                                   <span class="badge bg-secondary">@lang('unused')</span>
                                @elseif ($item->status == 1)
                                    <span class="badge bg-success">@lang('used')</span>
                                @endif
                              </td>
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
                    <div class="mt-2 text-right">
                        {{$vouchers->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection