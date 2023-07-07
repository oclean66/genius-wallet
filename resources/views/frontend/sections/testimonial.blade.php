<!-- Testimonial -->
<section class="testimonial-section bg--section pt-100 pb-100 overflow-hidden position-relative">
    <div class="container">
        <div class="section-title text-center">
            <h6 class="subtitle text--base">@lang(@$section->content->title)</h6>
            <h2 class="title">@lang(@$section->content->heading)</h2>
            <p>
                @lang(@$section->content->sub_heading)
            </p>
        </div>
        @if (!empty($section->sub_content))
            <div class="testimonial-slider owl-carousel owl-theme">
                @foreach ($section->sub_content as $item)
                    <div class="testimonial-item">
                        <div class="testimonial-header">
                            <div class="thumb">
                                <img src="{{getPhoto(@$item->image)}}">
                            </div>
                            <div class="icon">
                                <i class="fas fa-quote-left"></i>
                            </div>
                        </div>
                       
                        <div class="testimonial-content">
                            <p>
                                @lang(@$item->quote)
                            </p>
                            <h5 class="name text--base mt-3">@lang(@$item->name)</h5>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <span class="banner-elem elem2">&nbsp;</span>
</section>
<!-- Testimonial -->
