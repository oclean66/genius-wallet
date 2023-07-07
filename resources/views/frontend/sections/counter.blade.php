<section class="ctas-section pt-100 bg--section pb-100 position-relative overflow-hidden">
    <div class="container">
        <div class="row gy-5 flex-wrap-reverse justify-content-between">
            <div class="col-xl-6 col-lg-7">
                @if (!empty($section->sub_content))
                <div class="row g-4">
                    @foreach ($section->sub_content as $item)
                        <div class="col-sm-6">
                            <div class="counter-item">
                                <div class="counter-icon">
                                    <i class="{{@$item->icon}}"></i>
                                </div>
                                <div class="counter-content">
                                    <h3 class="counter-title">
                                        <span class="count">{{@$item->counter}}</span>
                                    </h3>
                                    <h6 class="counter-subtitle">{{@$item->title}}</h6>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
            <div class="col-xl-5 col-lg-5 align-self-center">
                <div class="section-title mb-4 pb-3">
                    <h6 class="subtitle text--base">@lang(@$section->content->title)</h6>
                    <h2 class="title">@lang(@$section->content->heading)</h2>
                    <p>
                        @lang(@$section->content->sub_heading)
                    </p>
                </div>
                <a href="{{url(@$section->content->button_url ?? '/')}}" class="cmn--btn">@lang(@$section->content->button_name)</a>
            </div>
        </div>
    </div>
    <span class="banner-elem elem1">&nbsp;</span>
    <span class="banner-elem elem3">&nbsp;</span>
    <span class="banner-elem elem5">&nbsp;</span>
    <span class="banner-elem elem6">&nbsp;</span>
</section>