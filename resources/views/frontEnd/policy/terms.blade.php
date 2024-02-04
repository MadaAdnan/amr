@extends('frontEnd.layouts.index')

@section('title')
Terms and Conditions - Lavish Ride
@endsection

@section('seo')
<meta name="title" content="Terms and Conditions - Lavish Ride">
<meta name="description" content="Read the terms and conditions of using LavishRide's chauffeur services. Find important information about booking, cancellation policy & payment methods.">
<meta name="keywords" content="terms and conditions, Lavish Ride terms, chauffeur service terms, booking terms, cancellation policy, payment methods, liability, terms, conditions, partner">
<link rel="canonical" href="{{ route('frontEnd.policy.terms_condition') }}/">

<meta property="og:title" content="Terms and Conditions - Lavish Ride" />
<meta property="og:description" content="Read the terms and conditions of using LavishRide's chauffeur services. Find important information about booking, cancellation policy & payment methods.">
<meta property="og:image" content="{{ asset("assets_new/img/LR-LogoSchema.png") }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="LavishRide - Secure Your Safety" />
<meta property="og:url" content="{{ route('frontEnd.policy.terms_condition') }}/" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@LavishRide" />
<meta name="twitter:title" content="Terms and Conditions - Lavish Ride" />
<meta name="twitter:description" content="Read the terms and conditions of using LavishRide's chauffeur services. Find important information about booking, cancellation policy & payment methods.">
<meta name="twitter:image" content="{{ asset("assets_new/img/LR-LogoSchema.png") }}" />
@endsection

@section('content')
<!--Main banner START -->
<section class="pt-4 pt-lg-5">
	<div class="container">

		<!-- Title -->
		<x-general.policy-title title="Terms Of Service" />

		<!-- Content START -->
        <x-general.policy-content :content="$data" />
		<!-- Content END -->

	</div>
</section>
<!--Main banner END -->

@endsection


