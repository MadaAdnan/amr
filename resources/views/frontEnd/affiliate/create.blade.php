@extends('frontEnd.layouts.index')

@section('title')
Affiliates - Create form - Let's Grow together
@endsection

@section('seo')
<meta name="title" content="Affiliates - Create form - Let's Grow together | LavishRide">
<meta name="description" content="Join our affiliate program and earn commission by referring customers to Lavish Ride, the leading provider of private chauffeur services in Houston.">
<meta name="keywords" content="service partners, business partners, looking for a business partner, business partners international, need business partner, affiliate program, referral program, commission, private chauffeur services, affiliate categories, affiliate marketing, affiliate links, affiliate partner program, affiliate partner, affiliate partnership">
<link rel="canonical" href="{{ route('frontEnd.affiliate.create') }}">
<meta property="og:title" content="Affiliates - Create form | LavishRide" />
<meta property="og:description" content="Join our affiliate program and earn commission by referring customers to Lavish Ride, the leading provider of private chauffeur services in Houston.">
<meta property="og:image" content="{{ asset('assets_new/Lavish-Ride-Affiliate.jpg') }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="LavishRide - Secure Your Safety" />
<meta property="og:url" content="{{ route('frontEnd.affiliate.create') }}" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@LavishRide" />
<meta name="twitter:title" content="Affiliates - Create form | LavishRide" />
<meta name="twitter:description" content="Join our affiliate program and earn commission by referring customers to Lavish Ride, the leading provider of private chauffeur services in Houston.">
<meta name="twitter:image" content="{{ asset('assets_new/Lavish-Ride-Affiliate.jpg') }}" />
@endsection

@section('style')

    <!-- Select 2 -->
    <link href="{{ asset('FrontEnd/assets/vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('FrontEnd/assets/vendor/select2/dist/css/theme/bootstrap-5/select2-bootstrap-5-theme.min.css')}}" />
    
    <!-- IntlTel -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.15/build/css/intlTelInput.css">

    <!-- Drop Zone -->
    <link rel="stylesheet" type="text/css" href="{{ asset('FrontEnd/assets/vendor/dropzone/css/dropzone.css') }}">

@endsection

@section('content')

<!--Page Banner START -->
<x-general.application-title title="Affiliate Application" />
<!--Page Banner END -->

<!--Main content START -->
<section>
	<div class="container">
		<div class="row">
			<div class="col-lg-10 mx-auto">
				<form method="POST" action="{{ route('frontEnd.affiliate.store') }}" id="contactForm" class="vstack gap-4">
                    @csrf
					<!-- Owner Detail START -->
					<div class="card shadow">
						<!-- Card header -->
						<div class="card-header border-bottom">
							<h5 class="mb-0">Owner Detail</h5>
						</div>

						<!-- Card body -->
						<div class="card-body">
							<div class="row g-3">
								<!-- Business Name -->
								<div class="col-12">
									<label class="form-label">Business Name</label>
									<div class="input-group">
										<input name="name" type="text" class="form-control" placeholder="Business Name">
									</div>
								</div>

                                 <!-- Phone -->
                                 <div class="col-md-6">
									<label class="form-label">Phone Number</label>
                                    <input id="phone" name="phone" class="form-control intTelInput" type="tel" placeholder="Enter phone number">
                                    <label id="custom-phone-error-message" for="phone"></label>
								</div>


								<!-- Email -->
								<div class="col-md-6">
									<label class="form-label">Email address</label>
									<input id="email" name="email" class="form-control" type="email" placeholder="Enter email address">
								</div>

								
							</div>
						</div>
					</div>
					<!-- Owner Detail END -->

					<!-- Business Detail START -->
					<div class="card shadow">
						<!-- Card header -->
						<div class="card-header border-bottom">
							<h5 class="mb-0">Business Detail</h5>
						</div>

						<!-- Card body -->
						<div class="card-body">
							<div class="row g-3">

								<!-- State -->
								<div class="col-md-6">
									<label class="form-label">State</label>
									<select class="form-control select2" name="state" id="state">
                                        @foreach ($states as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
								</div>

								<!-- City -->
								<div class="col-md-6">
									<label class="form-label">City</label>
                                    <input id="city" name="city" class="form-control" type="text" placeholder="Enter city name">
								</div>

                                <!-- Address -->
								<div class="col-md-6">
									<label class="form-label">Address</label>
                                    <input name="address" class="form-control" type="text" placeholder="Enter address">
								</div>

                                <!-- Zip Code -->
								<div class="col-md-6">
									<label class="form-label">Zip Code</label>
                                    <input id="zip_code" name="zip_code" class="form-control" type="text" placeholder="Enter zip code">
								</div>

                                <!-- Tax ID -->
								<div class="col-md-6">
									<label class="form-label">Tax ID</label>
                                    <input id="tax_id" name="tax_id" class="form-control" type="text" placeholder="Enter the tax id">
								</div>

                                <!-- Website -->
								<div class="col-md-6">
									<label class="form-label">Website</label>
                                    <input id="website" name="website" class="form-control" type="text" placeholder="Enter the Website">
								</div>

                                 <!-- Contact person -->
								<div class="col-md-6">
									<label class="form-label">Contact person</label>
                                    <input id="contact_person" name="contact_person" class="form-control" type="text" placeholder="Contact person">
								</div>

                                <!-- Contact phone -->
								<div class="col-md-6">
									<label class="form-label">Contact phone</label>
                                    <input id="contact_phone" name="contact_phone" class="form-control intTelInput" type="tel" placeholder="Enter the contact phone">
                                    <label id="custom-contact-phone-error-message" for="phone"></label>
								</div>

                                <!-- Contact email -->
								<div class="col-md-6">
									<label class="form-label">Contact email</label>
                                    <input id="contact_email" name="contact_email" class="form-control intTelInput" type="text" placeholder="Enter the contact email">
								</div>

                                <!-- Fleet Size -->
								<div class="col-md-6">
									<label class="form-label">Fleet Size</label>
                                    <input id="fleet_size" name="fleet_size" class="form-control intTelInput" type="text" placeholder="Enter the fleet size">
								</div>

								<!-- Area of service -->
								<div class="col-md-6">
									<label class="form-label">Area of service</label>
									<select class="form-control select2" placeholder="Please select the area of service" multiple name="area_of_service[]" id="area_of_service">
                                        @foreach ($states as $key => $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    <label id="custom-area-of-service-error-message" for="phone"></label>
								</div>


                                <!-- Fleet Size -->
								<div class="col-md-6">
									<label class="form-label">Airports you service</label>
                                    <input id="airports" name="airports" class="form-control" type="text" placeholder="Enter the airports you serve in">
								</div>


								
							</div>
						</div>
					</div>
					<!-- Business Detail END -->


					<!-- Button -->
					<div class="text-end">
						<button type="submit" class="btn btn-primary mb-0">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>
<!--Main content END -->

    
@endsection

@section('script')

<!-- Jquery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<!-- IntTel -->
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.15/build/js/intlTelInput.min.js"></script>

<!-- Select 2 -->
<script src="{{ asset('frontEnd/assets/vendor/select2/dist/js/select2.min.js') }}"></script>

<!-- Jquery Valdation -->
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/jquery.validate.js') }}"></script>
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/additional-methods.js') }}"></script>
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/additional-methods.min.js') }}"></script>
<script src="{{ asset('FrontEnd/assets/vendor/dropzone/js/dropzone.js') }}"></script>

<!-- Custom -->
<script>
    $(document).ready(function() {

        //init select2
        $('.select2').select2({
            theme: "bootstrap-5",
        });

        //phone number input
        const personalPhoneQuery = document.querySelector("#phone");
        const contactPhoneQuery = document.querySelector("#contact_phone");

        const personalPhoneInput =  createIntlTelInput(personalPhoneQuery)
        const contactPhoneInput = createIntlTelInput(contactPhoneQuery)

        //Form valdation
        $("#contactForm").validate({
            rules: {
                name:{
                    required:true
                },
                phone:{
                    required:true
                },
                email:{
                    required:true
                },
                address:{
                    required:true
                },
                state:{
                    required:true
                },
                city:{
                    required:true
                },
                "zip_code":{
                    required:true
                },
                "tax_id":{
                    required:true
                },
                website:{
                    required:true
                },
                "contact_person":{
                    required:true
                },
                "contact_phone":{
                    required:true
                },
                "contact_email":{
                    required:true
                },
                "area_of_service":{
                    required:true
                },
                "fleet_size":{
                    required:true,
                    number:true
                },
                airports:{
                    required:true
                },

            },
            errorPlacement: function(error, element) {
                if (element.attr("name") == "phone") 
                {
                    error.appendTo("#custom-phone-error-message");
                } 
                else if (element.attr("name") == "contact_phone") 
                {
                    error.appendTo("#custom-contact-phone-error-message");
                } 
                else if (element.attr("name") == "area-of-service") 
                {
                    error.appendTo("#custom-area-of-service-error-message");
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
                $("#contact_phone").val(contactPhoneInput.getNumber());

                return form.submit();
            }
        });

    });

</script>
@endsection



