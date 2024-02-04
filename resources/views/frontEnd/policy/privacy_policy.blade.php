@extends('frontEnd.layouts.index')

@section('title')
Privacy Policy - Lavish Ride
@endsection

@section('seo')
<meta name="title" content="Privacy Policy - Lavish Ride">
<meta name="description" content="Learn how Lavish Ride collects, uses, and protects your personal information when you use our chauffeur services.">
<meta name="keywords" content="privacy policy, data protection, personal information, security, Lavish Ride privacy, chauffeur service privacy">
<link rel="canonical" href="{{ route('frontEnd.policy.privacy_policy') }}/">

<meta property="og:title" content="Privacy Policy - Lavish Ride" />
<meta property="og:description" content="Get answers to common questions about Lavish Ride's chauffeur services. Find information about reservations, rates, vehicle types, and airport transfers.">
<meta property="og:image" content="{{ asset("assets_new/img/LR-LogoSchema.png") }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="LavishRide - Secure Your Safety" />
<meta property="og:url" content="{{ route('frontEnd.policy.privacy_policy') }}/" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@LavishRide" />
<meta name="twitter:title" content="Privacy Policy - Lavish Ride" />
<meta name="twitter:description" content="Get answers to common questions about Lavish Ride's chauffeur services. Find information about reservations, rates, vehicle types, and airport transfers.">
<meta name="twitter:image" content="{{ asset("assets_new/img/LR-LogoSchema.png") }}" />
@endsection

@section('content')
<!--Main banner START -->
<section class="pt-4 pt-lg-5">
	<div class="container">

		<!-- Title -->
		<x-general.policy-title title="Privacy Policy" />

		<!-- Content START -->
        <x-general.policy-content :content="$data" />
		<!-- Content END -->

	</div>
</section>
<!--Main banner END -->

@endsection


