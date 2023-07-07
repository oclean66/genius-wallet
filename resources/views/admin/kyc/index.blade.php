@extends('layouts.admin')

@section('title')
   @lang('Manage KYC')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header">
        <h1>@lang('Manage KYC')</h1>
    </div>
</section>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-4">
           <a href="{{route('admin.manage.kyc.user','user')}}">
            <div class="wizard-steps">
                <div class="wizard-step wizard-step-primary">
                  <div class="wizard-step-icon">
                    <i class="fas fa-users-cog"></i>
                  </div>
                  <div class="wizard-step-label">
                   <h4> @lang('User KYC Form')</h4>
                  </div>
                </div>
             </div>
           </a>
        </div>
        <div class="col-md-4">
           <a href="{{route('admin.manage.kyc.user','merchant')}}">
            <div class="wizard-steps">
                <div class="wizard-step wizard-step-primary">
                  <div class="wizard-step-icon">
                    <i class="fas fa-fax"></i>
                  </div>
                  <div class="wizard-step-label">
                   <h4> @lang('Merchant KYC Form')</h4>
                  </div>
                </div>
             </div>
           </a>
        </div>
       
    </div>
@endsection