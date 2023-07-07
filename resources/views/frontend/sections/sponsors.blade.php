 <!-- Sponsors -->
 <section class="sponsor-section pt-50 pb-100">
    <div class="container">
        <div class="section-title text-center">
            <h6 class="subtitle text--base">@lang(@$section->content->title)</h6>
            <h2 class="title">@lang(@$section->content->heading)</h2>
             <p>
                @lang(@$section->content->sub_heading)
            </p>
        </div>
        @if (!empty($section->sub_content))
            <div class="sponsor-slider owl-theme owl-carousel">
                @foreach ($section->sub_content as $item)
                    <div class="sponsor-item">
                        <img src="{{getPhoto(@$item->image)}}" alt="sponsor">
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
<!-- Sponsors -->