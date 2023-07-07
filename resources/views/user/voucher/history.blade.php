@extends('layouts.user')

@section('title')
   @lang('Reedemed History')
@endsection

@section('breadcrumb')
@lang('Reedemed History')
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
                        <th>@lang('Reedemed at')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($history as $item)
                        <tr>
                          <td data-label="@lang('Voucher Code')">{{$item->code}}</td>
                          <td data-label="@lang('Amount')">{{numFormat($item->amount)}} {{$item->currency->code}}</td>
                        
                          <td data-label="@lang('Reedemed at')">{{dateFormat($item->updated_at)}}</td>
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
                    {{$history->links()}}
                </div>
            </div>
            
        </div>
    </div>
  </div>
@endsection