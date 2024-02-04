@extends('layouts.app')
@section('pageTitle')
    <title> 
        About LavishRide | Your Luxury Transportation Services
    </title>
@endsection

@section('seo')
    <meta name="title" content="About LavishRide | Your Luxury Transportation Services">
    <meta name="description"
        content="Learn more about Lavish Ride and our commitment to providing the most luxurious, premium, and comfortable black car service.">
    <meta name="keywords"
        content="about Lavish Ride, luxurious black car service, premium transportation, comfortable ride, airport car service, corporate transportation, wedding getaways, Houston">
    <link rel="canonical" href="{{ route('frontEnd.about_us') }}">

    <meta property="og:title" content="About LavishRide | Your Luxury Transportation Services" />
    <meta property="og:description"
        content="Learn more about Lavish Ride and our commitment to providing the most luxurious, premium, and comfortable black car service.">
    <meta property="og:image" content="{{ asset('assets_new/Lavish-Ride-About-Us.webp') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="LavishRide - Secure Your Safety" />
    <meta property="og:url" content="{{ route('frontEnd.about_us') }}/" />

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@LavishRide" />
    <meta name="twitter:title" content="About LavishRide | Your Luxury Transportation Services" />
    <meta name="twitter:description"
        content="Learn more about Lavish Ride and our commitment to providing the most luxurious, premium, and comfortable black car service.">
    <meta name="twitter:image" content="{{ asset('assets_new/Lavish-Ride-About-Us.webp') }}" />
@endsection
@section('page_name')
    <h1 class="text-white">About Us</h1>
@endsection

@section('content')
    <style>
        .section-bg-coustom {
            background-image: url('{{ asset('assets_new/Lavish-Ride-About-Us.webp') }}') !important;
        }
    </style>
    <section class="white-section section-block">

        <div class="container">
            <div class="section-space"></div>

            <div class="row justify-content-center">
                <div class="col-md-5 col-lg-6 wow animated fadeInLeft text-center margin-bottom-20">
                    <h2 class="font-weight-600 no-margin">{{ $pageData->section_one_paragraph_one_title }}</h2>
                    <span>{{ $pageData->section_one_paragraph_one_description }}</span>
                </div>

                <div class="col-md-5 col-lg-6 wow animated fadeInRight text-center margin-bottom-20">
                    <h2 class="font-weight-600 no-margin">{{ $pageData->section_one_paragraph_two_title }}</h2>
                    <span>{{ $pageData->section_one_paragraph_two_description }}</span>
                </div>

            </div>

            <div class="row justify-content-center padding-top-45">

                <div class="col-md-12 col-lg-6">
                    <img src="{{ asset('FrontEnd/locations-map-lavishride.webp') }}" alt="about us" lazy="loaded" />
                </div>

                <div class="col-md-12 col-lg-6">
                    <div class="content-area">
                        <h2 class="padding-bottom-35">{{ $pageData->section_one_title }}</h2>
                        <p>{{ $pageData->section_one_description }}</p>

                    </div>
                </div>

            </div>

            <div class="section-space"></div>

        </div>
    </section>

    <!-- Why Choose Us? -->
    <section class="dark-section section-block">

        <div class="section-bg" style="background-image: url('../assets_new/img/Black-car-names.webp');"></div>

        <div class="section-space"></div>

        <div class="container">
            <div class="row">
                <div class="col-sm-12 section-title-wrapper text-center">
                    <h2 class="section-title text-white">{{ $pageData->section_two_title }}</h2>
                    <p>{{ $pageData->section_two_description }}</p>
                </div>
            </div>

            <div class="row justify-content-center row-2">
                <div class="col-md-6 col-lg-6 wow animated fadeInLeft text-center">
                    <div class="content-area">
                        <h3 class="padding-bottom-35 text-main line-height-1-4">
                            {{ $pageData->section_two_paragraph_one_title }}</h3>
                        <p class="text-very-light">{{ $pageData->section_two_paragraph_one_description }}</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-6 wow animated fadeInRight text-center">
                    <div class="content-area">
                        <h3 class="padding-bottom-35 text-main line-height-1-4">
                            {{ $pageData->section_two_paragraph_two_title }}</h3>
                        <p class="text-very-light">{{ $pageData->section_two_paragraph_two_description }}</p>
                    </div>
                </div>
            </div>

            <div class="section-space"></div>

        </div>
    </section>

    <!-- testimonials-area -->
    <section class="light-section section-block">

        <div class="section-space"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 section-title-wrapper margin-bottom-0 text-center">
                    <h2 class="section-title">{{ $pageData->section_three_title }}</h2>
                    <p>{{ $pageData->section_three_description }}</p>
                </div>
            </div>
        </div>

        <div class="row wow fadeInUp animated">
            <div class="col-12 offset-0">
                <div class="testimonials margin-top-20" data-auto="true" data-loop="true" data-column="3" data-column2="1"
                    data-column3="1" data-gap="10">
                    <div class="owl-carousel owl-theme">

                        <div class="review-item clearfix">
                            <div class="testimonial-box" style="height: 90%">
                                <div>
                                    <img src="../assets/quote.a4f0ee63.svg" width="30px" height="30px"
                                        style="float:left;" />
                                    <p style="padding: 40px">They have never failed to meet my expectations!</p>
                                    <img src="../assets/quote.a4f0ee63.svg" width="30px" height="30px"
                                        style="float:right; transform: scaleY(-1);" />
                                </div>
                                <div class="testimonial-author">
                                    <p class="text-main font-size-18 font-weight-600">Jenni A.</p>
                                    <p class="font-size-16">Lavish Ride Customer</p>
                                </div>
                            </div>
                        </div>

                        <div class="review-item clearfix">
                            <div class="testimonial-box" style="height: 90%">
                                <div>
                                    <img src="../assets/quote.a4f0ee63.svg" width="30px" height="30px"
                                        style="float:left;" />
                                    <p style="padding: 40px">Committing to using a service has never been easier.</p>
                                    <img src="../assets/quote.a4f0ee63.svg" width="30px" height="30px"
                                        style="float:right; transform: scaleY(-1);" />
                                </div>
                                <div class="testimonial-author">
                                    <p class="text-main font-size-18 font-weight-600">Emma B.</p>
                                    <p class="font-size-16">Lavish Ride Customer</p>
                                </div>
                            </div>
                        </div>

                        <div class="review-item clearfix">
                            <div class="testimonial-box" style="height: 90%">
                                <div>
                                    <img src="../assets/quote.a4f0ee63.svg" width="30px" height="30px"
                                        style="float:left;" />
                                    <p style="padding: 40px">Life is all about decisions so I chose Lavish Ride.</p>
                                    <img src="../assets/quote.a4f0ee63.svg" width="30px" height="30px"
                                        style="float:right; transform: scaleY(-1);" />
                                </div>
                                <div class="testimonial-author">
                                    <p class="text-main font-size-18 font-weight-600">Richard C.</p>
                                    <p class="font-size-16">Lavish Ride Customer</p>
                                </div>
                            </div>
                        </div>

                        <div class="review-item clearfix">
                            <div class="testimonial-box" style="height: 90%">
                                <div>
                                    <img src="../assets/quote.a4f0ee63.svg" width="30px" height="30px"
                                        style="float:left;" />
                                    <p style="padding: 40px">Expect a level of excellency that matches no other!</p>
                                    <img src="../assets/quote.a4f0ee63.svg" width="30px" height="30px"
                                        style="float:right; transform: scaleY(-1);" />
                                </div>
                                <div class="testimonial-author">
                                    <p class="text-main font-size-18 font-weight-600">Robert D.</p>
                                    <p class="font-size-16">Lavish Ride Customer</p>
                                </div>
                            </div>
                        </div>

                        <div class="review-item clearfix">
                            <div class="testimonial-box" style="height: 90%">
                                <div>
                                    <img src="../assets/quote.a4f0ee63.svg" width="30px" height="30px"
                                        style="float:left;" />
                                    <p style="padding: 40px">I steer the wheel to ensure your safety wherever you decide to
                                        head.</p>
                                    <img src="../assets/quote.a4f0ee63.svg" width="30px" height="30px"
                                        style="float:right; transform: scaleY(-1);" />
                                </div>
                                <div class="testimonial-author">
                                    <p class="text-main font-size-18 font-weight-600">Jorge P.</p>
                                    <p class="font-size-16">Lavish Ride Chauffeur</p>
                                </div>
                            </div>
                        </div>

                        <div class="review-item clearfix">
                            <div class="testimonial-box" style="height: 90%">
                                <div>
                                    <img src="../assets/quote.a4f0ee63.svg" width="30px" height="30px"
                                        style="float:left;" />
                                    <p style="padding: 40px">With me your safety is granted.</p>
                                    <img src="../assets/quote.a4f0ee63.svg" width="30px" height="30px"
                                        style="float:right; transform: scaleY(-1);" />
                                </div>
                                <div class="testimonial-author">
                                    <p class="text-main font-size-18 font-weight-600">Ameer K.</p>
                                    <p class="font-size-16">Lavish Ride Chauffeur</p>
                                </div>
                            </div>
                        </div>

                        <div class="review-item clearfix">
                            <div class="testimonial-box" style="height: 90%">
                                <div>
                                    <img src="../assets/quote.a4f0ee63.svg" width="30px" height="30px"
                                        style="float:left;" />
                                    <p style="padding: 40px">A great chauffeur has priorities, and mine is your safety.</p>
                                    <img src="../assets/quote.a4f0ee63.svg" width="30px" height="30px"
                                        style="float:right; transform: scaleY(-1);" />
                                </div>
                                <div class="testimonial-author">
                                    <p class="text-main font-size-18 font-weight-600">Ashley P.</p>
                                    <p class="font-size-16">Lavish Ride Chauffeur</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="section-space"></div>

    </section>
@endsection
