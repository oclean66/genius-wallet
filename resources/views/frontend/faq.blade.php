@extends('layouts.frontend')

@section('title')
    @lang(@$faq->name)
@endsection

@section('content')
    <section class="faqs-section pt-50 pb-100">
        <div class="container">
            <div class="row flex-wrap-reverse gy-5 mb-5 justify-content-center">
                <div class="col-lg-6">
                    <div class="sticky-section-title ps-xl-3 ps-xxl-4">
                        <div class="section-title mb-0">
                            <h2 class="title">@lang(@$faq->content->heading)</h2>
                            <p>
                                @lang(@$faq->content->sub_heading)
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row flex-wrap-reverse gy-5 justify-content-center">
                <div class="col-lg-10">
                    @if(!empty($faq->sub_content))
                        @foreach($faq->sub_content as $key => $item)
                            
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
                          
                        @endforeach
                    @endif
                </div>
                
            </div>
        </div>
        <span class="banner-elem elem1">&nbsp;</span>
        <span class="banner-elem elem3">&nbsp;</span>
        <span class="banner-elem elem5">&nbsp;</span>
        <span class="banner-elem elem6">&nbsp;</span>
    </section>
 
@endsection