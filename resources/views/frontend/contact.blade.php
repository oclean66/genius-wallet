@extends('layouts.frontend')

@section('title')
    @lang('Contact Us')
@endsection

@section('content')
<section class="contact-section pt-100 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 pe-xl-5">
               
                    <form class="contact-form row g-4" action="" method="POST">
                        @csrf
                        <div class="col-sm-6 form-group">
                            <label class="form--label" for="name">@lang('Your Name')</label>
                            <input type="text" name="name" class="form-control form--control bg--section" id="name"
                                placeholder="@lang('Your Name')" required value="{{old('name')}}">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label class="form--label" for="email">@lang('Your Email Address')</label>
                            <input type="text" name="email" class="form-control form--control bg--section" id="email"
                                placeholder="@lang('Your Email Address')" required value="{{old('email')}}">
                        </div>
                        <div class="col-sm-12 form-group">
                            <label class="form--label" for="subject">@lang('Subject')</label>
                            <input type="text" name="subject" class="form-control form--control bg--section" id="subject"
                                placeholder="@lang('Subject')" required value="{{old('subject')}}">
                        </div>
                        <div class="col-sm-12 form-group">
                            <label class="form--label" for="message">@lang('Your Message')</label>
                            <textarea id="message" name="message" class="form-control form--control bg--section"
                                placeholder="@lang('Your Message')" required>{{old('subject')}}</textarea>
                        </div>
                       
                        <div class="col-xl-12 form-group">
                            <button type="submit" class="cmn--btn">@lang('Send Message')</button>
                        </div>
                    </form>
                
            </div>
            <div class="col-lg-6">
                <div class="section-title mb-4 pb-3">
                    <h6 class="subtitle text--base">@lang(@$contact->content->title)</h6>
                    <h2 class="title">@lang(@$contact->content->heading)</h2>
                    <p>
                       @lang(@$contact->content->sub_heading)
                    </p>
                </div>
                <div class="contact-content">
                    <div class="contact__item">
                        <div class="contact__item-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact__item-cont">
                            <h5 class="contact__item-title">@lang('Phone')</h5>
                            <a href="Tel:{{@$contact->content->phone}}">{{@$contact->content->phone}}</a>
                        </div>
                    </div>
                    <div class="contact__item">
                        <div class="contact__item-icon">
                            <i class="far fa-envelope"></i>
                        </div>
                        <div class="contact__item-cont">
                            <h5 class="contact__item-title">@lang('Email')</h5>
                            <a href="{{@$contact->content->email}}">{{@$contact->content->email}}</a>
                        </div>
                    </div>
                    <div class="contact__item">
                        <div class="contact__item-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact__item-cont">
                            <h5 class="contact__item-title">@lang('Address')</h5>
                            <span class="text--base">{{@$contact->content->address}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection