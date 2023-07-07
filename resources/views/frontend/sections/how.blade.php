 <!-- How To -->
 <section class="how-section pt-50 pb-100 overflow-hidden position-relative">
    <div class="container">
        <div class="row align-items-center flex-wrap-reverse gy-5">
            <div class="col-lg-6">
                <div class="pe-xl-4 pe-xxl-5">
                    <div class="section-title text-lg-end">
                        <h6 class="subtitle text--base">@lang(@$section->content->title)</h6>
                        <h2 class="title">@lang(@$section->content->heading)</h2>
                        <p>
                            @lang(@$section->content->sub_heading)
                        </p>
                    </div>
                    @if(!empty($section->sub_content))
                    <div class="how-wrapper">
                        @foreach ($section->sub_content as $item)
                        <div class="how__item">
                            <div class="how__item-thumb">
                                <i class="{{$item->icon}}"></i>
                            </div>
                            <div class="how__item-content">
                                <h5 class="how__item-title text--base">
                                    @lang(@$item->title)
                                </h5>
                                <p>
                                    @lang(@$item->details)
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-6">
                <div class="how-thumb">
                    <img src="{{getPhoto(@$section->content->image)}}" alt="about" />
                </div>
            </div>
        </div>
    </div>
    <span class="banner-elem elem3">&nbsp;</span>
    <span class="banner-elem elem7">&nbsp;</span>
    <span class="banner-elem elem2">&nbsp;</span>
    <span class="banner-elem elem5">&nbsp;</span>
    <span class="banner-elem elem8">&nbsp;</span>
</section>
<!-- How To -->