 <!-- About -->
 <section class="about-section overflow-hidden pt-100 pb-50 position-relative">
    <div class="container position-relative">
        <div class="row flex-wrap-reverse gy-4">
            <div class="col-lg-6">
                <div class="about-img rtl">
                    <img src="{{getPhoto(@$section->content->image)}}" alt="about" />
                </div>
            </div>
            <div class="col-lg-6 align-self-center">
                <div class="section-title">
                    <h6 class="subtitle text--base">@lang(@$section->content->title)</h6>
                    <h2 class="title">@lang(@$section->content->heading)</h2>
                    <p>
                        @lang(@$section->content->short_details)
                    </p>
                </div>
                <div class="about-feature-wrapper">
                    <div class="about-feature-item">
                        <div class="icon">
                            <i class="{{@$section->content->feature_1_icon}}"></i>
                        </div>
                        <div class="cont">
                            <h5 class="subtitle">@lang(@$section->content->feature_1_title)</h5>
                            <p>
                                @lang(@$section->content->feature_1_details)
                            </p>
                        </div>
                    </div>
                    <div class="about-feature-item">
                        <div class="icon">
                            <i class="{{@$section->content->feature_2_icon}}"></i>
                        </div>
                        <div class="cont">
                            <h5 class="subtitle">@lang(@$section->content->feature_2_title)</h5>
                            <p>
                                @lang(@$section->content->feature_2_details)
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <span class="banner-elem elem3">&nbsp;</span><span class="banner-elem elem7">&nbsp;</span>
</section>
<!-- About -->