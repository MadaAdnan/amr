@extends('frontEnd.layouts.index')


@section('title')
    {{$data->title}}
@endsection


@section('seo')
    <meta name="title" content="{{ $data->seo_title }}">
    <meta name="description" content="{{ $data->seo_description }}">
    <meta name="keywords" content="{{ $data->seo_keyphrase }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('frontEnd.fleets.details',$data->slug) }}" />

    <meta property="og:title" content="{{ $data->seo_title }}" />
    <meta property="og:description" content="{{ $data->seo_description }}">
    <meta property="og:image" content="{{ asset("assets_new/Lavish-Ride-Fleet-Category.jpg") }}" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="LavishRide - Secure Your Safety" />
    <meta property="og:url" content="{{ route('frontEnd.fleets.details',$data->slug) }}" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@LavishRide" />
    <meta name="twitter:title" content="{{ $data->seo_title }}" />
    <meta name="twitter:description" content="{{ $data->seo_description }}">
    <meta name="twitter:image" content="{{ asset("assets_new/Lavish-Ride-Fleet-Category.jpg") }}" />
@endsection



@section('content')
<!--Main Title START -->
<section class="py-0 pt-sm-5">
	<div class="container position-relative">
		<!-- Title and button START -->
		<div class="row mb-3">
			<div class="col-12">
				<!-- Meta -->
				<div class="d-lg-flex justify-content-lg-between mb-1">
					<!-- Title -->
					<div class="mb-2 mb-lg-0">
						<h1 class="fs-2">{{ $data->title }}</h1>
					</div>

				</div>
			</div>
		</div>
		<!-- Title and button END -->

	</div>
</section>
<!--Main Title END -->

<!--Hero Image START -->
<section class="card-grid pt-0">
	<div class="container">
		<div class="row g-2">
			<!-- Image -->
			<div class="col-md-12">
				<a data-glightbox data-gallery="gallery" href="{{ $data->avatar }}">
					<div class="card card-grid-lg card-element-hover card-overlay-hover overflow-hidden" style="background-image:url('{{ $data->avatar }}'); background-position: center left; background-size: cover;">
						<!-- Card hover element -->
						<div class="hover-element position-absolute w-100 h-100">
							<i class="bi bi-fullscreen fs-6 text-white position-absolute top-50 start-50 translate-middle bg-dark rounded-1 p-2 lh-1"></i>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>
</section>
<!--Hero Image END -->

<!--About hotel START -->
<section class="pt-0">
	<div class="container" data-sticky-container>
		<div class="row g-4 g-xl-5">
			<!-- Content START -->
			<div class="col-xl-7 order-1">
				<div class="vstack gap-5">

					<!-- About hotel START -->
					<div class="card bg-transparent">
						<!-- Card header -->
						<div class="card-header border-bottom bg-transparent px-0 pt-0">
							<h3 class="mb-0">About This Fleet</h3>
						</div>

						<!-- Card body START -->
						<div class="card-body pt-4 p-0 mb-3">
							<h5 class="fw-light mb-4">Main Highlights</h5>

							<!-- Highlights Icons -->
							<div class="hstack gap-3 pb-4">
								<div class="icon-lg bg-light h5 rounded-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Free wifi">
									<i class="fa-solid fa-person"></i>
                                    <h6 class="mt-2">
                                        {{ $data->passengers }}
                                    </h6>
								</div>
								<div class="icon-lg bg-light h5 rounded-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Swimming Pool">
									<i class="fa-solid fa-person-walking-luggage"></i>
                                    <h6 class="mt-2">
                                        {{ $data->luggage }}
                                    </h6>
								</div>
								<div class="icon-lg bg-light h5 rounded-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Central AC">
									<i class="fa-solid fa-plane"></i>
                                    <h6 class="mt-2">
                                        {{ $data->flight_tracking == 1 ? "Yes" : "No" }}
                                    </h6>
								</div>
							</div>

                            <p class="my-3">{{ $data->category_description }}</p>

							<!-- List -->
							<h5 class="fw-light mb-2">Advantages</h5>
							<ul class="list-group list-group-borderless mb-0">
								<li class="list-group-item h6 fw-light d-flex mb-0"><i class="bi bi-patch-check-fill text-success me-2"></i>Every hotel staff to have Proper PPT kit for COVID-19</li>
								<li class="list-group-item h6 fw-light d-flex mb-0"><i class="bi bi-patch-check-fill text-success me-2"></i>Every staff member wears face masks and gloves at all service times.</li>
								<li class="list-group-item h6 fw-light d-flex mb-0"><i class="bi bi-patch-check-fill text-success me-2"></i>Hotel staff ensures to maintain social distancing at all times.</li>
								<li class="list-group-item h6 fw-light d-flex mb-0"><i class="bi bi-patch-check-fill text-success me-2"></i>The hotel has In-Room Dining options available </li>
							</ul>
						</div>
						<!-- Card body END -->
					</div>
					<!-- About hotel START -->

					<!-- Amenities START -->
					<div class="card bg-transparent">
						<!-- Card header -->
						<div class="card-header border-bottom bg-transparent px-0 pt-0">
							<h3 class="card-title mb-0">Amenities</h3>
						</div>

						<!-- Card body START -->
						<div class="card-body pt-4 p-0">
							<div class="row g-4">
								<!-- Activities -->
								<div class="col-sm-6">
									<h6><i class="fa-solid fa-biking me-2"></i>Activities</h6>
									<!-- List -->
									<ul class="list-group list-group-borderless mt-2 mb-0">
										<li class="list-group-item pb-0">
											<i class="fa-solid fa-check-circle text-success me-2"></i>Swimming pool
										</li>
										<li class="list-group-item pb-0">
											<i class="fa-solid fa-check-circle text-success me-2"></i>Spa
										</li>
										<li class="list-group-item pb-0">
											<i class="fa-solid fa-check-circle text-success me-2"></i>Kids' play area
										</li>
										<li class="list-group-item pb-0">
											<i class="fa-solid fa-check-circle text-success me-2"></i>Gym
										</li>
									</ul>
								</div>
	
								<!-- Payment Method -->
								<div class="col-sm-6">
									<h6><i class="fa-solid fa-credit-card me-2"></i>Payment Method</h6>
									<!-- List -->
									<ul class="list-group list-group-borderless mt-2 mb-0">
										<li class="list-group-item pb-0">
											<i class="fa-solid fa-check-circle text-success me-2"></i>Credit card (Visa, Master card)
										</li>
										<li class="list-group-item pb-0">
											<i class="fa-solid fa-check-circle text-success me-2"></i>Cash
										</li>
										<li class="list-group-item pb-0">
											<i class="fa-solid fa-check-circle text-success me-2"></i>Debit Card
										</li>
									</ul>
								</div>
	
								<!-- Services -->
								<div class="col-sm-6">
									<h6><i class="fa-solid fa-concierge-bell me-2"></i>Services</h6>
									<!-- List -->
									<ul class="list-group list-group-borderless mt-2 mb-0">
										<li class="list-group-item pb-0">
											<i class="fa-solid fa-check-circle text-success me-2"></i>Dry cleaning
										</li>
										<li class="list-group-item pb-0">
											<i class="fa-solid fa-check-circle text-success me-2"></i>Room Service
										</li>
										<li class="list-group-item pb-0">
											<i class="fa-solid fa-check-circle text-success me-2"></i>Special service
										</li>
										<li class="list-group-item pb-0">
											<i class="fa-solid fa-check-circle text-success me-2"></i>Waiting Area
										</li>
										<li class="list-group-item pb-0">
											<i class="fa-solid fa-check-circle text-success me-2"></i>Secrete smoking area
										</li>
										<li class="list-group-item pb-0">
											<i class="fa-solid fa-check-circle text-success me-2"></i>Concierge
										</li>
										<li class="list-group-item pb-0">
											<i class="fa-solid fa-check-circle text-success me-2"></i>Laundry facilities
										</li>
										<li class="list-group-item pb-0">
											<i class="fa-solid fa-check-circle text-success me-2"></i>Ironing Service
										</li>
										<li class="list-group-item pb-0">
											<i class="fa-solid fa-check-circle text-success me-2"></i>Lift
										</li>
									</ul>
								</div>
	
								<!-- Safety & Security -->
								<div class="col-sm-6">
									<h6><i class="bi bi-shield-fill-check me-2"></i>Safety & Security</h6>
									<!-- List -->
									<ul class="list-group list-group-borderless mt-2 mb-4 mb-sm-5">
										<li class="list-group-item pb-0">
											<i class="fa-solid fa-check-circle text-success me-2"></i>Doctor on Call
										</li>
									</ul>
	
									<h6><i class="fa-solid fa-volume-up me-2"></i>Staff Language</h6>
									<!-- List -->
									<ul class="list-group list-group-borderless mt-2 mb-0">
										<li class="list-group-item pb-0">
											<i class="fa-solid fa-check-circle text-success me-2"></i>English
										</li>
										<li class="list-group-item pb-0">
											<i class="fa-solid fa-check-circle text-success me-2"></i>Spanish
										</li>
										<li class="list-group-item pb-0">
											<i class="fa-solid fa-check-circle text-success me-2"></i>Hindi
										</li>
									</ul>
								</div>
	
							</div>
						</div>
						<!-- Card body END -->
					</div>
					<!-- Amenities END -->

					

					<!-- Our Policies START -->
					<div class="card bg-transparent">
						<!-- Card header -->
						<div class="card-header border-bottom bg-transparent px-0 pt-0">
							<h3 class="mb-0">Our Policies</h3>
						</div>

						<!-- Card body START -->
						<div class="card-body pt-4 p-0">
							<!-- List -->
							<ul class="list-group list-group-borderless mb-2">
								<li class="list-group-item d-flex">
									<i class="bi bi-check-circle-fill me-2"></i>This is a family farmhouse, hence we request you to not indulge.
								</li>
								<li class="list-group-item d-flex">
									<i class="bi bi-check-circle-fill me-2"></i>Drinking and smoking within controlled limits are permitted at the farmhouse but please do not create a mess or ruckus at the house.
								</li>
								<li class="list-group-item d-flex">
									<i class="bi bi-check-circle-fill me-2"></i>Drugs and intoxicating illegal products are banned and not to be brought to the house or consumed.
								</li>
								<li class="list-group-item d-flex">
									<i class="bi bi-x-circle-fill me-2"></i>For any update, the customer shall pay applicable cancellation/modification charges.
								</li>
							</ul>

							<!-- List -->
							<ul class="list-group list-group-borderless mb-2">
								<li class="list-group-item h6 fw-light d-flex mb-0">
									<i class="bi bi-arrow-right me-2"></i>Check-in: 1:00 pm - 9:00 pm
								</li>
								<li class="list-group-item h6 fw-light d-flex mb-0">
									<i class="bi bi-arrow-right me-2"></i>Check out: 11:00 am
								</li>
								<li class="list-group-item h6 fw-light d-flex mb-0">
									<i class="bi bi-arrow-right me-2"></i>Self-check-in with building staff
								</li>
								<li class="list-group-item h6 fw-light d-flex mb-0">
									<i class="bi bi-arrow-right me-2"></i>No pets
								</li>
								<li class="list-group-item h6 fw-light d-flex mb-0">
									<i class="bi bi-arrow-right me-2"></i>No parties or events
								</li>
								<li class="list-group-item h6 fw-light d-flex mb-0">
									<i class="bi bi-arrow-right me-2"></i>Smoking is allowed
								</li>
							</ul>

						</div>
						<!-- Card body END -->
					</div>
					<!-- Our Policies START -->
				</div>	
			</div>
			<!-- Content END -->

			<!-- Right side content START -->
			<aside class="col-xl-5 order-xl-2">
				<div data-sticky data-margin-top="100" data-sticky-for="1199">
					<!-- Book now START -->
					<div class="card card-body border">
						
						<!-- Title -->
						<div class="d-sm-flex justify-content-sm-between align-items-center mb-3">
							<div>
								<h4 class="card-title mb-0">Do you like this fleet ?</h4>
							</div>
							
						</div>		

					

						<!-- Button -->
						<div class="d-grid">
							<a href="{{ route('frontEnd.reservations.choose_location') }}" class="btn btn-lg btn-primary-soft mb-0">MAKE A RESERVATION NOW !</a>
						</div>
					</div>
					<!-- Book now END -->

                    <!-- Room START -->
					<div class="card bg-transparent mt-5" id="room-options">
						<!-- Card header -->
						<div class="card-header border-bottom bg-transparent px-0 pt-0">
							<div class="d-sm-flex justify-content-sm-between align-items-center">
								<h3 class="mb-2 mb-sm-0">Fleet Options</h3>
						</div>

						<!-- Card body START -->
						<div class="card-body pt-4 p-0">
							<div class="vstack gap-4">
                                @foreach ($data->fleets as $item)
                                    <!-- Fleet item START -->
                                    <div class="card shadow p-3">
                                        <div class="row g-4">
                                            <!-- Card img -->
                                            <div class="col-md-5 position-relative">
                                                <!-- Image START -->
                                                <div class="tiny-slider arrow-round arrow-xs arrow-dark overflow-hidden rounded-2">
                                                    <div class="tiny-slider-inner" data-autoplay="true" data-arrow="true" data-dots="false" data-items="1">
                                                        <!-- Image item -->
                                                        <div><img src="{{ $item->avatar }}" alt="Card image"></div>
                                                    </div>
                                                </div>
                                                <!-- Image END -->
                                            </div>

                                            <!-- Card body -->
                                            <div class="col-md-7">
                                                <div class="card-body d-flex flex-column h-100 p-0">
                        
                                                    <!-- Title -->
                                                    <h5 class="card-title"><a href="#">{{ $data->title }}</a></h5>	
                                                    <span>Learn More</span>											
                                                    <!-- Price and Button -->
                                                    <div class="d-sm-flex justify-content-sm-between align-items-center mt-auto">
                                                        <!-- Button -->													               
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fleet item END -->
                                @endforeach
							</div>
						</div>
						<!-- Card body END -->
					</div>
					<!-- Room END -->
				</div>	
			</aside>
			<!-- Right side content END -->
		</div> <!-- Row END -->
	</div>
</section>
<!--About hotel END -->

@endsection