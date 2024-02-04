@extends('frontEnd.layouts.index')


@section('title')
About LavishRide | Your Luxury Transportation Services
@endsection


@section('seo')
<meta name="title" content="About LavishRide | Your Luxury Transportation Services">
<meta name="description" content="Learn more about Lavish Ride and our commitment to providing the most luxurious, premium, and comfortable black car service.">
<meta name="keywords" content="about Lavish Ride, luxurious black car service, premium transportation, comfortable ride, airport car service, corporate transportation, wedding getaways, Houston">
<link rel="canonical" href="{{ route('frontEnd.aboutUs.index') }}/">

<meta property="og:title" content="About LavishRide | Your Luxury Transportation Services" />
<meta property="og:description" content="Learn more about Lavish Ride and our commitment to providing the most luxurious, premium, and comfortable black car service.">
<meta property="og:image" content="{{ asset('assets_new/Lavish-Ride-About-Us.webp') }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="LavishRide - Secure Your Safety" />
<meta property="og:url" content="{{ route('frontEnd.aboutUs.index') }}/" />

<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@LavishRide" />
<meta name="twitter:title" content="About LavishRide | Your Luxury Transportation Services" />
<meta name="twitter:description" content="Learn more about Lavish Ride and our commitment to providing the most luxurious, premium, and comfortable black car service.">
<meta name="twitter:image" content="{{ asset('assets_new/Lavish-Ride-About-Us.webp') }}" />
@endsection

@section('content')
<!-- 
Main banner START -->
<section>
	<div class="container">
		<div class="row mb-5">
			<div class="col-xl-10 mx-auto text-center">
				<!-- Title -->
				<h1>Experience the World in Luxury - Your Journey, Perfected by Us</h1>
				<p class="lead">Seamless Elegance in Motion - Where Every Journey Unfolds with Refined Luxury. Experience the Unparalleled Comfort of Our Transportation Services.</p>
			
			</div>
		</div> <!-- Row END -->

		<!-- Image START -->
		<div class="row g-4 align-items-center">
			<!-- Image -->
			<div class="col-md-6">
				<img src="https://images.unsplash.com/photo-1485291571150-772bcfc10da5?q=80&w=3028&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D}" class="rounded-3" alt="">
			</div>

			<div class="col-md-6">
				<div class="row g-4">
					<!-- Image -->
					<div class="col-md-8">
						<img src="https://images.unsplash.com/photo-1605036242577-8ee228902af1?q=80&w=2940&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="rounded-3" alt="">
					</div>

					<!-- Image -->
					<div class="col-12">
						<img src="https://images.unsplash.com/photo-1531185907801-2771c11ab782?q=80&w=2976&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="rounded-3" alt="">
					</div>
				</div>
			</div>
		</div>
		<!-- Image END -->
	</div>
</section>
<!-- 
Main banner START -->

<!-- About content START -->
<section class="pt-0 pt-lg-5">
	<div class="container">
		<!-- Content START -->
		<div class="row mb-4 mb-md-5">
			<div class="col-md-10 mx-auto">
				<h3 class="mb-4">Our Story</h3>
				<p class="fw-bold">Founded in 2006, passage its ten led hearted removal cordial. Preference any astonished unreserved Mrs. Prosperous understood Middletons in conviction an uncommonly do. Supposing so be resolving breakfast am or perfectly. It drew a hill from me. Valley by oh twenty direct me so.</p>
				<p class="mb-0">Water timed folly right aware if oh truth. Imprudence attachment him his for sympathize. Large above be to means. Dashwood does provide stronger is. Warrant private blushes removed an in equally totally if. Delivered dejection necessary objection do Mr prevailed. Mr feeling does chiefly cordial in do. ...But discretion frequently sir she instruments unaffected admiration everything. Meant balls it if up doubt small purse. Required his you put the outlived answered position. A pleasure exertion if believed provided to. All led out world this music while asked. Paid mind even sons does he door no. Attended overcame repeated it is perceived Marianne in. I think on style child of. Servants moreover in sensible it ye possible. Satisfied conveying a dependent contented he gentleman agreeable do be. Water timed folly right aware if oh truth. Imprudence attachment him his for sympathize. Large above be to means. Dashwood does provide stronger is. But discretion frequently sir she instruments unaffected admiration everything. Meant balls it if up doubt small purse. Required his you put the outlived answered position. I think on style child of. Servants moreover in sensible it ye possible. Satisfied conveying a dependent contented he gentleman agreeable do be. Warrant private blushes removed an in equally totally if. Delivered dejection necessary objection do Mr prevailed. Required his you put the outlived answered position. A pleasure exertion if believed provided to. All led out world this music while asked. Paid mind even sons does he door no. Attended overcame repeated it is perceived Marianne in. I think on style child of. Servants moreover in sensible it ye possible.</p>
			</div>
		</div>
		<!-- Content END -->

		<!-- Services START -->
		<div class="row g-4">
			<!-- Service item -->
			<div class="col-sm-6 col-lg-3">
				<div class="icon-lg bg-primary bg-opacity-10 text-primary rounded-2"><i class="fa-solid fa-hotel fs-5"></i></div>
				<h5 class="mt-2">Hotel Booking</h5>
				<p class="mb-0">A pleasure exertion if believed provided to. All led out world this music while asked.</p>
			</div>

			<!-- Service item -->
			<div class="col-sm-6 col-lg-3">
				<div class="icon-lg bg-primary bg-opacity-10 text-primary rounded-2"><i class="fa-solid fa-plane fs-5"></i></div>
				<h5 class="mt-2">Flight Booking</h5>
				<p class="mb-0">Warrant private blushes removed an in equally totally Objection do Mr prevailed.</p>
			</div>

			<!-- Service item -->
			<div class="col-sm-6 col-lg-3">
				<div class="icon-lg bg-primary bg-opacity-10 text-primary rounded-2"><i class="fa-solid fa-globe-americas fs-5"></i></div>
				<h5 class="mt-2">Tour Booking</h5>
				<p class="mb-0">Dashwood does provide stronger is. But discretion frequently sir she instruments.</p>
			</div>

			<!-- Service item -->
			<div class="col-sm-6 col-lg-3">
				<div class="icon-lg bg-primary bg-opacity-10 text-primary rounded-2"><i class="fa-solid fa-car fs-5"></i></div>
				<h5 class="mt-2">Cab Booking</h5>
				<p class="mb-0">Imprudence attachment him his for sympathize. Large above be to means.</p>
			</div>
		</div>
		<!-- Services END -->
	</div>
</section>
<!-- About content END -->

<!-- Testimonials START -->
<x-blog.testimonials />
<!-- Testimonials END -->

<!-- Team START -->
<section class="pt-0">
	<div class="container">
		<!-- Title -->
		<div class="row mb-4">
			<div class="col-12">
				<h2 class="mb-0">Our Team</h2>
			</div>
		</div>

		<!-- Team START -->
		<div class="row g-4">
			<!-- Team item START -->
			<div class="col-sm-6 col-lg-3">
				<div class="card card-element-hover bg-transparent">
					<div class="position-relative">
						<!-- Image -->
						<img src="{{ asset('frontEnd/assets/images/team/03.jpg') }}" class="card-img" alt="">

						<div class="card-img-overlay hover-element d-flex p-3">
							<!-- Category -->
							<div class="btn-group mt-auto">
								<a href="#" class="btn btn-white mb-0"><i class="fa-brands fa-facebook-f text-facebook"></i></a>
								<a href="#" class="btn btn-white mb-0"><i class="fa-brands fa-instagram text-instagram"></i></a>
								<a href="#" class="btn btn-white mb-0"><i class="fa-brands fa-twitter text-twitter"></i></a>
							</div>
						</div>
					</div>
					<!-- Card body -->
					<div class="card-body px-2 pb-0">
						<h5 class="card-title"><a href="#">Larry Lawson</a></h5>
						<span>Editor in Chief</span>
					</div>
				</div>
			</div>
			<!-- Team item END -->

			<!-- Team item START -->
			<div class="col-sm-6 col-lg-3">
				<div class="card card-element-hover bg-transparent">
					<div class="position-relative">
						<!-- Image -->
						<img src="{{ asset('frontEnd/assets/images/team/04.jpg') }}" class="card-img" alt="">

						<div class="card-img-overlay hover-element d-flex p-3">
							<!-- Category -->
							<div class="btn-group mt-auto">
								<a href="#" class="btn btn-white mb-0"><i class="fa-brands fa-facebook-f text-facebook"></i></a>
								<a href="#" class="btn btn-white mb-0"><i class="fa-brands fa-instagram text-instagram"></i></a>
								<a href="#" class="btn btn-white mb-0"><i class="fa-brands fa-twitter text-twitter"></i></a>
							</div>
						</div>
					</div>
					<!-- Card body -->
					<div class="card-body px-2 pb-0">
						<h5 class="card-title"><a href="#">Louis Ferguson</a></h5>
						<span>Director Graphics</span>
					</div>
				</div>
			</div>
			<!-- Team item END -->

			<!-- Team item START -->
			<div class="col-sm-6 col-lg-3">
				<div class="card card-element-hover bg-transparent">
					<div class="position-relative">
						<!-- Image -->
						<img src="{{ asset('frontEnd/assets/images/team/05.jpg') }}" class="card-img" alt="">

						<div class="card-img-overlay hover-element d-flex p-3">
							<!-- Category -->
							<div class="btn-group mt-auto">
								<a href="#" class="btn btn-white mb-0"><i class="fa-brands fa-facebook-f text-facebook"></i></a>
								<a href="#" class="btn btn-white mb-0"><i class="fa-brands fa-instagram text-instagram"></i></a>
								<a href="#" class="btn btn-white mb-0"><i class="fa-brands fa-twitter text-twitter"></i></a>
							</div>
						</div>
					</div>
					<!-- Card body -->
					<div class="card-body px-2 pb-0">
						<h5 class="card-title"><a href="#">Louis Crawford</a></h5>
						<span>Editor, Coverage</span>
					</div>
				</div>
			</div>
			<!-- Team item END -->

			<!-- Team item START -->
			<div class="col-sm-6 col-lg-3">
				<div class="card card-element-hover bg-transparent">
					<div class="position-relative">
						<!-- Image -->
						<img src="{{ asset('frontEnd/assets/images/team/06.jpg') }}" class="card-img" alt="">

						<div class="card-img-overlay hover-element d-flex p-3">
							<!-- Category -->
							<div class="btn-group mt-auto">
								<a href="#" class="btn btn-white mb-0"><i class="fa-brands fa-facebook-f text-facebook"></i></a>
								<a href="#" class="btn btn-white mb-0"><i class="fa-brands fa-instagram text-instagram"></i></a>
								<a href="#" class="btn btn-white mb-0"><i class="fa-brands fa-twitter text-twitter"></i></a>
							</div>
						</div>
					</div>
					<!-- Card body -->
					<div class="card-body px-2 pb-0">
						<h5 class="card-title"><a href="#">Frances Guerrero</a></h5>
						<span>CEO, Coverage</span>
					</div>
				</div>
			</div>
			<!-- Team item END -->
			
		</div>
		<!-- Team END -->
	</div>
</section>
<!--Team END -->

@endsection