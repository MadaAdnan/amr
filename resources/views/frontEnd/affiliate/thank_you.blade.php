@extends('frontEnd.layouts.index')

@section('title')
Affiliates - Thank You - Let's Grow together
@endsection

@section('seo')
<meta name="title" content="Affiliates - Thank You - Let's Grow together | LavishRide">
<meta name="description" content="Join our affiliate program and earn commission by referring customers to Lavish Ride, the leading provider of private chauffeur services in Houston.">
<meta name="keywords" content="service partners, business partners, looking for a business partner, business partners international, need business partner, affiliate program, referral program, commission, private chauffeur services, affiliate categories, affiliate marketing, affiliate links, affiliate partner program, affiliate partner, affiliate partnership">
<link rel="canonical" href="{{ route('frontEnd.affiliate.thank_you') }}">
<meta property="og:title" content="Affiliates - Thank You | LavishRide" />
<meta property="og:description" content="Join our affiliate program and earn commission by referring customers to Lavish Ride, the leading provider of private chauffeur services in Houston.">
<meta property="og:image" content="{{ asset('assets_new/Lavish-Ride-Affiliate.jpg') }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="LavishRide - Secure Your Safety" />
<meta property="og:url" content="{{ route('frontEnd.affiliate.thank_you') }}" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@LavishRide" />
<meta name="twitter:title" content="Affiliates - Thank You | LavishRide" />
<meta name="twitter:description" content="Join our affiliate program and earn commission by referring customers to Lavish Ride, the leading provider of private chauffeur services in Houston.">
<meta name="twitter:image" content="{{ asset('assets_new/Lavish-Ride-Affiliate.jpg') }}" />
@endsection

@section('content')
   <!-- Page content START -->
<section class="overflow-hidden pt-0 pt-lg-5">
	<x-general.thank-you-page-card
		image="{{ asset('frontEnd/assets/images/element/congratulations.svg') }}"
		title="Your application has been submitted successfully."
		description="We will review your item shortly. You will be informed by email that your listing has been accepted. Also, make sure your."
	 />
</section>
<!-- Page content END -->

@endsection

