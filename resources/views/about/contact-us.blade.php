


@extends('layouts.app')
@section('pageTitle') 
<title> 
    Contact Lavish Ride - Chauffeur Services in Houston</title>
 @endsection

@section('page_name')
    <h1 class="text-white">Contact Us</h1>
@endsection
@section('seo')
<meta name="title" content="Contact Lavish Ride - Chauffeur Services in Houston">
<meta name="description" content="Contact Lavish Ride for any inquiries, feedback, or to book our luxury chauffeur services. Our customer support team is available 24/7 to assist you.">
<meta name="keywords" content="contact Lavish Ride, customer support, chauffeur services, inquiries, feedback, booking, luxury transportation, Houston">
<link rel="canonical" href="{{ route('contactForm') }}">

<meta property="og:title" content="Contact Lavish Ride - Chauffeur Services in Houston" />
<meta property="og:description" content="Contact Lavish Ride for any inquiries, feedback, or to book our luxury chauffeur services. Our customer support team is available 24/7 to assist you.">
<meta property="og:image" content="{{ asset("assets_new/Lavish-Ride-Contact-Us.jpg") }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="LavishRide - Secure Your Safety" />
<meta property="og:url" content="{{ route('contactForm') }}" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@LavishRide" />
<meta name="twitter:title" content="Contact Lavish Ride - Chauffeur Services in Houston" />
<meta name="twitter:description" content="Contact Lavish Ride for any inquiries, feedback, or to book our luxury chauffeur services. Our customer support team is available 24/7 to assist you.">
<meta name="twitter:image" content="{{ asset("assets_new/Lavish-Ride-Contact-Us.jpg") }}" />
<script src="https://www.google.com/recaptcha/api.js?render=6LdgHg0pAAAAAFhL_RAaPQACCBMaD9ZM9AOUKS4M" async defer></script>

@endsection

@section('content')
    <style>
            #banner_section{
                background-image: url('{{ asset("assets_new/Lavish-Ride-Contact-Us.jpg") }}') !important;
            }
    </style>
    <!-- content-area -->
    <section class="white-section section-block">
        <div class="container">
            <div class="section-space"></div>
            <div class="row justify-content-center">
                <div class="col-md-5 col-lg-4 wow animated fadeInLeft text-center margin-bottom-20">
                    <i class="fa fa-home font-size-42 text-main"></i>
                    <h3 class="font-weight-600 no-margin">Location</h3>
                    <span>333 West Loop North , Suit 420, Houston, TX 77024</span>
                </div>
                <div class="col-md-5 col-lg-4 wow animated fadeInRight text-center margin-bottom-20">
                    <i class="fa fa-envelope-o font-size-42 text-main"></i>
                    <h3 class="font-weight-600 no-margin">Email</h3>
                    <span class="d-block">
                        <a title="lavishRide email" class="text-dark" href="mailto:info@lavishride.com">info@lavishride.com</a>
                    </span>
                    <span class="d-block">
                        <a title="lavishRide sales email" class="text-dark" href="mailto:sales@lavishride.com">sales@lavishride.com</a>
                    </span>
                </div>
            </div>
            <div class="section-space"></div>
        </div>
    </section>
    <div class="container-fluid">
        <div class="contact-map wow fadeIn mb-3">
            {{-- <iframe
                    src="https://maps.google.com/maps?&amp;hl=en&amp;q=333 West Loop North , Suit 420, Houston, TX 77024&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"
                    width="600" height="450" style="border:0" allowfullscreen></iframe> --}}
        </div>
    </div>


    <section id="contact" class="light-section">
        <div class="container">
            <div class="section-space"></div>
            <div class="row">
                @if(session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="col-xl-8 col-lg-8 offset-xl-2 offset-lg-2">
                    <div class="content-area">
                        <h2>Don’t hesitate to contact us to share your thoughts and concerns.</h2>

                        <form action="{{route('contactUsRequest')}}" method="post" name="contactform" id="contactform"
                              autocomplete="on">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">First Name <span class="text-danger">*</span></label>
                                    <input class="with-border" name="fname" type="text" id="first_name"
                                           placeholder="First Name" required="required"/>
                                    @error('fname')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="control-label">Last Name <span class="text-danger">*</span></label>
                                    <input class="with-border" name="lname" type="text" id="last_name"
                                           placeholder="Last Name" required="required"/>
                                    @error('lname')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">Email <span class="text-danger">*</span></label>
                                    <input class="with-border" name="email" type="email" id="email"
                                           placeholder="Email Address"
                                           pattern="^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$"
                                           required="required"/>
                                    @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control py-3 px-4"
                                           placeholder="+1-123-456-7890" required="required">
                                    @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">Message <span class="text-danger">*</span></label>
                                    <textarea class="with-border" name="message" cols="40" rows="5" id="message"
                                              placeholder="Write Your Message" spellcheck="true"
                                              required="required"></textarea>
                                    @error('message')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                @error('g-recaptcha-response') <label class="form-text text-danger">{{ $message }}</label>@enderror
                        
                                {!! NoCaptcha::renderJs() !!}
                                {!! NoCaptcha::display() !!}
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <button type="submit" class="custom-btn btn-5">Submit Message</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="section-space"></div>
        </div>
    </section>
@endsection
