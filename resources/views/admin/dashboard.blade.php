@extends('layouts.admin')
@section('title')
    @lang('Admin Dashboard')
@endsection
@section('breadcrumb')
<section class="section">
    <div class="section-header">
        <h1>@lang('Dashboard')</h1>
    </div>
</section>
@endsection
@section('content')
    
    @if (access('dashboard info'))
    <div class="row">
       <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
           <div class="card card-statistic-1">
               <div class="card-icon bg-primary">
                   <i class="far fa-user"></i>
               </div>
               <div class="card-wrap">
                   <div class="card-header">
                       <h4>@lang('Total User')</h4>
                   </div>
                   <div class="card-body">
                      {{$totalUser}}
                   </div>
               </div>
           </div>
       </div>
       <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
           <div class="card card-statistic-1">
               <div class="card-icon bg-primary">
                   <i class="far fa-user"></i>
               </div>
               <div class="card-wrap">
                   <div class="card-header">
                       <h4>@lang('Total Merchant')</h4>
                   </div>
                   <div class="card-body">
                      {{$totalMerchant}}
                   </div>
               </div>
           </div>
       </div>
       <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
           <div class="card card-statistic-1">
               <div class="card-icon bg-primary">
                <i class="fas fa-coins"></i>
               </div>
               <div class="card-wrap">
                   <div class="card-header">
                       <h4>@lang('Total Currency')</h4>
                   </div>
                   <div class="card-body">
                      {{$totalCurrency}}
                   </div>
               </div>
           </div>
       </div>
       <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
           <div class="card card-statistic-1">
               <div class="card-icon bg-primary">
                <i class="fas fa-globe"></i>
               </div>
               <div class="card-wrap">
                   <div class="card-header">
                       <h4>@lang('Total Country')</h4>
                   </div>
                   <div class="card-body">
                      {{$totalCountry}}
                   </div>
               </div>
           </div>
       </div>
       <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
           <div class="card card-statistic-1">
               <div class="card-icon bg-primary">
                <i class="fas fa-user-tag"></i>
               </div>
               <div class="card-wrap">
                   <div class="card-header">
                       <h4>@lang('Total Role')</h4>
                   </div>
                   <div class="card-body">
                      {{$totalRole}}
                   </div>
               </div>
           </div>
       </div>
       <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
           <div class="card card-statistic-1">
               <div class="card-icon bg-primary">
                <i class="far fa-user"></i>
               </div>
               <div class="card-wrap">
                   <div class="card-header">
                       <h4>@lang('Total Staff')</h4>
                   </div>
                   <div class="card-body">
                      {{$totalStaff}}
                   </div>
               </div>
           </div>
       </div>
       <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
           <div class="card card-statistic-1">
               <div class="card-icon bg-primary">
                 <i class="fas fa-file-invoice-dollar"></i>
               </div>
               <div class="card-wrap">
                   <div class="card-header">
                       <h4>@lang('Total Profit')</h4>
                   </div>
                   <div class="card-body">
                      {{$totalProfit}}  {{$gs->curr_code}} <sup class="text-danger">*</sup>
                   </div>
               </div>
           </div>
       </div>
       <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
           <div class="card card-statistic-1">
               <div class="card-icon bg-primary">
                 <i class="fas fa-file-invoice-dollar"></i>
               </div>
               <div class="card-wrap">
                   <div class="card-header">
                       <h4>@lang('Default Currency')</h4>
                   </div>
                   <div class="card-body">
                    {{$gs->curr_code}} <sup class="text-danger">*</sup>
                   </div>
               </div>
           </div>
       </div>
   </div>

    <div class="row">
        <div class="col-sm-6 col-12">
            <div class="card card-statistic-2">
                <div class="card-icon shadow-primary bg-success text-white">
                    {{$gs->curr_sym}}
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                       <h4>@lang('Total Deposit')</h4>
                    </div>
                    <div class="card-body">
                        {{$gs->curr_sym}}{{$totalDeposit}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="card card-statistic-2">
            <div class="card-icon shadow-primary bg-primary text-white">
                {{$gs->curr_sym}}
            </div>
            <div class="card-wrap">
                <div class="card-header">
                <h4>@lang('Total Withdraw')</h4>
                </div>
                <div class="card-body">
                    {{$gs->curr_sym}}{{$totalWithdraw}}
                </div>
            </div>
            </div>
        </div>
    </div>
   @endif

   <div class="row">
       <div class="col-12 col-md-6 col-lg-6">
           <div class="card">
               <div class="card-header">
                   <h4>@lang('Recent Registered Users')</h4>
               </div>
               <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Email')</th>
                            <th>@lang('Country')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        @forelse ($recentUsers as $key => $user)
                            <tr>
                                 <td data-label="@lang('Name')">
                                   {{$user->name}}
                                 </td>
                                 <td data-label="@lang('Email')">{{$user->email}}</td>
                                 <td data-label="@lang('Country')">{{$user->country}}</td>
                                 <td data-label="@lang('Status')">
                                    @if($user->status == 1)
                                        <span class="badge badge-success">@lang('active')</span>
                                    @elseif($user->status == 2)
                                         <span class="badge badge-danger">@lang('banned')</span>
                                    @endif
                                 </td>
                                 @if (access('edit user'))
                                 <td data-label="@lang('Action')">
                                     <a class="btn btn-primary details" href="{{route('admin.user.details',$user->id)}}">@lang('Details')</a>
                                 </td>
                                 @else
                                 <td data-label="@lang('Action')">
                                   N/A
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
           </div>
       </div>

       <div class="col-12 col-md-6 col-lg-6">
           <div class="card">
               <div class="card-header">
                   <h4>@lang('Recent Registered Merchants')</h4>
               </div>
               <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Email')</th>
                            <th>@lang('Country')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        @forelse ($recentMerchants as $key => $user)
                            <tr>
                             
                                 <td data-label="@lang('Name')">
                                   {{$user->name}}
                                 </td>
                                 <td data-label="@lang('Email')">{{$user->email}}</td>
                                 <td data-label="@lang('Country')">{{$user->country}}</td>
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
                                 @else
                                 <td data-label="@lang('Action')">
                                   N/A
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
           </div>
       </div>

   </div>

@endsection
