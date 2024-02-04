@extends('frontEnd.layouts.index')

@section('title')
Chauffeur Partners - Create | LavishRide
@endsection

@section('seo')
<meta name="title" content="Chauffeur Partners - Create | LavishRide">
<meta name="description" content="Own a chauffeur company? Apply now to join our network of partners, granting you exclusive access to our discerning clientele.">
<meta name="keywords" content="Become a chauffeur partner, Chauffeur, Grow your business with Lavishride, driver login">
<link rel="canonical"    href="{{ route('frontEnd.chauffeur.create') }}/">

<meta property="og:title" content="Chauffeur Partners - Create | LavishRide" />
<meta property="og:description" content="Own a chauffeur company? Apply now to join our network of partners, granting you exclusive access to our discerning clientele.">
<meta property="og:image" content="{{ asset("assets_new/Lavish-Ride-Chauffeur.jpg") }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="LavishRide - Secure Your Safety" />
<meta property="og:url" content="{{ route('frontEnd.chauffeur.create') }}/" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@LavishRide" />
<meta name="twitter:title" content="Chauffeur Partners - Create | LavishRide" />
<meta name="twitter:description" content="Own a chauffeur company? Apply now to join our network of partners, granting you exclusive access to our discerning clientele.">
<meta name="twitter:image" content="{{ asset("assets_new/Lavish-Ride-Chauffeur.jpg") }}" />   
@endsection


@section('style')

    <!-- Select2 CSS -->
    <link href="{{ asset('FrontEnd/assets/vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('FrontEnd/assets/vendor/select2/dist/css/theme/bootstrap-5/select2-bootstrap-5-theme.min.css')}}" />

    <!-- intlTelInput CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.15/build/css/intlTelInput.css">

    <!-- Drop Zone CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('FrontEnd/assets/vendor/dropzone/css/dropzone.css') }}">

    <!-- Bootstrap datepicker CSS -->
    <link rel="stylesheet" href="{{ asset('FrontEnd/assets/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}"/>

@endsection


@section('content')
<x-general.application-title title="Chauffeur Application" />

<!--Main content START -->
<section>
	<div class="container">
		<div class="row">
			<div class="col-lg-10 mx-auto">
				<form enctype='multipart/form-data' method="POST" action="{{ route('frontEnd.chauffeur.store') }}" id="chauffeurForm" class="vstack gap-4">
                    @csrf
					<!-- Owner Detail START -->
					<div class="card shadow">
						<!-- Card header -->
						<div class="card-header border-bottom">
							<h5 class="mb-0">Detail</h5>
						</div>

						<!-- Card body -->
						<div class="card-body">
							<div class="row g-3">

								<!-- Full Name -->
								<div class="col-6">
									<label class="form-label">Full Name</label>
									<div class="input-group">
										<input id="name" name="name" type="text" class="form-control" placeholder="Full Name">
									</div>
								</div>

								<!-- Phone -->
								<div class="col-md-6">
									<label class="form-label">Phone</label>
									<input id="phone" name="phone" class="form-control" type="text" placeholder="Enter Phone">
                                    <label id="custom-phone-error-message" for="phone"></label>
								</div>

								<!-- Email -->
								<div class="col-md-6">
									<label class="form-label">Email address</label>
									<input id="email" name="email" class="form-control" type="email" placeholder="Enter email address">
								</div>

								<!-- State -->
								<div class="col-6">
									<label class="form-label">State</label>
                                    <select id="state" name="state" class="form-select select2" data-search-enabled="true">
                                        @foreach ($states as $item)
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
									<input id="address" name="address" class="form-control" type="text" placeholder="Enter address">
								</div>

                                <!-- Date of birth -->
								<div class="col-md-6">
									<label class="form-label">Date of birth</label>
									<input id="date_of_birth" name="date_of_birth" class="form-control" type="text" placeholder="Enter date of birth">
								</div>

                                 <!-- Experience Years -->
								<div class="col-md-6">
									<label class="form-label">Experience Years</label>
									<input id="experience_years" name="experience_years" class="form-control" type="text" placeholder="Enter experience years">
								</div>

                                <!-- Short notice -->
								<div class="col-md-6">
									<label class="form-label">Can you be available on short notice ?</label>
									<div class="d-sm-flex">
										<!-- Radio -->
										<div class="form-check radio-bg-light me-4">
											<input class="form-check-input" type="radio" name="availability" id="availability" value="1">
											<label class="form-check-label" for="availability">
												Yes
											</label>
										</div>
										<!-- Radio -->
										<div class="form-check radio-bg-light me-4">
											<input class="form-check-input" type="radio" name="availability" id="availability2" value="0">
											<label class="form-check-label" for="availability">
												No
											</label>
										</div>
									</div>
                                    <label id="custom-short-notice-error-message"></label>

								</div>

                                <!-- Valid License -->
								<div class="col-md-6">
									<label class="form-label">Do you have Valid State Texas Driver's License ?</label>
									<div class="d-sm-flex">
										<!-- Radio -->
										<div class="form-check radio-bg-light me-4">
											<input class="form-check-input" type="radio" name="texas_license" id="texas_license" value="1">
											<label class="form-check-label" for="texas_license">
												Yes
											</label>
										</div>
										<!-- Radio -->
										<div class="form-check radio-bg-light me-4">
											<input class="form-check-input" type="radio" name="texas_license" id="texas_license2" value="0">
											<label class="form-check-label" for="texas_license">
												No
											</label>
										</div>
									</div>
                                    <label id="custom-valid-license-error-message"></label>
								</div>

                                <!-- Limo License -->
								<div class="col-md-6">
									<label class="form-label">Do you have Valid City of Houston Limo License ?</label>
									<div class="d-sm-flex">
										<!-- Radio -->
										<div class="form-check radio-bg-light me-4">
											<input class="form-check-input" type="radio" name="houston_limo_license" id="houston_limo_license" value="1">
											<label class="form-check-label" for="houston_limo_license">
												Yes
											</label>
										</div>
										<!-- Radio -->
										<div class="form-check radio-bg-light me-4">
											<input class="form-check-input" type="radio" name="houston_limo_license" id="houston_limo_license2" value="0">
											<label class="form-check-label" for="houston_limo_license">
												No
											</label>
										</div>
									</div>
                                    <label id="custom-houston-limo-license-error-message"></label>
								</div>

                                <!-- Upload your resume -->
								<div class="col-md-6">
									<label class="form-label">Upload your resume</label>
									<input id="resume" name="resume" class="form-control" type="file" />
								</div>
                                 
                                <!-- Additional Information -->
                                <div class="col-md-12">
									<label class="form-label">Additional Information</label>
									<textarea id="additional_information" name="additional_information" class="form-control"></textarea>
								</div>
                            								
							</div>
						</div>
					</div>
					<!-- Owner Detail END -->

					<!-- Button -->
					<div class="text-end">
						<button type="submit" class="btn btn-primary mb-0">Submit</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>
<!--Main content END -->

@endsection

@section('script')

    <!-- Jquery JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.15/build/js/intlTelInput.min.js"></script>

    <!-- Select 2 JS -->
    <script src="{{ asset('frontEnd/assets/vendor/select2/dist/js/select2.min.js') }}"></script>

    <!-- Validation JS -->
    <script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/jquery.validate.js') }}"></script>
    <script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/additional-methods.js') }}"></script>
    <script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/additional-methods.min.js') }}"></script>

    <!-- Bootstrap datepicker JS-->
    <script src="{{ asset('FrontEnd/assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>


    <script>
        $(document).ready(function() {

            //init select2
            $('.select2').select2({
                theme: "bootstrap-5",
            });

            //datepicker
            $('#date_of_birth').datepicker({ dateFormat: 'yy-mm-dd' });

            //phone number input
            const phoneQuery = document.querySelector("#phone");
            const phoneInput =  createIntlTelInput(phoneQuery)

            //Form valdation
            $("#chauffeurForm").validate({
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
                    state:{
                        required:true
                    },
                    city:{
                        required:true
                    },
                    address:{
                        required:true
                    },
                    date_of_birth:{
                        required:true,
                        date:true
                    },
                    experience_years:{
                        required:true
                    },
                    availability:{
                        required:true
                    },
                    texas_license:{
                        required:true
                    },
                    houston_limo_license:{
                        required:true
                    },
                    resume:{
                        required:true
                    }
                },
                errorPlacement: function(error, element) {
                    if (element.attr("name") == "phone") 
                    {
                        error.appendTo("#custom-phone-error-message");
                    }
                    else if(element.attr("name") == "availability")
                    {
                        error.appendTo("#custom-short-notice-error-message");
                    }
                    else if(element.attr("name") == "texas_license")
                    {
                        error.appendTo("#custom-valid-license-error-message");
                    }
                    else if(element.attr("name") == "houston_limo_license")
                    {
                        error.appendTo("#custom-houston-limo-license-error-message");
                    }
                    else 
                    {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form,event) {

                    event.preventDefault();

                    //Add the country code to the personal and contact phone number
                    $("#phone").val(phoneInput.getNumber());

                    return form.submit();
                }
            });
        })
    </script>
@endsection


