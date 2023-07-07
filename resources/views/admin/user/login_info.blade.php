@extends('layouts.admin')

@section('title')
   @lang('Login info : '.$user->name)
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header">
        <h1>@lang('Login info : '.$user->name)</h1>
    </div>
</section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
   
                <div class="card-body text-center">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th>@lang('Sl')</th>
                                <th>@lang('User')</th>
                                <th>@lang('IP')</th>
                                <th>@lang('City')</th>
                                <th>@lang('Country')</th>
                            </tr>
                            @forelse ($loginInfo as $key => $item)
                                <tr>
                                    <td data-label="@lang('Sl')">{{$key + $loginInfo->firstItem()}}</td>
                                     <td data-label="@lang('User')">
                                       {{$item->user->email}}
                                     </td>
                                     <td data-label="@lang('IP')">{{$item->ip}}</td>
                                     <td data-label="@lang('City')">{{$item->city}}</td>
                                     <td data-label="@lang('Country')">{{$item->country}}</td>
                                   
                                </tr>
                             @empty
    
                                <tr>
                                    <td class="text-center" colspan="100%">@lang('No Data Found')</td>
                                </tr>
    
                            @endforelse
                        </table>
                    </div>
                </div>
                @if ($loginInfo->hasPages())
                    {{ $loginInfo->links('admin.partials.paginate') }}
                @endif
            </div>
        </div>
    </div>
@endsection