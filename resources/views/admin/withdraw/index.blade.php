@extends('layouts.admin')

@section('title')
  @lang('Withdraw Methods')
@endsection

@section('breadcrumb')
 <section class="section">
    <div class="section-header justify-content-between">
        <h1>@lang('Withdraw Methods')</h1>
        @if (access('withdraw method create'))
        <a href="{{route('admin.withdraw.create')}}" class="btn btn-primary add"><i class="fa fa-plus"></i> @lang('Add new Method')</a>
            
        @endif
    </div>
</section>
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header justify-content-end">
                    <div class="card-header-form">
                        <form method="GET" action="{{ route('admin.withdraw.search') }}">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search">
                                <div class="input-group-append">
                                    <button class="btn btn-primary border-0"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-body text-center">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <th>@lang('Sl')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                                @forelse ($withdraws as $key => $withdraw)
                                    <tr>

                                        <td data-label="@lang('Sl')">{{$key +  $withdraws->firstItem() }}</td>
                                        <td data-label="@lang('Name')">{{ $withdraw->name }}</td>

                                        <td data-label="@lang('status')">
                                            @if ($withdraw->status)
                                                <div class="badge badge-success">@lang('Active')</div>
                                            @else
                                                <div class="badge badge-danger">@lang('Inactive')</div>
                                            @endif
                                        </td>

                                        @if (access('withdraw method edit'))
                                        <td data-label="@lang('Action')">
                                            <a href="{{ route('admin.withdraw.edit', $withdraw->id) }}"  class="btn btn-primary update"><i class="fa fa-pen"></i></a>
                                        </td>
                                        @else
                                        @lang('N/A')
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
                    @if ($withdraws->hasPages())
                        <div class="card-footer">
                            {{ $withdraws->links('admin.partials.paginate') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


@endsection
