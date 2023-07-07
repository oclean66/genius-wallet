<!-- Feature -->
<section class="feature-section pt-100 pb-50 position-relative">
    <span class="banner-elem elem10">&nbsp;</span>
    <span class="banner-elem elem6">&nbsp;</span>
    <div class="container">
        <div class="row gy-5">
            <div class="col-lg-6">
                <div class="sticky-section-title">
                    <div class="section-title mb-0">
                        <h6 class="subtitle text--base">@lang(@$section->content->title)</h6>
                        <h2 class="title">@lang(@$section->content->heading)</h2>
                        <p>
                            @lang(@$section->content->sub_heading)
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                @if(!empty($section->sub_content))
                    <div class="feature-wrapper">
                        @foreach ($section->sub_content as $item)
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <img src="{{getPhoto(@$item->image)}}">
                                </div>
                                <div class="feature-content">
                                    <h5 class="feature-title text--base">@lang(@$item->title)</h5>
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
    </div>
</section>
<!-- Feature -->