@extends('layouts.admin')

@section('title')
   @lang('Manage Agent')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header justify-content-between">
        <h1> @lang('Manage Agent')</h1>
        <form action="">
            <div class="row">
                <div class="col-md-6">
                    <select class="form-control" id="" onChange="window.location.href=this.value">
                        <option value="{{url('admin/agent/list'.'?status=all')}}" {{request('status') == 'all'?'selected':''}}>@lang('All')</option>
                        <option value="{{url('admin/agent/list'.'?status=active')}}" {{request('status') == 'active'?'selected':''}}>@lang('Active')</option>
                        <option value="{{url('admin/agent/list'.'?status=banned')}}" {{request('status') == 'banned'?'selected':''}}>@lang('Banned')</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="input-group has_append ">
                      <input type="text" class="form-control" placeholder="@lang('email')" name="search" value="{{$search ?? ''}}"/>
                      <div class="input-group-append">
                          <button class="input-group-text bg-primary border-0"><i class="fas fa-search text-white"></i></button>
                      </div>
                    </div>
                </div>
                
            </div>
          </form>
    </div>
</section>
@endsection

@section('content')
        
<div class="row">
    <div class="col-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary" data-toggle="modal" data-target="#addModal"><i class="fas fa-plus"></i> @lang('Add Agent')</button>
            </div>
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
                                        <span class="badge badge-success">@lang('active')</span>
                                    @elseif($agent->status == 0)
                                         <span class="badge badge-danger">@lang('banned')</span>
                                    @endif
                                 </td>
                                
                                 <td data-label="@lang('Action')">
                                     <a class="btn btn-primary btn-sm details" href="{{route('admin.agent.details',$agent->id)}}"><i class="fas fa-arrow-right"></i> @lang('Details')</a>
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

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{route('admin.agent.add')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add New Agent')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>@lang('Name')</label>
                            <input class="form-control" type="text" name="name" required value="{{old('name')}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>@lang('Email')</label>
                            <input class="form-control" type="email" name="email" required value="{{old('email')}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="">@lang('Select Country')</label>
                            <select name="country" class="form-control country">
                                @foreach ($countries as $country)
                                 <option value="{{$country->name}}">{{$country->name}}</option>
                                @endforeach
                            </select>
                       </div>
                        <div class="form-group col-md-6">
                            <label>@lang('Contact No (with country dial code)')</label>
                            <input class="form-control" type="text" name="phone" required value="{{old('phone')}}">
                        </div>
                       
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>@lang('Password')</label>
                            <input class="form-control" type="password" name="password" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>@lang('Confirm Password')</label>
                            <input class="form-control" type="password" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>@lang('National ID Number')</label>
                        <input class="form-control" type="text" name="nid" value="{{old('nid')}}" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('National ID Photo')</label>
                        <input class="form-control" type="file" name="nid_photo" required>
                    </div>

                    <div class="form-group">
                        <label>@lang('Name of Business Institution')</label>
                        <input class="form-control" type="text" name="business_name" value="{{old('business_name')}}" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('Address of Business Institution')</label>
                        <input class="form-control" type="text" name="business_address" value="{{old('business_address')}}" required>
                    </div>
                
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Submit')</button>
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
    </script>
@endpush