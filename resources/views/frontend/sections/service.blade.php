 <!-- Service -->
 <section class="service-section pt-50 pb-50 position-relative overflow-hidden">
    <div class="container position-relative">
        <div class="section-title text-center">
            <h6 class="subtitle text--base">@lang(@$section->content->title)</h6>
            <h2 class="title">@lang(@$section->content->heading)</h2>
            <p>
                @lang(@$section->content->sub_heading)
            </p>
        </div>
        @if (!empty($section->sub_content))
            <div class="row g-4 justify-content-center">
                @foreach ($section->sub_content as $item)
                    <div class="col-lg-4 col-sm-6">
                        <div class="service-item">
                            <div class="service-icon">
                                <i class="{{@$item->icon}}"></i>
                            </div>
                            <div class="feature-content">
                                <h5 class="title">
                                    <a href="#0">@lang(@$item->title)</a>
                                </h5>
                                <p>
                                    @lang(@$item->details)
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <span class="banner-elem elem3">&nbsp;</span>
    <span class="banner-elem elem7">&nbsp;</span>
    <span class="banner-elem elem2">&nbsp;</span>
    <span class="banner-elem elem5">&nbsp;</span>
    <span class="banner-elem elem8">&nbsp;</span>
</section>
<!-- Service -->