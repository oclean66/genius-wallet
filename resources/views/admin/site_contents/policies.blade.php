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
   
        @if(is_array($section->sub_content))
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>@lang(ucfirst($section->name).' SubContent')</h4>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#add"><i class="fas fa-plus"></i> @lang('Add New')</button>
                        </div>
                        <div class="card-body text-center">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>@lang('SL')</th>
                                        <th>@lang('Title')</th>
                                        <th>@lang('Language')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                    @php
                                    $i = 0;
                                    @endphp
                                    @forelse ($section->sub_content as $key => $info)
                                        <tr>
                                            <td data-label="@lang('SL')">
                                                {{++$i}}
                                            </td>
                                            <td data-label="@lang('Title')">
                                               {{$info->title}}
                                            </td>
                                            <td data-label="@lang('Language')">
                                               {{$info->lang}}
                                            </td>
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

            <div class="modal fade" id="add" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-xl" role="document">
                    <form action="{{route('admin.frontend.sub-content.update',$section->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">@lang('Add Policy and Term')</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                     <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>@lang('Language')</label>
                                   <select name="lang" class="form-control" required>
                                     @foreach (DB::table('languages')->get() as $item)
                                       <option value="{{$item->code}}">{{$item->language}}</option>
                                     @endforeach
                                   </select>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Title')</label>
                                    <input class="form-control" type="text" name="title" required>
                                </div>
                               
                                <div class="form-group">
                                    <label>@lang('Description')</label>
                                    <textarea name="description" class="form-control summernote" rows="10" required></textarea>
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
                <div class="modal-dialog modal-xl" role="document">
                    <form action="{{route('admin.frontend.sub-content.single.update')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="section" value="{{$section->id}}">
                        <input type="hidden" name="sub_key">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">@lang('Edit Policy and Term')</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                     <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                               
                               <div class="form-group">
                                    <label>@lang('Language')</label>
                                   <select name="lang" class="form-control" required>
                                        @foreach (DB::table('languages')->get() as $item)
                                        <option value="{{$item->code}}">{{$item->language}}</option>
                                        @endforeach
                                   </select>
                                </div>
                                
                                <div class="form-group">
                                    <label>@lang('Title')</label>
                                    <input class="form-control" type="text" name="title" required>
                                </div>
                               
                                <div class="form-group">
                                    <label>@lang('Description')</label>
                                    <textarea name="description" class="form-control summernote" rows="10" required></textarea>
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
    
        $('.details').on('click',function () { 
            var data = $(this).data('item')
            $('#edit').find('input[name=sub_key]').val($(this).data('key'))
            $('#edit').find('input[name=title]').val(data.title)
            $('#edit').find('select[name=lang]').val(data.lang)
            $('#edit').find('.summernote').summernote('reset')
            $('#edit').find('.summernote').summernote('pasteHTML',data.description)
            $('#edit').modal('show')
        })
        $('.remove').on('click',function () { 
            $('#remove').find('input[name=key]').val($(this).data('key'))
            $('#remove').modal('show')
        })
    </script>
@endpush