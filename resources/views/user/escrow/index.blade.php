@extends('layouts.user')

@section('title')
   @lang('My escrows')
@endsection

@section('breadcrumb')
   @lang('My escrows')
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
                                <th>@lang('Recipient')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Charge')</th>
                                <th>@lang('Details')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($escrows as $item)
                            <tr>
                                <td data-label="@lang('Recipient')">{{$item->recipient->email}}</td>
                                <td data-label="@lang('Amount')">{{amount($item->amount,$item->currency->type,2)}} {{$item->currency->code}}</td>
                                <td data-label="@lang('Charge')">{{amount($item->charge,$item->currency->type,2)}} {{$item->currency->code}}</td>
                                <td data-label="@lang('Details')">{{Str::limit($item->description,80)}}</td>
                                <td data-label="@lang('Status')">
                                    @if ($item->status == 1)
                                        <span class="badge bg-success">@lang('Released')</span>
                                    @elseif ($item->status == 0)
                                        <span class="badge bg-warning">@lang('On hold')</span>
                                    @elseif ($item->status == 3)
                                        <span class="badge bg-info">@lang('Disputed')</span>
                                    @elseif ($item->status == 4)
                                        <span class="badge">@lang('Closed')</span>
                                    @endif
                                </td>
                                <td data-label="@lang('Date')">{{dateFormat($item->created_at)}}</td>
                                <td data-label="@lang('Action')">
                                
                                    @if ($item->status != 1 && $item->status != 4)
                                    <a href="javascript:void(0)" data-route="{{route('user.escrow.release',$item->id)}}" class="btn btn-primary btn-sm release">@lang('Release')</a>

                                      <a href="{{route('user.escrow.dispute',$item->id)}}" class="btn btn-warning btn-sm">@lang('Dispute')</a>
                                    @else
                                      @lang('N/A')
                                    @endif
                                </td>
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

        <div class="modal modal-blur fade" id="modal-success" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-primary"></div>
                <div class="modal-body text-center py-4">
                    <i  class="fas fa-info-circle fa-3x text-primary mb-2"></i>
                    <h3>@lang('Confirm Release?')</h3>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                        <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                            @lang('Cancel')
                            </a></div>
                        <div class="col">
                            <form action="" method="get">
                                <button type="submit" class="btn btn-primary w-100 confirm">
                                @lang('Confirm')
                                </button>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict';
        $('.release').on('click',function() { 
            $('#modal-success').find('form').attr('action',$(this).data('route'))
            $('#modal-success').modal('show')
        })
    </script>
@endpush