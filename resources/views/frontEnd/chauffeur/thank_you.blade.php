@extends('frontEnd.layouts.index')

@section('title')
Chauffeur Partners - Thank You | LavishRide
@endsection

@section('seo')
<meta name="title" content="Chauffeur Partners - Thank You | LavishRide">
<meta name="description" content="Own a chauffeur company? Apply now to join our network of partners, granting you exclusive access to our discerning clientele.">
<meta name="keywords" content="Become a chauffeur partner, Chauffeur, Grow your business with Lavishride, driver login">
<link rel="canonical"    href="{{ route('frontEnd.chauffeur.thank_you') }}/">

<meta property="og:title" content="Chauffeur Partners - Create | LavishRide" />
<meta property="og:description" content="Own a chauffeur company? Apply now to join our network of partners, granting you exclusive access to our discerning clientele.">
<meta property="og:image" content="{{ asset("assets_new/Lavish-Ride-Chauffeur.jpg") }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="LavishRide - Secure Your Safety" />
<meta property="og:url" content="{{ route('frontEnd.chauffeur.thank_you') }}/" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@LavishRide" />
<meta name="twitter:title" content="Chauffeur Partners - Thank You | LavishRide" />
<meta name="twitter:description" content="Own a chauffeur company? Apply now to join our network of partners, granting you exclusive access to our discerning clientele.">
<meta name="twitter:image" content="{{ asset("assets_new/Lavish-Ride-Chauffeur.jpg") }}" />   
@endsection

@section('content')
    <section class="overflow-hidden pt-0 pt-lg-5">
        <x-general.thank-you-page-card
            image="{{ asset('frontEnd/assets/images/element/congratulations.svg') }}"
            title="Your application has been submitted successfully."
            description="We will review your item shortly. You will be informed by email that your listing has been accepted. Also, make sure your."
        />
        </section>
@endsection