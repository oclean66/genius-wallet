@extends('layouts.user')

@section('title')
   @lang('Withdraw History')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header">
        <h1>@lang('Withdraw History')</h1>
    </div>
</section>
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
                            <th>@lang('Transaction')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Charge')</th>
                            <th>@lang('Total Amount')</th>
                            <th>@lang('Method Name')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Date')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($withdrawals as $item)
                            <tr>
                                <td data-label="@lang('Transaction')">{{$item->trx}}</td>
                                <td data-label="@lang('Amount')">{{numFormat($item->amount)}} {{$item->currency->code}}</td>
                                <td data-label="@lang('Charge')">{{numFormat($item->charge)}} {{$item->currency->code}}</td>
                                <td data-label="@lang('Total Amount')">{{numFormat($item->total_amount)}} {{$item->currency->code}}</td>
                                <td data-label="@lang('Method Name')">{{$item->method->name}}</td>
                                <td data-label="@lang('Status')">

                                    @if($item->status == 1)
                                        <span class="badge bg-success">@lang('Accepted')</span>
                                    @elseif($item->status == 2)
                                         <span class="badge bg-danger">@lang('Rejected')</span>
                                         <button class="badge bg-secondary reason" data-bs-toggle="modal" data-bs-target="#modal-team" data-reason="{{$item->reject_reason}}"><i class="fas fa-info"></i></button>
                                    @else
                                        <span class="badge bg-warning">@lang('Pending')</span>

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
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="modal-team" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">@lang('Reject Reasons')</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div>
                <textarea class="form-control reject-reason" rows="5" disabled></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn ms-auto" data-bs-dismiss="modal">Close</button>
             
            </div>
          </div>
        </div>
      </div>
@endsection

@push('script')
    <script>
        'use strict';
        $('.reason').on('click',function() { 
            $('#modal-team').find('.reject-reason').val($(this).data('reason'))
            $('#modal-team').modal('show')
        })
    </script>
@endpush