@extends('layouts.admin')

@section('title')
   @lang('Agent Details')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header justify-content-between">
        <h1>@lang('Agent Details')</h1>
        <a href="{{route('admin.agent.list')}}" class="btn btn-primary"><i class="fas fa-backward"></i> @lang('Back')</a>
    </div>
</section>
@endsection

@section('content')

    <div class="row justify-content-center">
        <div class="col-xxl-8 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>@lang('Agent Wallets')</h6>
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addWallet"><i class="fas fa-plus"></i> @lang('Add Wallet')</button>
                    </div>
                    <hr>
                    <div class="row justify-content-center">
                        @forelse ($user->wallets as $item)
                        <div class="col-xxl-6 col-lg-12 col-md-6">
                            <a href="javascript:void(0)" class="wallet"  data-code="{{$item->currency->code}}" data-id="{{$item->id}}" data-toggle="tooltip" title="@lang('Click to Add or Subtract Balance')">
                                <div class="card card-statistic-1 bg-sec">
                                    <div class="card-icon bg-primary text-white">
                                        {{$item->currency->code}}
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header ">
                                            <h4 class="text-dark">@lang($item->currency->curr_name)</h4>
                                        </div>
                                        <div class="card-body">
                                            {{amount($item->balance,$item->currency->type,3)}} {{$item->currency->code}}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                      @empty
                        <p>@lang('No wallet found')</p>
                      @endforelse
                    </div>

                    <h6 class="mt-3">@lang('Agent details')</h6>
                    <hr>
                    <form action="{{route('admin.agent.profile.update',$user->id)}}" method="POST" class="row">
                        @csrf
                        <div class="form-group col-md-6">
                            <label>@lang('Name')</label>
                            <input class="form-control" type="text" name="name" value="{{$user->name}}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>@lang('Email')</label>
                            <input class="form-control" type="email" name="email" value="{{$user->email}}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>@lang('Phone')</label>
                            <input class="form-control" type="text" name="phone" value="{{$user->phone}}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>@lang('Country')</label>
                            <Select class="form-control js-example-basic-single" name="country" required>
                                @foreach ($countries as $item)
                                <option value="{{$item->name}}" {{$user->country == $item->name ? 'selected':''}}>{{$item->name}}</option>
                                @endforeach
                            </Select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>@lang('City')</label>
                            <input class="form-control" type="text" name="city" value="{{$user->city}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>@lang('Zip')</label>
                            <input class="form-control" type="text" name="zip" value="{{$user->zip}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>@lang('Address')</label>
                            <input class="form-control" type="text" name="address" value="{{$user->address}}">
                        </div>
                      
                        <div class="form-group col-md-6">
                            <label>@lang('National ID Number')</label>
                            <input class="form-control" type="text" name="nid" value="{{$user->nid}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>@lang('Name of Business Institution')</label>
                            <input class="form-control" type="text" name="business_name" value="{{$user->business_name}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>@lang('Address of Business Institution')</label>
                            <input class="form-control" type="text" name="business_address" value="{{$user->business_address}}">
                        </div>
                       
                        <div class="form-group col-md-6">
                            <label class="cswitch d-flex justify-content-between align-items-center border p-2">
                                <input class="cswitch--input" name="status" type="checkbox" {{$user->status == 1 ? 'checked':''}} /><span class="cswitch--trigger wrapper"></span>
                                <span class="cswitch--label font-weight-bold">@lang('Status')</span>
                            </label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="cswitch d-flex justify-content-between align-items-center border p-2">
                                <input class="cswitch--input update" name="email_verified" type="checkbox" {{$user->email_verified == 1 ? 'checked':''}} /><span class="cswitch--trigger wrapper"></span>
                                <span class="cswitch--label font-weight-bold">@lang('Email Verified')</span>
                            </label>
                        </div>
                        
                        @if (access('update user'))
                        <div class="form-group col-md-12 text-right">
                           <button type="submit" class="btn btn-primary btn-lg">@lang('Submit')</button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-lg-6 col-md-8">
            <div class="card">
                <div class="card-body">
                        <label class="font-weight-bold">@lang('Profile Picture')</label>
                        <div id="image-preview" class="image-preview u_details w-100"
                        style="background-image:url({{getPhoto($user->photo)}});">
                    </div>
                </div>
            </div>
            <a href="{{getPhoto($user->nid_photo)}}" data-lightbox="{{$user->nid_photo}}">
                <div class="card">
                    <div class="card-body">
                            <label class="font-weight-bold">@lang('NID Photo')</label>
                            <div id="image-preview" class="image-preview  w-100"
                            style="background-image:url({{getPhoto($user->nid_photo)}});">
                        </div>
                    </div>
                </div>
            </a>
            <div class="card">
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item active"><h5>@lang('Information')</h5></li>
                        
                        <li class="list-group-item d-flex justify-content-between">@lang('Login to Agent') <span><a target="_blank" href="{{route('admin.agent.login',$user->id)}}" class="btn btn-dark btn-sm">@lang('Login')</a></span></li>
                     
                        <li class="list-group-item d-flex justify-content-between">@lang('Agent Login Info') <span><a href="{{route('admin.agent.login.info',$user->id)}}" class="btn btn-dark btn-sm">@lang('View')</a></span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->

    <div class="modal fade" id="balanceModal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{route('admin.agent.balance.modify')}}" method="post">
                @csrf
                <input type="hidden" name="wallet_id">
                <input type="hidden" name="user_id" value="{{$user->id}}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Add/Subract Balance -- ') <span class="code"></span></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                       <div class="form-group">
                           <label>@lang('Amount')</label>
                           <input class="form-control" type="text" name="amount" required>
                       </div>
                       <div class="form-group">
                           <label>@lang('Type')</label>
                          <select name="type" id="" class="form-control">
                              <option value="1">@lang('Add Balance')</option>
                              <option value="2">@lang('Subtract Balance')</option>
                          </select>
                       </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="modal fade" id="addWallet" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{route('admin.agent.add.wallet')}}" method="post">
                @csrf
                <input type="hidden" name="agent_id" value="{{$user->id}}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Add wallet') <span class="code"></span></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Currency')</label>
                            <Select class="form-control js-wallet-basic-single" name="currency" required>
                                @foreach ($currencies as $item)
                                  <option value="{{$item->id}}">{{$item->code}}</option>
                                @endforeach
                            </Select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{asset('assets/admin/js/lightbox.min.js')}}"></script>
    <script>
        'use strict';
        $.uploadPreview({
            input_field: "#image-upload", // Default: .image-upload
            preview_box: "#image-preview", // Default: .image-preview
            label_field: "#image-label", // Default: .image-label
            label_default: "@lang('Choose File')", // Default: Choose File
            label_selected: "@lang('Update Image')", // Default: Change File
            no_label: false, // Default: false
            success_callback: null // Default: null
        });

        $('.wallet').on('click',function () { 
            $('#balanceModal').find('input[name=wallet_id]').val($(this).data('id'))
            $('#balanceModal').find('.code').text($(this).data('code'))
            $('#balanceModal').modal('show')
        })

        $(document).ready(function() {
           $('.js-example-basic-single').select2();
           $('.js-wallet-basic-single').select2();
        });
    </script>
@endpush

@push('style')
    <link rel="stylesheet" href="{{asset('assets/admin/css/lightbox.min.css')}}">
    <style>
        .bg-sec{
            background-color: #cdd3d83c
        }
        .u_details{
            height: 370px!important;
        }
    </style>
@endpush
