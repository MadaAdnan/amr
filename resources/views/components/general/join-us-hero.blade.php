<section class="pt-4 pt-md-5">
	<div class="container">
		<div class="row g-4 align-items-center">				
			<div class="col-lg-7">
				<!-- Title -->
				<h1 class="mb-4 display-5">{{ $title }} <span class="text-primary">{{ $slogan }}</span></h1>
				<!-- Info -->
				<p class="mb-4">{{ $description }}</p>
				<!-- Button -->
				<a href="{{ $buttonActionLink }}" class="btn btn-primary-soft mb-4">{{ $buttonText }} <i class="fa-solid fa-arrow-right-long fa-fw"></i></a>
				<!-- List -->
				<h6 class="fw-normal mb-1">Create New Listing</h6>
				<ul class="list-group list-group-borderless mb-0 small">
					<li class="list-group-item d-flex mb-0">
						<i class="fa-solid fa-check-circle text-success me-2 mt-1"></i>More than 5.1 million holiday rentals already listed 
					</li>
					<li class="list-group-item d-flex mb-0">
						<i class="fa-solid fa-check-circle text-success me-2 mt-1"></i>Bed one supposing breakfast day fulfilled off depending questions.
					</li>
					<li class="list-group-item d-flex mb-0">
						<i class="fa-solid fa-check-circle text-success me-2 mt-1"></i>The difference in the cost shall be borne by the client in case.
					</li>
				</ul>
			</div>
			<!-- Image -->
			<div class="col-lg-5 text-center">
				<img src="{{ $image }}" alt="join-us">
			</div>
		</div>
	</div>
</section>