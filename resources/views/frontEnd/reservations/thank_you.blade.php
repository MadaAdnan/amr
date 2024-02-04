@extends('frontEnd.layouts.index')
@section('pageTitle') 
<title>
    Car Service Booking Confirmation: Thank You!
</title>
@endsection 
@section('seo')
    <meta name="title" content="Car Service Booking Confirmation: Thank You!">
    <meta name="description"
        content="Gratitude for choosing our luxury car service! We appreciate your booking. Enjoy a seamless, reliable, timely, & comfortable ride with us. Thank you!">
    <meta name="keywords"
        content="lavish ride limousine service, lavishride is pioneer, lavishride, book transfers, lavish ride limo service, lavishride black car service, lavish ride, booking wedding transportation, booking transportation, black car service near me, town car service near me, luxury car rental houston, limousine service houston, limo service in houston tx, limo reservation, limousine houston">
    <meta name="robots" content="index, follow">
    <meta name="language" content="EN">
    <link rel="canonical" href="https://lavishride.com/reservations/thank-you" />
@endsection
@section('content')
    <style>
        .banner-section {
            padding: 2.1% 0 !important;
        }

        .banner-section::before {
            background-color: #0b0b0b;
        }
    </style>
    <!-- about-area -->
    <div class="container">
        <div class="d-flex align-items-center justify-content-center pb-5" style="min-height: 100vh;">
            <div id="thankYouCard" class="card d-flex align-items-center p-3 border bg-white w-75">
                <img src="{{ asset('favIcon.png') }}" style="width: 150px; height: auto">
                <div class="mt-2 margin-bottom-40 text-center pb-5">
                    <h3 class="font-size-42 font-weight-800 text-title-thank-you-page">Thank You for choosing Lavish Ride!
                    </h3>
                    <p class="text-center font-weight-bold text-dark">
                        Your reservation is being reviewed and will be accepted shortly.<br />
                        If you have any questions, don't hesitate to get in touch with us at {{ config('general.support_email') }} or call
                        us at {{ config('general.support_phone') }}.
                    </p>
                    <a href="{{ url('/') }}" class="btn btn-primary w-50">Home</a>
                </div>
            </div>
        </div>
    </div>
    </section>
@endsection
