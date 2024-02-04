@extends('frontEnd.layouts.index')

@section('title')
    Contact Lavish Ride - Chauffeur Services in Houston
@endsection

@section('seo')
<meta name="title" content="Contact Lavish Ride - Chauffeur Services in Houston">
<meta name="description" content="Contact Lavish Ride for any inquiries, feedback, or to book our luxury chauffeur services. Our customer support team is available 24/7 to assist you.">
<meta name="keywords" content="contact Lavish Ride, customer support, chauffeur services, inquiries, feedback, booking, luxury transportation, Houston">
<link rel="canonical" href="{{ route('frontEnd.contactUs.thank_you') }}/">

<meta property="og:title" content="Contact Lavish Ride - Chauffeur Services in Houston" />
<meta property="og:description" content="Contact Lavish Ride for any inquiries, feedback, or to book our luxury chauffeur services. Our customer support team is available 24/7 to assist you.">
<meta property="og:image" content="{{ asset("assets_new/Lavish-Ride-Contact-Us.jpg") }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="LavishRide - Secure Your Safety" />
<meta property="og:url" content="{{ route('frontEnd.contactUs.thank_you') }}" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@LavishRide" />
<meta name="twitter:title" content="Contact Lavish Ride - Chauffeur Services in Houston" />
<meta name="twitter:description" content="Contact Lavish Ride for any inquiries, feedback, or to book our luxury chauffeur services. Our customer support team is available 24/7 to assist you.">
<meta name="twitter:image" content="{{ asset("assets_new/Lavish-Ride-Contact-Us.jpg") }}" />
<script src="https://www.google.com/recaptcha/api.js?render=6LdgHg0pAAAAAFhL_RAaPQACCBMaD9ZM9AOUKS4M" async defer></script>
@endsection


@section('content')
<section class="overflow-hidden pt-0 pt-lg-5">
    <x-general.thank-you-page-card
        image="{{ asset('frontEnd/assets/images/element/help.svg') }}"
        title="Message received!"
        description="Expect to hear from us soon."
    />
    </section>
@endsection
