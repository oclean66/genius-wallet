 <!-- Banner -->
 <section class="banner-section">
    <div class="container">
        <div class="banner__wrapper">
            <div class="banner__wrapper-content">
                <h6 class="subtitle text--base">{{__(@$section->content->title)}}</h6>
                <h1 class="title">
                    @lang(@$section->content->heading)
                </h1>
                <p>
                    @lang(@$section->content->sub_heading)
                </p>
                <div class="btn__grp mt-4">
                    <a href="{{url(@$section->content->button_1_url ?? '/')}}" class="cmn--btn btn-outline">@lang(@$section->content->button_1_name)</a>

                    <a href="{{url(@$section->content->button_2_url ?? '/')}}" class="cmn--btn">@lang(@$section->content->button_2_name)</a>
                </div>
            </div>
            <div class="banner__wrapper-thumb">
                <img src="{{getPhoto(@$section->content->image)}}"/>
            </div>
        </div>
    </div>
    <span class="banner-elem elem1">&nbsp;</span>
    <span class="banner-elem elem2">&nbsp;</span>
    <span class="banner-elem elem3">&nbsp;</span>
    <span class="banner-elem elem4">&nbsp;</span>
    <span class="banner-elem elem5">&nbsp;</span>
    <span class="banner-elem elem6">&nbsp;</span>
    <span class="banner-elem elem7">&nbsp;</span>
    <span class="banner-elem elem8">&nbsp;</span>
    <span class="banner-elem elem9">&nbsp;</span>
</section>
<!-- Banner -->