@extends('frontEnd.layouts.index')

@section('title')
Fleet | Lavish Ride
@endsection

@php
    #get the number of categories
    $totalFleetCategories = count($data);
@endphp

@section('seo')
    <meta name="title" content="Fleet | Lavish Ride">
    <meta name="description"
        content="Mercedes-Benz Executive Sprinter, Cadillac Escalade ESV, Lincoln Navigator XL, Yukon Denali, Chevy Suburban, Cadillac XTS">
    <meta name="keywords" content="fleet, executive sprinter, Mercedes-Benz, Cadillac, Lincoln, Yukon, Chevy, Cadillac XTS">
    <link rel="canonical" href="{{ Request::url() }}" />

    <meta property="og:title" content="Fleet | Lavish Ride" />
    <meta property="og:description"
        content="Mercedes-Benz Executive Sprinter, Cadillac Escalade ESV, Lincoln Navigator XL, Yukon Denali, Chevy Suburban, Cadillac XTS.">
    <meta property="og:image" content="{{ asset('assets_new/Our-Fleet-Banner.webp') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="LavishRide - Secure Your Safety" />
    <meta property="og:url" content="{{ Request::url() }}" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@LavishRide" />
    <meta name="twitter:title" content="Fleet | Lavish Ride" />
    <meta name="twitter:description"
        content="Mercedes-Benz Executive Sprinter, Cadillac Escalade ESV, Lincoln Navigator XL, Yukon Denali, Chevy Suburban, Cadillac XTS.">
    <meta name="twitter:image" content="{{ asset('assets_new/Our-Fleet-Banner.webp') }}" />
@endsection





@section('content')
	<!-- Main Banner START -->
<section class="pt-0">
	<div class="container">
		<!-- Background image -->
		<div class="p-sm-5 rounded-3 hero__image__fleet">
			<!-- Banner title -->
			<div class="row"> 
				<div class="col-md-8 mx-auto my-5"> 
					<h1 class="text-center fleet__text text-white">Fleet</h1>
                    <span class="fleet__text text-white">
                        At Lavish Ride- Black Car Services provider, we take pride in offering an unparalleled fleet of meticulously curated vehicles designed to elevate your transportation experience.
                    </span>
				</div>
			</div>
		</div>


	</div>
</section>
<!--Main Banner END -->

<!--Tour grid START -->
<section class="pt-0">
	<div class="container">

		<!-- Filter and content START -->
		<div class="row g-4 align-items-center justify-content-between mb-4">
			<!-- Content -->
			<div class="col-12 col-xl-8">
				<h5 class="mb-0"> {{ $totalFleetCategories != 0 ? "We Have $totalFleetCategories Fleet's Category Available" : 'No Data'}}</h5>
			</div>			
		</div>
		<!-- Filter and content END -->

		<div class="row g-4">
            @foreach ($data as $item)
                <!-- Card item START -->
                <x-blog.fleet-card  :item="$item" />
                <!-- Card item END -->                
            @endforeach

		</div> <!-- Row END -->
		
	</div>
</section>
<!--Tour grid END -->

@endsection