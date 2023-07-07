@extends('layouts.agent')

@section('title')
   @lang('Withdraw History')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header justify-content-between">
        <h1>@lang('Withdraw History')</h1>
        <form action="" method="get">
            <div class="input-group">
                <input type="text" name="search" value="{{$search ?? ''}}" class="form-control" placeholder="@lang('Transaction')">
                <div class="input-group-append">
                    <button class="input-group-text bg-primary text-white" type="submit" ><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('content')
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped table--responsive">
                        <thead>
                        <tr>
                            <th>@lang('Transaction ID')</th>
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
                                <td data-label="@lang('Amount')">{{amount($item->amount,$item->currency->type,2)}} {{$item->currency->code}}</td>
                                <td data-label="@lang('Charge')">{{amount($item->charge,$item->currency->type,2)}} {{$item->currency->code}}</td>
                                <td data-label="@lang('Total Amount')">{{amount($item->total_amount,$item->currency->type,2)}} {{$item->currency->code}}</td>
                                <td data-label="@lang('Method Name')">{{$item->method->name}}</td>
                                <td data-label="@lang('Status')">
                                    <div class="d-flex align-items-center justify-content-center">
                                        @if($item->status == 1)
                                           <span class="badge bg-success m-1">@lang('Accepted')</span>
                                        @elseif($item->status == 2)
                                            <span class="badge badge-danger m-1">@lang('Rejected')</span>
                                            <button class="btn btn-sm bg-dark text-white reason m-1" data-bs-toggle="modal" data-bs-target="#modal-team" data-reason="{{$item->reject_reason}}"><i class="fas fa-info"></i></button>
                                        @else
                                            <span class="badge badge-warning m-1">@lang('Pending')</span>

                                        @endif
                                    </div>
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
                        @if ($withdrawals->hasPages())
                            {{$withdrawals->links('merchant.partials.paginate')}}
                        @endif
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
              
            </div>
            <div class="modal-body">
              <div>
                <textarea class="form-control reject-reason" rows="5" disabled></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-dark ms-auto" data-dismiss="modal">@lang('Close')</button>
             
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