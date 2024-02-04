@extends('frontEnd.layouts.index')

@section('title')
    Contact Lavish Ride - Chauffeur Services in Houston
@endsection

@section('seo')
<meta name="title" content="Contact Lavish Ride - Chauffeur Services in Houston">
<meta name="description" content="Contact Lavish Ride for any inquiries, feedback, or to book our luxury chauffeur services. Our customer support team is available 24/7 to assist you.">
<meta name="keywords" content="contact Lavish Ride, customer support, chauffeur services, inquiries, feedback, booking, luxury transportation, Houston">
<link rel="canonical" href="{{ route('frontEnd.contactUs.index') }}/">

<meta property="og:title" content="Contact Lavish Ride - Chauffeur Services in Houston" />
<meta property="og:description" content="Contact Lavish Ride for any inquiries, feedback, or to book our luxury chauffeur services. Our customer support team is available 24/7 to assist you.">
<meta property="og:image" content="{{ asset("assets_new/Lavish-Ride-Contact-Us.jpg") }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="LavishRide - Secure Your Safety" />
<meta property="og:url" content="{{ route('frontEnd.contactUs.index') }}" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@LavishRide" />
<meta name="twitter:title" content="Contact Lavish Ride - Chauffeur Services in Houston" />
<meta name="twitter:description" content="Contact Lavish Ride for any inquiries, feedback, or to book our luxury chauffeur services. Our customer support team is available 24/7 to assist you.">
<meta name="twitter:image" content="{{ asset("assets_new/Lavish-Ride-Contact-Us.jpg") }}" />
<script src="https://www.google.com/recaptcha/api.js?render=6LdgHg0pAAAAAFhL_RAaPQACCBMaD9ZM9AOUKS4M" async defer></script>

@endsection

@section('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.15/build/css/intlTelInput.css">
@endsection

@section('content')
<!-- Main banner START -->
<section class="pt-4 pt-md-5">
	<div class="container">
		<div class="row mb-5">
			<div class="col-xl-10">
				<!-- Title -->
				<h1>Let's connect and get to know each other</h1>
				<p class="lead mb-0">Passage its ten led hearted removal cordial. Preference any astonished unreserved Mrs. Prosperous understood Middletons. Preference for any astonished unreserved.</p>
			</div>
		</div>

		<!-- Contact info -->
		<div class="row g-4">

			<!-- Contact item START -->
			<div class="col-md-6 col-xl-4">
				<div class="card card-body shadow text-center align-items-center h-100">
					<!-- Icon -->
					<div class="icon-lg bg-info bg-opacity-10 text-info rounded-circle mb-2"><i class="bi bi-headset fs-5"></i></div>
					<!-- Title -->
					<h5>Call us</h5>
					<p>Imprudence attachment him his for sympathize. Large above be to means.</p>
					<!-- Buttons -->
					<div class="d-grid gap-3 d-sm-block">
						<button class="btn btn-sm btn-primary-soft"><i class="bi bi-phone me-2"></i>{{ config('general.support_phone') }}</button>
					</div>
				</div>
			</div>
			<!-- Contact item END -->

			<!-- Contact item START -->
			<div class="col-md-6 col-xl-4">
				<div class="card card-body shadow text-center align-items-center h-100">
					<!-- Icon -->
					<div class="icon-lg bg-danger bg-opacity-10 text-danger rounded-circle mb-2"><i class="bi bi-inboxes-fill fs-5"></i></div>
					<!-- Title -->
					<h5>Email us</h5>
					<p>Large above be to means. Him his for sympathize.</p>
					<!-- Buttons -->
					<a href="mailto:{{config('general.support_email')}}?Subject=Ticket" class="btn btn-link text-decoration-underline p-0 mb-0"><i class="bi bi-envelope me-1"></i>{{ config('general.support_email') }}</a>
				</div>
			</div>
			<!-- Contact item END -->

			<!-- Contact item START -->
			<div class="col-xl-4 position-relative">

				<div class="card card-body shadow text-center align-items-center h-100">
					<!-- Icon -->
					<div class="icon-lg bg-orange bg-opacity-10 text-orange rounded-circle mb-2"><i class="bi bi-globe2 fs-5"></i></div>
					<!-- Title -->
					<h5>Social media</h5>
					<p>Sympathize Large above be to means.</p>
					<!-- Buttons -->
					<ul class="list-inline mb-0">
						<li class="list-inline-item"> <a class="btn btn-sm bg-facebook px-2 mb-0" target="_blank" href="{{ config('general.social_media.facebook') }}"><i class="fab fa-fw fa-facebook-f"></i></a> </li>
						<li class="list-inline-item"> <a class="btn btn-sm bg-instagram px-2 mb-0" target="_blank" href="{{ config('general.social_media.instagram') }}"><i class="fab fa-fw fa-instagram"></i></a> </li>
						<li class="list-inline-item"> <a class="btn btn-sm bg-twitter px-2 mb-0" target="_blank" href="{{ config('general.social_media.twitter') }}"><i class="fab fa-fw fa-twitter"></i></a> </li>
						<li class="list-inline-item"> <a class="btn btn-sm bg-linkedin px-2 mb-0" target="_blank" href="{{ config('general.social_media.linkedin') }}"><i class="fab fa-fw fa-linkedin-in"></i></a> </li>
					</ul>
				</div>
			</div>
			<!-- Contact item END -->
		</div>
	</div>
</section>
<!-- Main banner START -->

<!-- Contact form and vector START -->
<section class="pt-0 pt-lg-5">
	<div class="container">
		<div class="row g-4 g-lg-5 align-items-center">
			<!-- Vector image START -->
			<div class="col-lg-6 text-center">
				<img src="{{ asset('frontEnd/assets/images/element/contact-us-2.svg') }}" alt="">
			</div>
			<!-- Vector image END -->

			<!-- Contact form START -->
			<div class="col-lg-6">
				<div class="card bg-light p-4">
					<!-- Card header -->
					<div class="card-header bg-light p-0 pb-3">
						<h3 class="mb-0">Send us message</h3>
					</div>

					<!-- Card body START -->
					<div class="card-body p-0">

						<form id="contactUsForm" method="POST" action="{{ route('frontEnd.contactUs.store') }}" class="row g-4">
                            @csrf
							<!-- First Name -->
							<div class="col-md-6">
								<label class="form-label">First name</label>
								<input  id="first_name" name="first_name" type="text" class="form-control">
							</div>

							<!-- Last Name -->
							<div class="col-md-6">
								<label class="form-label">Last name</label>
								<input id="last_name" name="last_name" type="text" class="form-control">
							</div>

							<!-- Email -->
							<div class="col-md-12">
								<label class="form-label">Email address</label>
								<input id="email" name="email" type="email" class="form-control">
							</div>

							<!-- Phone -->
							<div class="col-12">
								<label class="form-label">Phone</label>
								<input  id="phone" name="phone" type="tel" class="form-control">
                                <label id="custom-phone-error-message" for="phone"></label>
							</div>

							<!-- Message -->
							<div class="col-12">
								<label class="form-label">Message</label>
								<textarea id="message" name="message" class="form-control" rows="3"></textarea>
							</div>

							<!-- Button -->
							<div class="col-12">
								<button class="btn btn-dark mb-0" type="submit">Send Message</button>
							</div>	
						</form>
					</div>
					<!-- Card body END -->
				</div>
			</div>
			<!-- Contact form END -->
		</div>
	</div>
</section>
<!-- Contact form and vector END -->

<!-- Map START -->
<section class="pt-0 pt-lg-5">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<iframe class="w-100 h-300px grayscale rounded" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d13852.728770046006!2d-95.4566837!3d29.7723729!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8640d77fa2c78fc9%3A0xca939ddf63707654!2sLavish%20Ride!5e0!3m2!1sen!2sjo!4v1706434008543!5m2!1sen!2sjo" height="500" style="border:0;" aria-hidden="false" tabindex="0"></iframe>
			</div>
		</div>
	</div>
</section>
<!--Map END -->


@endsection

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.15/build/js/intlTelInput.min.js"></script>
<script src="{{ asset('frontEnd/assets/vendor/select2/dist/js/select2.min.js') }}"></script>

<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/jquery.validate.js') }}"></script>
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/additional-methods.js') }}"></script>
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/additional-methods.min.js') }}"></script>
<script src="{{ asset('FrontEnd/assets/vendor/dropzone/js/dropzone.js') }}"></script>
<script>
        $(document).ready(function() {

            //phone number input
           const personalPhoneQuery = document.querySelector("#phone");
           const personalPhoneInput =  createIntlTelInput(personalPhoneQuery)
   
           //Form Valdation
           $("#contactUsForm").validate({
                rules: {
                    first_name:{
                        required:true
                    },
                    last_name:{
                        required:true
                    },
                    email:{
                        required:true,
                        email:true
                    },
                    phone:{
                        required:true
                    },
                    message:{
                        required:true
                    }
                },
                errorPlacement: function(error, element) {
                    if (element.attr("name") == "phone") 
                    {
                        error.appendTo("#custom-phone-error-message");
                    }
                    else 
                    {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form,event) {

                    event.preventDefault();

                    //Add the country code to the personal and contact phone number
                    $("#phone").val(personalPhoneInput.getNumber());

                    return form.submit();
                }
           })
        })

</script>

@endsection


