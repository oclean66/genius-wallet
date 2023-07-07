@extends('layouts.admin')

@section('title')
   @lang('Pending Agent Request')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header justify-content-between">
        <h1> @lang('Pending Agent Request')</h1>
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
                            <th>@lang('Name')</th>
                            <th>@lang('Email')</th>
                            <th>@lang('National ID NO. (NID)')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        @forelse ($agents as $key => $agent)
                            <tr>
                                <td data-label="@lang('Sl')">{{$key + $agents->firstItem()}}</td>
                    
                                 <td data-label="@lang('Name')">
                                   {{$agent->name}}
                                 </td>
                                 <td data-label="@lang('Email')">{{$agent->email}}</td>
                                  <td data-label="@lang('National ID No (NID)')">
                                    {{$agent->nid}}
                                 </td>
                                 <td data-label="@lang('Status')">
                                    @if($agent->status == 1)
                                        <span class="badge badge-success">@lang('Active')</span>
                                    @elseif($agent->status == 2)
                                         <span class="badge badge-warning">@lang('Pending')</span>
                                    @endif
                                 </td>
                                
                                 <td data-label="@lang('Action')">
                                     <a href="javascript:void(0)" class="btn btn-primary btn-sm details m-1" data-url="{{route('admin.agent.request.details',$agent->id)}}"> @lang('Details')</a>
                                     <a href="javascript:void(0)" class="btn btn-success btn-sm accept m-1" data-url="{{route('admin.agent.request.accept',$agent->id)}}"> @lang('Accept')</a>
                                     <a  href="javascript:void(0)" class="btn btn-danger btn-sm reject m-1" href="{{route('admin.agent.request.reject',$agent->id)}}"> @lang('Reject')</a>
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
            @if ($agents->hasPages())
                {{ $agents->links('admin.partials.paginate') }}
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="details" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Agent Details')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid agent-details">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="accept" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <form action="" method="post">
       @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Accept')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <p>@lang('Are you sure to Accept this agent request')?</p>
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
    <div class="modal-dialog" role="document">
       <form action="" method="post">
       @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Reject')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group col-md-12">
                        <p>@lang('Are you sure to reject this agent request')?</p>
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
        'use strict';
        $(document).ready(function() {
           $('.country').select2({
            dropdownParent: $('#addModal')
           });
        });

        $('.details').on('click',function () {
            var url = $(this).data('url') 
            $.get(url, null,
                function (data) {
                    $('#details').find('.agent-details').html(data)
                    $('#details').modal('show')
                }
            );
        })
        $('.accept').on('click',function () {
            var url = $(this).data('url') 
            $('#accept').find('form').attr('action',url)
            $('#accept').modal('show')  
        })
        $('.reject').on('click',function () {
            var url = $(this).data('url') 
            $('#reject').find('form').attr('action',url)
            $('#reject').modal('show')  
        })
    </script>
@endpush