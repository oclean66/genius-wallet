 <!-- FAQs -->
 <section class="faqs-section pt-50 pb-100">
    <div class="container">
        <div class="row flex-wrap-reverse gy-5">
            <div class="col-lg-6">
                @if(!empty($section->sub_content))
                    @foreach($section->sub_content as $key => $item)
                        @if ($key < 5)
                        <div class="accordion-wrapper">
                            <div class="accordion-item {{$loop->first ? 'open active':''}}">
                                <div class="accordion-title">
                                    <h6 class="title">
                                        {{__(@$item->question)}}
                                    </h6>
                                    <span class="icon"></span>
                                </div>
                                <div class="accordion-content">
                                    <p>
                                        {{__(@$item->answer)}}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="col-lg-6">
                <div class="sticky-section-title ps-xl-3 ps-xxl-4">
                    <div class="section-title mb-0">
                        <h6 class="subtitle text--base">@lang(@$section->content->title)</h6>
                        <h2 class="title">@lang(@$section->content->heading)</h2>
                        <p>
                            @lang(@$section->content->sub_heading)
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- FAQs -->