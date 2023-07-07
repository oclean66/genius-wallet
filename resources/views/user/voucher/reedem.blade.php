@extends('layouts.user')

@section('title')
    @lang('Reedem Voucher')
@endsection

@section('breadcrumb')
   @lang('Reedem Voucher')
@endsection

@section('content')
<div class="container-xl">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
            <div class="card-body">
                <form action="" id="form" method="post">
                  @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-label ">@lang('Voucher Code : ')</div>
                            <input type="text" name="code" id="code" class="form-control shadow-none mb-2"  required>
                        </div>
                      
                        <div class="col-md-12 mb-3">
                            <div class="form-label">&nbsp;</div>
                            <a href="#" class="btn btn-primary w-100 create">
                                @lang('Reedem')
                            </a>
                        </div>


                        <div class="modal modal-blur fade" id="modal-success" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                <div class="modal-status bg-primary"></div>
                                <div class="modal-body text-center py-4">
                                <i  class="fas fa-info-circle fa-3x text-primary mb-2"></i>
                                <h3>@lang('Confirm Reedem')</h3>
                               
                                </div>
                                <div class="modal-footer">
                                <div class="w-100">
                                    <div class="row">
                                    <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                                        @lang('Cancel')
                                        </a></div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary w-100 confirm">
                                           @lang('Confirm')
                                        </button>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>

    <div class="row row-deck row-cards mt-3">
        <div class="col-md-12">
            <h2> @lang('Recent Reedemed Vouchers')</h2>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                  <table class="table table-vcenter card-table table-striped">
                    <thead>
                      <tr>
                        <th>@lang('Voucher Code')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Reedemed at')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($recentReedemed as $item)
                        <tr>
                          <td>{{$item->code}}</td>
                          <td>{{numFormat($item->amount)}} {{$item->currency->code}}</td>
                        
                          <td>{{dateFormat($item->updated_at)}}</td>
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
</div>

@endsection

@push('script')
    <script>
        'use strict';
       
        $('.create').on('click',function () { 
            if($('#code').val() == ''){
              toast('error','@lang('Please provide the voucher code first')')
              return false
            } 
            $('#modal-success').modal('show')
        })
    </script>
@endpush