@extends('layouts.admin')

@section('title')
   @lang('Currency')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header d-flex flex-wrap justify-content-between">
      <h1 class="mb-1 mr-auto">@lang('Manage Currency')</h1>
      <div class="d-flex flex-wrap ">

            <a href="javascript:void(0)" data-toggle="modal" data-target="#currency_api" class="btn btn-primary mb-1 mr-3"><i class="fas fa-coins"></i> @lang('Currency rate API')</a>

            @if (access('add currency'))
            <a href="{{route('admin.currency.add')}}" class="btn btn-primary mb-1 mr-3"><i class="fas fa-plus"></i> @lang('Add New')</a>
            @endif
            <form action="">
              <div class="input-group has_append">
                <input type="text" class="form-control" placeholder="@lang('Currency Name/Code')" name="search" value="{{$search ?? ''}}"/>
                <div class="input-group-append">
                    <button class="input-group-text bg-primary border-0"><i class="fas fa-search text-white"></i></button>
                </div>
              </div>
            </form>

          </div>
    </div>
</section>
@endsection

@section('content')
<div class="row">
    @foreach ($currencies as $curr)
    <div class="col-md-6 col-lg-6 col-xl-3 currency--card">
      <div class="card card-primary">
        <div class="card-header {{$curr->default == 1 ? 'default' : ''}}">
          <h4><i class="fas fa-coins"></i> {{$curr->curr_name}}</h4>
        </div>
        <div class="card-body">
          <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between">@lang('Currency Symbol :')
              <span class="font-weight-bold">{{$curr->symbol}}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">@lang('Currency Code :')
              <span class="font-weight-bold">{{$curr->code}}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">@lang('Currency Type :')
              <span class="font-weight-bold">{{$curr->type == 1 ? 'Fiat':'Crypto'}}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">@lang('Rate 1 USD :')
              <span class="font-weight-bold">{{amount($curr->rate,$curr->type,3)}} {{$curr->code}}</span>
            </li>
          </ul>
          @if (access('edit currency'))
          <a href="{{route('admin.currency.edit',$curr->id)}}" class="btn btn-primary btn-block"><i class="fas fa-edit"></i> @lang('Edit Currency')</a>  
          @endif
        </div>
      </div>
    </div>
    @endforeach
</div>

<div class="modal fade" id="currency_api" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
     <form action="{{route('admin.currency.api.update')}}" method="POST">
      @csrf
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">@lang('Currency API Key')</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
              </div>
              <div class="modal-body">
                  <div class="form-group">
                      <div class="card border-left border-primary">
                          <div class="card-body">
                              <span class="font-weight-bold">@lang('Note') :  </span> <code class="text-warning">@lang('If you want to update your currency rates autometically, You must set the API key. You will find API key in the mentioned API provider. And you have to set up the cron job to your server. Cron job URL is : '.url('/currency-rate'))</code>
                          </div>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="font-weight-bold">@lang('Fiat Currency API Key')</label>
                      ( <small>@lang('For the api key please visit :') 
                          <a target="_blank" class="text-info" href="https://currencylayer.com/">@lang('Currency Layer')</a>
                       </small> )
                      <input class="form-control" type="text" name="fiat_access_key" placeholder="@lang('Fiat Currency API Key')" required value="{{$gs->fiat_access_key}}">
                  </div>

                  <div class="form-group">
                      <label class="font-weight-bold">@lang('Crypto Currency API Key')</label>
                      ( <small>@lang('For the api key please visit :')
                          <a target="_blank" class="text-info" href="https://coinlayer.com/">@lang('Coin Layer')</a>
                      </small> )
                      <input class="form-control" type="text" name="crypto_access_key" placeholder="@lang('Crypto Currency Rate Api Key')" required value="{{$gs->crypto_access_key}}">
                  </div>
                
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
                  <button type="submit" class="btn btn-primary">@lang('Update')</button>
              </div>
          </div>
     </form>
  </div>
</div>
@endsection

@push('style')
    <style>
        .default{
          background-color: #6777ef26!important;
        }
    </style>
@endpush