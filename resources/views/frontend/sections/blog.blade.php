<!-- Blog -->
@php
    $blogs = App\Models\Blog::where('status',1)->whereHas('category',function($q){
        $q->where('status',1);
    })->latest()->inRandomOrder()->take(3)->get();
@endphp
<section class="blog-section pt-100 pb-50">
    <div class="container">
        <div class="section-title text-center">
            <h6 class="subtitle text--base">@lang(@$section->content->title)</h6>
            <h2 class="title">@lang(@$section->content->heading)</h2>
            <p>
                @lang(@$section->content->sub_heading)
            </p>
        </div>
        <div class="row justify-content-center gy-4">
            @foreach ($blogs as $item)
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
            @endforeach
        </div>
    </div>
</section>
<!-- Blog -->