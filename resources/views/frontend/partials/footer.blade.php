 @php
     $policies = App\Models\SiteContent::where('slug','policies')->first();
 @endphp
 <!-- Footer -->
  <footer class="position-relative overflow-hidden">
    <div class="footer-top bg--section">
        <div class="container">
            <div class="footer-wrapper">
                <div class="footer-widget">
                    <h5 class="footer-title">@lang('Menu')</h5>
                    <ul>
                        @foreach (json_decode($gs->menu) as $item)
                        <li>
                           <a target="{{$item->target == 'self' ? '':'_blank'}}" href="{{url($item->href)}}">{{__($item->title)}}</a>
                        </li>
                    @endforeach
                    </ul>
                </div>
                <div class="footer-widget">
                    <h5 class="footer-title">@lang('Get Started')</h5>
                    <ul>
                        <li>
                            <a href="{{route('user.register')}}">@lang('Register as User')</a>
                        </li>
                        <li>
                            <a href="{{route('user.login')}}">@lang('Login as User')</a>
                        </li>
                        <li>
                            <a href="{{route('merchant.register')}}">@lang('Register as Merchant')</a>
                        </li>
                        <li>
                            <a href="{{route('merchant.login')}}">@lang('Login as Merchant')</a>
                        </li>
                       
                       
                    </ul>
                </div>
                
                <div class="footer-widget">
                    <h5 class="footer-title">@lang('Pages')</h5>
                    <ul>
                        <li>
                            <a href="{{route('faq')}}">@lang('Frequently Asked Questions')</a>
                        </li>
                        @foreach (DB::table('pages')->where('lang',app()->currentLocale())->get() as $item)
                            @if ($item->slug != 'about')
                                <li>
                                    <a href="{{route('pages',[$item->id,$item->slug])}}">@lang($item->title)</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom bg--body py-4">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-center justify-content-sm-between bottom-menu-wrapper">
                <div class="footer-logo">
                    <a href="{{url('/')}}">
                        <img src="{{getPhoto($gs->header_logo)}}" alt="logo">
                    </a>
                </div>
                <div class="copyright">
                    &copy; @lang('All Right Reserved by') <a href="{{url('/')}}" class="text--base">{{__($gs->title)}}</a>
                </div>
                <ul class="bottom-menu">
                    @foreach ($policies->sub_content as $key=> $item)
                        @if (app()->currentLocale() == $item->lang)
                            <li>
                                <a href="{{route('terms.details',[$key,Str::slug($item->title)])}}">{{__($item->title)}}</a>
                            </li>
                        @endif
                    @endforeach
                   
                </ul>
            </div>
        </div>
    </div>
    <span class="banner-elem elem3">&nbsp;</span>
    <span class="banner-elem elem5">&nbsp;</span>
    <span class="banner-elem elem6">&nbsp;</span>
    <span class="banner-elem elem8">&nbsp;</span>
</footer>
<!-- Footer -->