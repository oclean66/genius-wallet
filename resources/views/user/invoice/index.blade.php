@extends('layouts.user')

@section('title')
   @lang('Invoices')
@endsection

@section('breadcrumb')
    @lang('Invoices')
@endsection
@push('extra')
  <a href="{{route('user.invoice.create')}}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> @lang(' Create Invoice')</a>
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
                        <th>@lang('Invoice')</th>
                        <th>@lang('Invoice To')</th>
                        <th>@lang('Email')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Pay Status')</th>
                        <th>@lang('Publish Status')</th>
                        <th>@lang('Date')</th>
                        <th>@lang('Action')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($invoices as $item)
                        <tr>
                          <td>{{$item->number}}</td>
                          <td>{{$item->invoice_to}}</td>
                          <td>{{$item->email}}</td>
                          <td>{{amount($item->final_amount,$item->currency->type,2)}} {{$item->currency->code}}</td>
                          <td>
                            @if ($item->status != 2)
                             <label class="form-check form-switch">
                                <input class="form-check-input pay_status shadow-none" data-id="{{$item->id}}" type="checkbox" {{$item->payment_status == 1 ? 'checked':''}}>
                                <small class="form-check-label pay-status-{{$item->id}} badge {{$item->payment_status == 1 ? 'bg-success':'bg-secondary'}}">{{$item->payment_status == 1 ? 'Paid':'Unpaid'}}</small>
                              </label>
                              @else
                                @lang('N/A')
                              @endif
                          </td>
                          <td>
                              @if (($item->status == 1 || $item->status == 0) && $item->payment_status != 1)
                              <label class="form-check form-switch">
                                <input class="form-check-input status shadow-none" type="checkbox" data-id="{{$item->id}}" {{$item->status == 1 ? 'checked':''}}>
                                <small class="form-check-label status-text-{{$item->id}} badge {{$item->status == 1 ? 'bg-success':'bg-secondary'}}">{{$item->status == 1 ? 'Published':'Un-Published'}}</small>
                              </label>
                               @elseif($item->status == 2)
                                 <span class="badge bg-danger">@lang('cancelled')</span>
                                @else
                                <span class="badge bg-success">@lang('Published')</span>
                              @endif
                          </td>
                          <td>{{dateFormat($item->created_at,'d M Y')}}</td>
                          <td>
                              <a href="{{route('user.invoice.view',$item->number)}}" class="btn btn-dark btn-sm"><i class="fas fa-eye"></i></a>
                         
                              @if ($item->status == 0)
                                <a href="{{route('user.invoice.edit',$item->id)}}" class="btn btn-primary btn-sm edit-{{$item->id}}"><i class="fas fa-edit"></i></a>
                              @else
                              <a href="javascript:void(0)" class="btn btn-primary btn-sm disabled"><i class="fas fa-edit"></i></a>
                              @endif

                              <a href="javascript:void(0)" class="btn btn-secondary btn-sm copy" data-clipboard-text="{{route('invoice.view',encrypt($item->number))}}" title="@lang('Copy Invoice URL')"><i class="fas fa-copy"></i></a>
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
                <div class="mt-2 text-right">
                    {{$invoices->links()}}
                </div>
            </div>
        </div>
    </div>
  </div>
@endsection

@push('script')
   <script src="{{asset('assets/user/js/clipboard.min.js')}}"></script>
    <script>
        'use strict';
        $('.pay_status').on('change',function () { 
            var url = "{{route('user.invoice.pay.status')}}"
            var id = $(this).data('id')
            $.post(url,{id:id,_token:'{{csrf_token()}}'},function (res) { 
                if(res.paid){
                    toast('success',res.paid)
                    $('.pay-status-'+id).addClass('bg-success').text('Paid')
                    return false
                }
                if(res.unpaid){
                    toast('success',res.unpaid)
                    $('.pay-status-'+id).removeClass('bg-success').addClass('bg-secondary').text('Unpaid')
                    return false
                }
                if(res.error){
                    toast('error',res.error)
                    return false
                }
            })
        })
        $('.status').on('change',function () { 
            var url = "{{route('user.invoice.publish.status')}}"
            var id = $(this).data('id')
            $.post(url,{id:id,_token:'{{csrf_token()}}'},function (res) { 
                if(res.unpublish){
                    toast('success',res.unpublish)
                    $('.status-text-'+id).removeClass('bg-success').addClass('bg-secondary').text('Un-published')
                    $('.edit-'+id).removeClass('disabled')
                    return false
                }
                if(res.publish){
                    toast('success',res.publish)
                    $('.status-text-'+id).addClass('bg-success').text('Published')
                    $('.edit-'+id).addClass('disabled')
                    return false
                }
                if(res.error){
                    toast('error',res.error)
                    return false
                }
            })
        })

        var clipboard = new ClipboardJS('.copy');
        clipboard.on('success', function(e) {
           toast('success','Invoice URL Copied')
        });
    </script>
       
@endpush