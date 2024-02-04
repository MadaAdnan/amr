@extends('frontEnd.layouts.index')

@section('title')
Affiliates - Let's Grow together
@endsection


@section('seo')
<meta name="title" content="Affiliates - Let's Grow together | LavishRide">
<meta name="description" content="Join our affiliate program and earn commission by referring customers to Lavish Ride, the leading provider of private chauffeur services in Houston.">
<meta name="keywords" content="service partners, business partners, looking for a business partner, business partners international, need business partner, affiliate program, referral program, commission, private chauffeur services, affiliate categories, affiliate marketing, affiliate links, affiliate partner program, affiliate partner, affiliate partnership">
<link rel="canonical" href="{{ route('frontEnd.affiliate.index') }}">
<meta property="og:title" content="Affiliates - Let's Grow together | LavishRide" />
<meta property="og:description" content="Join our affiliate program and earn commission by referring customers to Lavish Ride, the leading provider of private chauffeur services in Houston.">
<meta property="og:image" content="{{ asset('assets_new/Lavish-Ride-Affiliate.jpg') }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="LavishRide - Secure Your Safety" />
<meta property="og:url" content="{{ route('frontEnd.affiliate.index') }}" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@LavishRide" />
<meta name="twitter:title" content="Affiliates - Let's Grow together | LavishRide" />
<meta name="twitter:description" content="Join our affiliate program and earn commission by referring customers to Lavish Ride, the leading provider of private chauffeur services in Houston.">
<meta name="twitter:image" content="{{ asset('assets_new/Lavish-Ride-Affiliate.jpg') }}" />
@endsection

@section('content')
<!-- Main Banner START -->

<x-general.join-us-hero 
title="Showcase Your" 
slogan="Luxury Fleet with Us" 
description="Speedily say has suitable disposal add boy. On forth doubt miles of child. Exercise joy man children rejoiced."
image="{{ asset('frontEnd/assets/images/element/join-us-3.svg') }}"
buttonActionLink="{{ route('frontEnd.affiliate.create') }}"
buttonText="JoinUs"
/>

<!--Main Banner END -->

<!--Counter START -->
<section class="pt-0">
	<div class="container">
		<div class="row g-4 align-items-center">
			<!-- Title -->
			<div class="col-sm-6 col-md-3">
				<h3 class="mb-0 text-center">Our Achievements</h3>
			</div>
			
			<!-- Counter item -->
			<div class="col-sm-6 col-md-3">
				<div class="card card-body text-center bg-light">
					<div class="d-flex justify-content-center">
						<h3 class="purecounter text-primary mb-0" data-purecounter-start="0" data-purecounter-end="50"	data-purecounter-delay="100">0</h3>
						<span class="h3 text-primary mb-0">K+</span>
					</div>
					<h6 class="fw-normal mb-0">Completed Rides</h6>
				</div>
			</div>
			
			<!-- Counter item -->
			<div class="col-sm-6 col-md-3">
				<div class="card card-body text-center bg-light">
					<div class="d-flex justify-content-center">
						<h3 class="purecounter text-primary mb-0" data-purecounter-start="0" data-purecounter-end="30"	data-purecounter-delay="100">0</h3>
						<span class="h3 text-primary mb-0">K+</span>
					</div>
					<h6 class="fw-normal mb-0">Clients</h6>
				</div>
			</div>
			
			<!-- Counter item -->
			<div class="col-sm-6 col-md-3">
				<div class="card card-body text-center bg-light">
					<div class="d-flex justify-content-center">
						<h3 class="purecounter text-primary mb-0" data-purecounter-start="0" data-purecounter-end="2"	data-purecounter-delay="100">0</h3>
						<span class="h3 text-primary mb-0">M</span>
					</div>
					<h6 class="fw-normal mb-0">Miles</h6>
				</div>
			</div>
		</div>
	</div>
</section>
<!--Counter END -->

<!--Benefits START -->
<x-general.benefits />
<!--Benefits END -->

<!--Testimonials START -->
<x-blog.testimonials />
<!--Testimonials END -->

@endsection

@section('script')
    <script src="{{ asset('frontEnd/assets/vendor/purecounterjs/dist/purecounter_vanilla.js') }}"></script>
@endsection
