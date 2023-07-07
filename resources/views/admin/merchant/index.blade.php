@extends('layouts.admin')

@section('title')
   @lang('Manage Merchant')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header justify-content-between">
        <h1> @lang('Manage Merchant')</h1>
        <form action="">
            <div class="row">
                <div class="col-md-6">
                    <select class="form-control" id="" onChange="window.location.href=this.value">
                        <option value="{{url('admin/manage-merchants/'.'?status=all')}}" {{request('status') == 'all'?'selected':''}}>@lang('All')</option>
                        <option value="{{url('admin/manage-merchants/'.'?status=active')}}" {{request('status') == 'active'?'selected':''}}>@lang('Active')</option>
                        <option value="{{url('admin/manage-merchants/'.'?status=banned')}}" {{request('status') == 'banned'?'selected':''}}>@lang('Banned')</option>
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
   
            <div class="card-body text-center">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>@lang('Sl')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Email')</th>
                            <th>@lang('Country')</th>
                              <th>@lang('Email Verified')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        @forelse ($users as $key => $user)
                            <tr>
                                <td data-label="@lang('Sl')">{{$key + $users->firstItem()}}</td>
                    
                                 <td data-label="@lang('Name')">
                                   {{$user->name}}
                                 </td>
                                 <td data-label="@lang('Email')">{{$user->email}}</td>
                                 <td data-label="@lang('Country')">{{$user->country}}</td>
                                  <td data-label="@lang('Email Verified')">
                                    @if($user->email_verified == 1)
                                        <span class="badge badge-success">@lang('YES')</span>
                                    @elseif($user->email_verified == 0)
                                         <span class="badge badge-danger">@lang('NO')</span>
                                    @endif
                                 </td>
                                 <td data-label="@lang('Status')">
                                    @if($user->status == 1)
                                        <span class="badge badge-success">@lang('active')</span>
                                    @elseif($user->status == 2)
                                         <span class="badge badge-danger">@lang('banned')</span>
                                    @endif
                                 </td>
                                 @if (access('edit merchant'))
                                 <td data-label="@lang('Action')">
                                     <a class="btn btn-primary details" href="{{route('admin.merchant.details',$user->id)}}">@lang('Details')</a>
                                 </td>
                                 @endif
                               
                            </tr>
                         @empty

                            <tr>
                                <td class="text-center" colspan="100%">@lang('No Data Found')</td>
                            </tr>

                        @endforelse
                    </table>
                </div>
            </div>
            @if ($users->hasPages())
                {{ $users->links('admin.partials.paginate') }}
            @endif
        </div>
    </div>
</div>
@endsection