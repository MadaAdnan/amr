<footer class="site-footer fullwidth">
    <div class="footer-area">
        <div class="container">

            <div class="row margin-top-20" style="justify-content: center;">
                <div class="col-md-3">
                    <div class="widget">
                        <p class="widget-title text-main-dark">Company</p>
                        <div class="quick-links">
                            <ul>
                                <li><a title="Home"      href="{{ env('APP_URL') }}">Home</a></li>
                                <li><a title="Fleet"     href="{{route('fleets')}}">Fleet</a></li>
                                <li><a title="Affiliate" href="{{route('affiliate_application')}}">Affiliate</a></li>
                                <li><a title="Chauffeur" href="{{route('chauffeur_application')}}">Chauffeur</a></li>
                                <li><a title="About Us"  href="{{route('frontEnd.about_us')}}">About Us</a></li>
                                <li><a title="Countries" href="{{route('frontEnd.countries')}}">Countries</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="widget">
                        <p class="widget-title text-main-dark">Services</p>
                        <div class="quick-links">
                            <ul class="">
                                @foreach ($services->take(6) as $item)
                                    <li><a title="{{ $item->title }}" class="max-char"  href="{{ route('frontEnd.services.details',$item->slug) }}">{{ $item->short_title ?? 'No Title' }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            
                <div class="col-md-3">
                    <div class="widget">
                        <p class="widget-title text-main-dark">Contact  Us</p>
                        <div class="quick-links">
                            <ul>
                                <li><a title="lavishRide email" href="mailto:Info@Lavishride.com">Info@Lavishride.com</a></li>
                                <li><a title="lavishRide sales email" href="mailto:Sales@Lavishride.com">Sales@Lavishride.com</a></li>
                                <li><a title="lavishRide location" target="_blank" href="https://goo.gl/maps/Yk7AP4GStaWYZ6bH6">333 West Loop North , Suit 420, Houston, TX 77024</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="widget">
                        <p class="widget-title2 text-main-dark margin-bottom-0">Book Now</p>
                        <div class="quick-links">
                            <p>Guarantee a premium black car service with three steps only</p>
                            <a title="LavishRide Book A Ride" href="{{ env('APP_URL') }}/reservations" class="button main ripple-effect button-sliding-icon text-white" data-animation="fadeInUp" data-delay=".6s" tabindex="0" style="animation-delay: 0.6s;font-size: 100%;padding: 10px 0px;width: 45%">
                                Book A Ride<i class="fa fa-angle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="copyright">
        <div class="container">
            <div class="row margin-top-4">
                <div class="col-md-5">
                    <div class="copyright-text text-main-dark">
                        ©2024 Lavish Ride LLC. All Rights Reserved.
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="d-flex bottom-links">
                        <li ><a title="Terms & Conditions" class="text-main-dark" href="{{route('frontEnd.terms')}}">Terms & Conditions</a></li>
                        <li ><a title="Privacy Policy" class="text-main-dark" href="{{route('frontEnd.policy')}}">Privacy Policy</a></li>
                        <li ><a title="FAQs" class="text-main-dark" href="{{route('faqs')}}">FAQs</a></li>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <div class="social">
                        <a title="lavishRide linkedin" target="_blank" href="https://www.linkedin.com/company/lavish-ride-us/"><i class="fa fa-linkedin"></i></a>
                        <a title="lavishRide facebook" target="_blank" href="https://www.facebook.com/LavishrideUS"><i class="fa fa-facebook"></i></a>
                        <a title="lavishRide instagram" target="_blank" href="https://www.instagram.com/lavish_ride/"><i class="fa fa-instagram"></i></a>
                        <a title="lavishRide twitter" target="_blank" href="https://twitter.com/lavishride?s=21&t=te5wTudhQ7CKuDgP9glMqA"><i class="fa fa-twitter"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
