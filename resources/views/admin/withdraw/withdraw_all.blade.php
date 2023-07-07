@extends('layouts.admin')
@section('title')
    @if (request()->routeIs('admin.withdraw.pending'))
         @lang('Pending Withdraws')
    @elseif (request()->routeIs('admin.withdraw.accepted'))
          @lang('Accepted Withdraws')
    @else
          @lang('Rejected Withdraws')
    @endif
@endsection
@section('breadcrumb')
 <section class="section">
        <div class="section-header">
            @if (request()->routeIs('admin.withdraw.pending'))
            <h1>@lang('Pending Withdraws')</h1>
            @elseif (request()->routeIs('admin.withdraw.accepted'))
            <h1>@lang('Accepted Withdraws')</h1>
            @else
                <h1>@lang('Rejected Withdraws')</h1>
            @endif
        </div>
</section>
@endsection
@section('content')

    <div class="row">

        <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
               
                <div class="card-body text-center">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th>@lang('Sl')</th>
                                <th>@lang('User')</th>
                                <th>@lang('Withdraw Amount')</th>
                                <th>@lang('Charge')</th>
                                <th>@lang('status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            @forelse ($withdrawlogs as $key => $withdrawlog)
                                <tr>
                                    <td data-label="@lang('Sl')">{{$key + $withdrawlogs->firstItem()}}</td>
                                
                                    <td data-label="@lang('User')">
                                        @if($withdrawlog->user)
                                        {{$withdrawlog->user->email.'(user)'}}
                                        @elseif($withdrawlog->merchant)
                                        {{$withdrawlog->merchant->email.'(merchant)'}}
                                        @elseif(@$withdrawlog->agent)
                                        {{@$withdrawlog->agent->email.'(agent)'}}
                                        @endif
                                    </td>
                                   
                                    <td data-label="@lang('Withdraw Amount')">{{ __(amount($withdrawlog->amount,$withdrawlog->currency->type,2).' '.$withdrawlog->currency->code) }}</td>
                               
                                    <td data-label="@lang('Charge')">
                                       {{amount($withdrawlog->charge,$withdrawlog->currency->type,2)}}
                                    </td> 
                                

                                    <td data-label="@lang('status')">

                                        @if($withdrawlog->status == 1)
                                            <span class="badge badge-success">@lang('Accepted')</span>
                                        @elseif($withdrawlog->status == 2)
                                             <span class="badge badge-danger">@lang('Rejected')</span>
                                        @else
                                            <span class="badge badge-warning">@lang('Pending')</span>
                                        @endif
                                    </td>

                                    <td data-label="@lang('Action')">
                                    
                                        <div class="d-flex flex-wrap align-items-center justify-content-end justify-content-lg-center">
                                            <button class="btn btn-info details m-1 btn-sm" data-user_data="{{$withdrawlog->user_data}}" data-transaction="{{$withdrawlog->trx}}" data-provider="{{$withdrawlog->userDetails()->email}}" data-method_name="{{$withdrawlog->method->name}}" data-date="{{ __($withdrawlog->created_at->format('d F Y')) }}">@lang('Details')</button>

                                            @if($withdrawlog->status == 0)
                                                <button class="btn btn-primary accept m-1 btn-sm" data-url="{{route('admin.withdraw.accept', $withdrawlog)}}" >@lang('Accept')</button>
                                            
                                                <button class="btn btn-danger reject m-1 btn-sm" data-url="{{route('admin.withdraw.reject',$withdrawlog)}}">@lang('Reject')</button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                             @empty

                                <tr>
                                    <td class="text-center" colspan="100%">@lang('No Data Found')</td>
                                </tr>

                            @endforelse
                        </table>
                    </div>
                </div>
                @if ($withdrawlogs->hasPages())
                <div class="card-footer">
                    {{ $withdrawlogs->links('admin.partials.paginate') }}
                </div>
                @endif
            </div>
        </div>
    </div>


    
    <!-- Modal -->
    <div class="modal fade" id="details" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
           
            <div class="modal-content">
                    <div class="modal-header">
                            <h5 class="modal-title">@lang('Withdraw Details')</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                    </div>
                <div class="modal-body">
                    <div class="container-fluid withdraw-details">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
                    
                </div>
            </div>
           
        </div>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="accept" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">

           <form action="" method="post">
           @csrf
            <div class="modal-content">
                    <div class="modal-header">
                            <h5 class="modal-title">@lang('Withdraw Accept')</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <p>@lang('Are you sure to Accept this withdraw request')?</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark btn-sm" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary btn-sm" >@lang('Accept')</button>
                    
                </div>
            </div>
           </form>
        </div>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="reject" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">

           <form action="" method="post">
           @csrf
            <div class="modal-content">
                    <div class="modal-header">
                            <h5 class="modal-title">@lang('Withdraw Reject')</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="form-group col-md-12">

                            <label for="">@lang('Reason Of Reject')</label>
                            <textarea name="reason_of_reject" id="" cols="30" rows="10" class="form-control"> </textarea>
                        
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-danger" >@lang('Reject')</button>
                    
                </div>
            </div>
           </form>
        </div>
    </div>
    


@endsection


@push('script')

    <script>
    
        $(function(){
            'use strict';

            $('.details').on('click',function(){
                const modal = $('#details');

                let html = `
                
                    <ul class="list-group">
                           
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Transaction Id')
                                <span>${$(this).data('transaction')}</span>
                            </li>  
                            
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('User Name')
                                <span>${$(this).data('provider')}</span>
                            </li> 
                            
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Withdraw Method')
                                <span>${$(this).data('method_name')}</span>
                            </li> 
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Withdraw Date')
                                <span>${$(this).data('date')}</span>
                            </li> 
                        </ul>
                        <hr>
                        <h6>@lang('User Data For Withdraw : ')</h6>
                        <textarea class="form-control" rows="5" disabled>${$(this).data('user_data')}</textarea>
                
                
                `;

                modal.find('.withdraw-details').html(html);
                modal.modal('show');
            })

            $('.accept').on('click',function(){
                 const modal = $('#accept');

                 modal.find('form').attr('action', $(this).data('url'));
                 modal.modal('show');
            })
            
            $('.reject').on('click',function(){
                 const modal = $('#reject');

                 modal.find('form').attr('action', $(this).data('url'));
                 modal.modal('show');
            })

        })
    
    
    </script>
    
@endpush

