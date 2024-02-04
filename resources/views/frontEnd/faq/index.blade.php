@extends('frontEnd.layouts.index')

@section('title')
    Frequently Asked Questions - Lavish Ride
@endsection

@section('seo')
<meta name="title" content="Frequently Asked Questions - Lavish Ride">
<meta name="description" content="Get answers to common questions about Lavish Ride's chauffeur services. Find information about reservations, rates, vehicle types, and airport transfers.">
<meta name="keywords" content="frequently asked questions, FAQs, reservations, rates, vehicle types, airport transfers, chauffeur service information">
<link rel="canonical" href="{{ route('frontEnd.faq.index') }}/">

<meta property="og:title" content="Frequently Asked Questions - Lavish Ride" />
<meta property="og:description" content="Get answers to common questions about Lavish Ride's chauffeur services. Find information about reservations, rates, vehicle types, and airport transfers.">
<meta property="og:image" content="{{ asset("assets_new/img/LR-LogoSchema.png") }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="LavishRide - Secure Your Safety" />
<meta property="og:url" content="{{ route('frontEnd.faq.index') }}" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@LavishRide" />
<meta name="twitter:title" content="Frequently Asked Questions - Lavish Ride" />
<meta name="twitter:description" content="Get answers to common questions about Lavish Ride's chauffeur services. Find information about reservations, rates, vehicle types, and airport transfers.">
<meta name="twitter:image" content="{{ asset("assets_new/img/LR-LogoSchema.png") }}" />
@endsection

@section('content')

<!-- Main banner START -->
<section class="pt-4 pt-lg-5">
	<div class="container">
		<div class="row">
			<div class="col-lg-10 mx-auto text-center">
				<!-- Title -->
				<h6 class="text-primary">Faqs</h6>
				<h1>Frequently Asked Questions</h1>
				<!-- Info -->
				<p>have questions? We're here to help you</p>
			</div>
		</div>
	</div>
</section>
<!-- Main banner END -->

<!-- Faqs START -->
<section class="pt-0">
	<div class="container">
		<div class="row">
			<div class="col-xl-10 mx-auto">
				<div class="vstack gap-4">
					<!-- Card item START -->
					<div class="card border bg-transparent p-0">
						<!-- Card header -->
						<div class="card-header bg-transparent border-bottom p-4">
							<h5 class="mb-0">Professional Chauffeur</h5>
						</div>

						<!-- Card body -->
						<div class="card-body p-4 pt-0">
                            @foreach ($professionalChauffeur as $item)
                                <!-- Faq item -->
                                <x-general.faq-item question='{{ $item->question }}' answer='{{ $item->answer }}' />
                            @endforeach
						</div>
					</div>
					<!-- Card item END -->

					<!-- Card item START -->
					<div class="card bg-transparent border p-0">
						<!-- Card header -->
						<div class="card-header bg-transparent border-bottom p-4">
							<h5 class="mb-0">Cancellations & Refunds</h5>
						</div>

						<!-- Card body -->
						<div class="card-body p-4 pt-0">
							  @foreach ($cancellationAndRefundType as $item)
                                <!-- Faq item -->
                                <x-general.faq-item question='{{ $item->question }}' answer='{{ $item->answer }}' />
                            @endforeach
						</div>
					</div>
					<!-- Card item END -->

					<!-- Card item START -->
					<div class="card bg-transparent border p-0">
						<!-- Card header -->
						<div class="card-header bg-transparent border-bottom p-4">
							<h5 class="mb-0">General</h5>
						</div>

						<!-- Card body -->
						<div class="card-body p-4 pt-0">
							<!-- Faq item -->
                            @foreach ($general as $item)
                                <!-- Faq item -->
                                <x-general.faq-item question='{{ $item->question }}' answer='{{ $item->answer }}' />
                            @endforeach
						</div>
					</div>
					<!-- Card item END -->
				</div>		
			</div>
		</div>
	</div>
</section>
<!-- Faqs END -->

@endsection

