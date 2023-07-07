@extends('layouts.merchant')

@section('title')
   @lang('Api key')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header">
        <h1>@lang('Api key')</h1>
    </div>
</section>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card">
                <div class="card-header">
                    <h6>@lang('Business Api Key')</h6>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-3">
                            <h6 class="mt-2">@lang('Access Key :')</h6>
                        </div>
                        <div class="col-md-9">
                            <input class="form-control public" data-clipboard-text="{{@$cred->access_key}}" type="text" value="{{@$cred->access_key}}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3">
                            <h6 class="mt-2">@lang('Service Mode :')</h6>
                        </div>
                        <div class="col-md-9">
                            <select class="form-control mode">
                                <option value="0" {{$cred->mode == 0 ? 'selected':''}}>@lang('Test Mode')</option>
                                <option value="1" {{$cred->mode == 1 ? 'selected':''}}>@lang('Active Mode')</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mt-3 text-right">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmModal">@lang('Generate New')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="confirmModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{route('merchant.api.key.generate')}}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-body">
                     <h6>@lang('All of your previous api connection will be lost !! Are you sure to generate new api key?')</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Cancel')</button>
                        <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

 
@endsection

@push('script')
<script src="{{asset('assets/user/js/clipboard.min.js')}}"></script>
<script>
        $(function () {
            var public = new ClipboardJS('.public');
            public.on('success', function(e){
                 toast('success','@lang('Access key has been copied')')
            });

            $('.mode').on('change',function () { 
                $.get("{{route('merchant.api.service.mode')}}",function( res ) {
                    toast('success',res)
                })
            })
           
        });
</script>
@endpush