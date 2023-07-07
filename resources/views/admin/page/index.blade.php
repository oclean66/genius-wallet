
@extends('layouts.admin')

@section('title')
   @lang('Page Settings')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header d-flex justify-content-between">
        <h1>@lang('Page Settings')</h1>
        <a href="{{route('admin.page.create')}}" class="btn btn-primary"><i class="fas fa-plus"></i> @lang('Create New') </a>
    </div>
</section>
@endsection

@section('content')

<div class="row mt-3">
   <div class="col-lg-12">
      <div class="card mb-4">
         <div class="table-responsive p-3">
            <table class="table table-striped">
                <tr>
                    <th>@lang('Title')</th>
                    <th>@lang('URL Slug')</th>
                    <th>@lang('Details')</th>
                    <th>@lang('Language')</th>
                    <th>@lang('Actions')</th>
                </tr>
                @forelse ($pages as $info)
                    <tr>

                         <td data-label="@lang('Title')">
                           {{$info->title	}}
                         </td>
                         <td data-label="@lang('URL Slug')">
                           {{$info->slug	}}
                         </td>
                         <td data-label="@lang('Details')">{{Str::limit(strip_tags($info->details),40)}}</td>
                         <td data-label="@lang('Language')">
                           {{$info->lang	}}
                         </td>
                         <td data-label="@lang('Actions')">
                            <a href="{{route('admin.page.edit',$info)}}" class="btn btn-primary btn-sm mb-1"  data-toggle="tooltip" title="@lang('Edit')"><i class="fas fa-edit"></i></a>

                            @if ($info->slug != 'about')
                            <a href="javascript:void(0)" class="btn btn-danger btn-sm remove mb-1" data-id="{{$info->id}}"  data-toggle="tooltip" title="@lang('Remove')"><i class="fas fa-trash"></i></a>
                            @endif
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

<!-- Modal -->
<div class="modal fade" id="removeMod" tabindex="-1" role="dialog">
   <div class="modal-dialog" role="document">
      <form action="{{route('admin.page.remove')}}" method="POST">
         @csrf
         <input type="hidden" name="id">
         <div class="modal-content">
            <div class="modal-body">
               <h5>@lang('Are you sure to remove?')</h5>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
               <button type="submit" class="btn btn-danger">@lang('Confirm')</button>
            </div>
         </div>
      </form>
   </div>
</div>

@endsection

@push('script')
    <script>
       'use strict';
       $('.remove').on('click',function () { 
         $('#removeMod').find('input[name=id]').val($(this).data('id'))
         $('#removeMod').modal('show')
       })
    </script>
@endpush
