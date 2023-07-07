@extends('layouts.admin')

@section('title')
   @lang(ucfirst($section->name).' Section')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header d-flex justify-content-between">
        <h1>@lang(ucfirst($section->name).' Section')</h1>
        <a href="{{route('admin.frontend.index')}}" class="btn btn-primary"><i class="fa fa-backward"></i> @lang('Back')</a>
    </div>
</section>
@endsection

@section('content')
    <div class="row">
        @if ($section->content)
        <div class="col-md-12">
           <div class="card">
               <div class="card-header">
                    <h4>@lang(ucfirst($section->name).' Content')</h4>
               </div>
               <div class="card-body">
                    <form action="{{route('admin.frontend.content.update',$section->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                          
                            <div class="form-group col-md-6">
                                <label for="">@lang('Title')</label>
                                <input type="text" name="title" class="form-control" placeholder="@lang('Service Title')" value="{{@$section->content->title}}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">@lang('Heading')</label>
                                <input type="text" name="heading" class="form-control" placeholder="@lang('Service Heading')" value="{{@$section->content->heading}}" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="">@lang('Sub Heading')</label>
                                <input type="text" name="sub_heading" class="form-control" placeholder="@lang('Service Sub Heading')" value="{{@$section->content->sub_heading}}" required>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">@lang('Submit')</button>
                            </div>
                        </div>
                    </form>
               </div>
           </div>
        </div>
        @endif
    </div>
        @if(is_array($section->sub_content))
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>@lang(ucfirst($section->name).' Sub Content')</h4>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#add"><i class="fas fa-plus"></i> @lang('Add New')</button>
                        </div>
                        <div class="card-body text-center">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>@lang('Icon')</th>
                                        <th>@lang('Title')</th>
                                        <th>@lang('Details')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                    @forelse ($section->sub_content as $key => $info)
                                        <tr>
                                            <td data-label="@lang('Icon')">
                                              <i class="{{$info->icon}}"></i> 
                                            </td>
                                            <td data-label="@lang('Title')">{{$info->title}}</td>
                                            <td data-label="@lang('Details')">{{Str::limit($info->details,30)}}</td>
                                            <td data-label="@lang('Action')">
                                                <div class="d-flex flex-wrap flex-lg-nowrap align-items-center justify-content-end justify-content-lg-center">
                                                    <a href="javascript:void(0)" class="btn btn-primary details btn-sm m-1" data-key="{{$key}}" data-item="{{json_encode($info)}}"><i class="fas fa-edit"></i></a>

                                                    <a href="javascript:void(0)" class="btn btn-danger remove btn-sm m-1" data-key="{{$key}}" ><i class="fas fa-trash"></i></a>
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
                       
                    </div>
                </div>
            </div>

            <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="modelTitleId">
                <div class="modal-dialog" role="document">
                    <form action="{{route('admin.frontend.sub-content.update',$section->id)}}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">@lang('Add New Sub Content')</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                     <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="">@lang('Icon')</label>

                                    <div class="input-group">
                                        <input type="text" class="form-control icon-value" name="icon"
                                        value="">
                                        <span class="input-group-append">
                                            <button class="btn btn-outline-secondary iconpicker" data-icon="fas fa-home"
                                                role="iconpicker"></button>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Title')</label>
                                    <input class="form-control" type="text" name="title" required>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Details')</label>
                                    <textarea class="form-control" type="text" name="details" required></textarea>
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

            <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="modelTitleId">
                <div class="modal-dialog" role="document">
                    <form action="{{route('admin.frontend.sub-content.single.update')}}" method="POST">
                        @csrf
                        <input type="hidden" name="section" value="{{$section->id}}">
                        <input type="hidden" name="sub_key">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">@lang('Edit Sub Content')</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                     <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="">@lang('Icon')</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control icon-value2" name="icon"
                                        value="">
                                        <span class="input-group-append">
                                            <button class="btn btn-outline-secondary iconpicker2" data-icon="fas fa-home"
                                                role="iconpicker"></button>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Title')</label>
                                    <input class="form-control" type="text" name="title" required>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Details')</label>
                                    <textarea class="form-control" type="text" name="details" required></textarea>
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

            <div id="remove" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="{{route('admin.frontend.sub-content.remove')}}" method="POST">
                        @csrf
                        <input type="hidden" name="section" value="{{$section->id}}">
                        <input type="hidden" name="key">
                        <div class="modal-content">
                            <div class="modal-body">
                                <h6 class="mt-3">@lang('Are you sure to remove?')</h6>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
                                <button type="submit" class="btn btn-danger">@lang('Confirm')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
@endsection

@push('script')
    <script>
        'use strict';
        
        $('.iconpicker').iconpicker();
        $('.iconpicker2').iconpicker();

        $('.iconpicker').on('change', function(e) {
            $('.icon-value').val(e.icon)
        })
        $('.iconpicker2').on('change', function(e) {
            $('.icon-value2').val(e.icon)
        })

        $('#add').on('shown.bs.modal', function (e) {
            $(document).off('focusin.modal');
        });
        $('#edit').on('shown.bs.modal', function (e) {
            $(document).off('focusin.modal');
        });

        $('.details').on('click',function () { 
            var data = $(this).data('item')
            $('#edit').find('.icon-value2').val(data.icon)
            $('#edit').find('input[name=title]').val(data.title)
            $('#edit').find('textarea[name=details]').val(data.details)
            $('#edit').find('input[name=sub_key]').val($(this).data('key'))
            $('#edit').modal('show')
        })
        $('.remove').on('click',function () { 
            $('#remove').find('input[name=key]').val($(this).data('key'))
            $('#remove').modal('show')
        })
    </script>
@endpush