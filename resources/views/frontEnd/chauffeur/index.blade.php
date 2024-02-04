@extends('frontEnd.layouts.index')

@section('title')
Chauffeur Partners - Lets help you grow | LavishRide
@endsection

@section('seo')
<meta name="title" content="Chauffeur Partners - Lets help you grow | LavishRide">
<meta name="description" content="Own a chauffeur company? Apply now to join our network of partners, granting you exclusive access to our discerning clientele.">
<meta name="keywords" content="Become a chauffeur partner, Chauffeur, Grow your business with Lavishride, driver login">
<link rel="canonical"    href="{{ route('frontEnd.chauffeur.index') }}/">

<meta property="og:title" content="Chauffeur Partners - Lets help you grow | LavishRide" />
<meta property="og:description" content="Own a chauffeur company? Apply now to join our network of partners, granting you exclusive access to our discerning clientele.">
<meta property="og:image" content="{{ asset("assets_new/Lavish-Ride-Chauffeur.jpg") }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="LavishRide - Secure Your Safety" />
<meta property="og:url" content="{{ route('frontEnd.chauffeur.index') }}/" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@LavishRide" />
<meta name="twitter:title" content="Chauffeur Partners - Lets help you grow | LavishRide" />
<meta name="twitter:description" content="Own a chauffeur company? Apply now to join our network of partners, granting you exclusive access to our discerning clientele.">
<meta name="twitter:image" content="{{ asset("assets_new/Lavish-Ride-Chauffeur.jpg") }}" />   
@endsection

@section('content')

<!--Join Us START -->
<x-general.join-us-hero 
title="Join The Elite" 
slogan="Apply Today" 
description="Speedily say has suitable disposal add boy. On forth doubt miles of child. Exercise joy man children rejoiced."
image="{{ asset('frontEnd/assets/images/element/have_a_ride.svg') }}"
buttonActionLink="{{ route('frontEnd.chauffeur.create') }}"
buttonText="Apply"
/>
<!--Join Us End -->

<!--Benefits START -->
<x-general.benefits />
<!--Benefits END -->

<!--Testimonials START -->
<x-blog.testimonials />
<!--Testimonials END -->




@endsection
