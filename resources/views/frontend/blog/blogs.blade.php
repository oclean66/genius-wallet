@extends('layouts.frontend')

@section('title')
    @lang('Blogs')
@endsection

@section('content')
    <!-- Blog -->
    <section class="blog-section pt-100 pb-100">
        <div class="container">
            <div class="row justify-content-center gy-4">
                @forelse ($blogs as $item)
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="blog__item">
                        <div class="blog__item-img">
                            <a href="{{route('blog.details',[$item->id,$item->slug])}}">
                                <img src="{{getPhoto($item->photo)}}" alt="blog">
                            </a>
                        </div>
                        <div class="blog__item-content">
                            <div class="d-flex flex-wrap justify-content-between meta-post">
                                <span><i class="far fa-user"></i> @lang('Admin')</span>
                                <span><i class="fas fa-calendar"></i> {{dateFormat($item->created_at,'d M Y')}}</span>
                            </div>
                            <h5 class="blog__item-content-title">
                                <a href="{{route('blog.details',[$item->id,$item->slug])}}">
                                   {{__(Str::limit($item->title,30))}}
                                </a>
                            </h5>
                            <a href="{{route('blog.details',[$item->id,$item->slug])}}" class="read-more">@lang('Read More')</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="blog__item">
                        <div class="blog__item-content">
                            <h5 class="blog__item-content-title text-center">
                                @lang('No Blog Found!')
                            </h5>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
            @if ($blogs->hasPages())
            <ul class="pagination">
                {{$blogs->links()}}
            </ul>
            @endif
        </div>
    </section>
    <!-- Blog -->
@endsection