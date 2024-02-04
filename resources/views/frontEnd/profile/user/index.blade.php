@extends('frontEnd.layouts.index')

@section('title')
    User Profile
@endsection

@section('seo')
<meta name="title" content="User Dashboard | LavishRide">
<meta name="description" content="Join our affiliate program and earn commission by referring customers to Lavish Ride, the leading provider of private chauffeur services in Houston.">
<meta name="keywords" content="service partners, business partners, looking for a business partner, business partners international, need business partner, affiliate program, referral program, commission, private chauffeur services, affiliate categories, affiliate marketing, affiliate links, affiliate partner program, affiliate partner, affiliate partnership">
<link rel="canonical" href="{{ route('frontEnd.user.profile.home') }}">
<meta property="og:title" content="Affiliates - Create form | LavishRide" />
<meta property="og:description" content="Join our affiliate program and earn commission by referring customers to Lavish Ride, the leading provider of private chauffeur services in Houston.">
<meta property="og:image" content="{{ asset('assets_new/Lavish-Ride-Affiliate.jpg') }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="LavishRide - Secure Your Safety" />
<meta property="og:url" content="{{ route('frontEnd.user.profile.home') }}" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@LavishRide" />
<meta name="twitter:title" content="Affiliates - Create form | LavishRide" />
<meta name="twitter:description" content="Join our affiliate program and earn commission by referring customers to Lavish Ride, the leading provider of private chauffeur services in Houston.">
<meta name="twitter:image" content="{{ asset('assets_new/Lavish-Ride-Affiliate.jpg') }}" />
@endsection

@php
    $user = auth()->user();
@endphp

@section('style')

    <!-- IntlTel -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.15/build/css/intlTelInput.css">

@endsection

@section('content')
<!-- Content START -->
<section class="pt-3">
	<div class="container">
		<div class="row">

			<!-- Sidebar START -->
            <x-profile.sidebar activeNavItem="my-profile" />
			<!-- Sidebar END -->

			<!-- Main content START -->
			<div class="col-lg-8 col-xl-9">

				<!-- Offcanvas menu button -->
				<div class="d-grid mb-0 d-lg-none w-100">
					<button class="btn btn-primary mb-4" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
						<i class="fas fa-sliders-h"></i> Menu
					</button>
				</div>

				<div class="vstack gap-4">

					<!-- Verified message -->
                    @if (!$user->email_verified_at||!$user->phone_verified_at)
                        <x-profile.complete-profile />                    
                    @endif

					<!-- Personal info START -->
					<div class="card border">
						<!-- Card header -->
						<div class="card-header border-bottom">
							<h4 class="card-header-title">Personal Information</h4>
						</div>

						<!-- Card body START -->
						<div class="card-body">
							<!-- Form START -->
							<form id="personalInformationForm" method="POST" enctype='multipart/form-data' action="{{ route('frontEnd.user.profile.update_personal_information') }}" class="row g-3">
                                @csrf
								<!-- Profile photo -->
								<div class="col-12">
									<label class="form-label">Upload your profile photo<span class="text-danger">*</span></label>
									<div class="d-flex align-items-center">
										<label class="position-relative me-4" for="uploadfile-1" title="Replace this pic">
											<!-- Avatar place holder -->
											<span class="avatar avatar-xl">
												<img id="uploadfile-1-preview" class="avatar-img rounded-circle border border-white border-3 shadow" src="{{ $user->image }}" onerror="this.onerror=null;this.src='{{ asset('FrontEnd/assets/images/element/avatar-placeholder.jpg') }}';" alt="">
											</span>
										</label>
										<!-- Upload button -->
										<label class="btn btn-sm btn-primary-soft mb-0" for="uploadfile-1">Change</label>
										<input onchange="showSelectedImageInput(event,'uploadfile-1-preview')" name="image" id="uploadfile-1" class="form-control d-none" type="file">
									</div>
								</div>

								<!-- First Name -->
								<div class="col-md-6">
									<label class="form-label">First Name<span class="text-danger">*</span></label>
									<input id="first_name" name="first_name" type="text" class="form-control" value="{{ $user->first_name }}" placeholder="Enter your first name">
								</div>

								<!-- Last Name -->
								<div class="col-md-6">
									<label class="form-label">Last Name<span class="text-danger">*</span></label>
									<input id="last_name" name="last_name" type="text" class="form-control" value="{{ $user->last_name }}" placeholder="Enter your last name">
								</div>

							
								<!-- Button -->
								<div class="col-12 text-end">
									<button type="submit" class="btn btn-primary mb-0">Save Changes</button>
								</div>
							</form>
							<!-- Form END -->
						</div>
						<!-- Card body END -->
					</div>
					<!-- Personal info END -->

					<!-- Update email START -->
                    <div id="updateEmailSectionDivider"></div>
					<div class="card border">
						<!-- Card header -->
						<div class="card-header border-bottom">
							<h4 class="card-header-title">Update Email Address</h4>
							<p class="mb-0">Your current emal <span class="text-primary">{{ auth()->user()->email }}</span></p>
						</div>

						<!-- Card body START -->
                        <div id="updateVerifiPhoneDivider"></div>
						<div class="card-body">
							<form id="updateEmailForm" action="{{ route('frontEnd.user.profile.update_email_address') }}" method="POST">
                                @csrf
								<!-- Email -->
								<label class="form-label">Enter your new email <span class="text-danger">*</span></label>
								<input id="email" name="email" type="email" class="form-control" placeholder="Enter your email id">

								<div class="text-end mt-3">
									<button  {{ auth()->user()->email_verified_at ? 'disabled':'' }} id="sendVerificationLink" type="submit" class="btn btn-primary mb-0">Send Verification Link</a>
								</div>
							</form>	
						</div>
						<!-- Card body END -->
					</div>
					<!-- Update email END -->

					
				</div>
			</div>
			<!-- Main content END -->

		</div>
	</div>
</section>
<!-- Content END -->

@endsection

@section('script')

<!-- Jquery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<!-- IntTel -->
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.15/build/js/intlTelInput.min.js"></script>

<!-- Jquery Valdation -->
<script src="{{ asset('frontEnd/assets/vendor/jquery-validation/dist/jquery.validate.js') }}"></script>
<script src="{{ asset('frontEnd/assets/vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('frontEnd/assets/vendor/jquery-validation/dist/additional-methods.js') }}"></script>
<script src="{{ asset('frontEnd/assets/vendor/jquery-validation/dist/additional-methods.min.js') }}"></script>

<!-- Custom -->
<script>
    /* ===================
    Table Of Content
    ======================
    01 Personal Information Form Valdation
    02 Update Email Address Form Valdation

    ====================== */

    $(document).ready(function() {

        /**
         * 01 - Personal Information Form Valdation
         * 
         * doc: personal information form valdation
         * 
         * 
         */

        $("#personalInformationForm").validate({
            rules: {
                first_name:{
                    required:true
                },
                last_name:{
                    required:true
                }
            },
            submitHandler: function(form,event) 
            {
                return form.submit();
            }
        });

        /**
         * 02 - Update Email Address Form Valdation
         * 
         * doc: update email address  valdation form
         * 
         * 
         */
         const checkIfEmailExistInDatabase = '{{ route("frontEnd.auth.check_if_email_exist","true") }}';
         const  emailInput = document.getElementById('email');

         $("#updateEmailForm").validate({
            rules: {
                email:{
                   // required:true,
                    email:true,
                    remote: {
                    url: checkIfEmailExistInDatabase,
                    type: 'post',
                    data: {
                         _token: $('meta[name="csrf-token"]').attr('content'),
                         emailInput: function() {
                                return emailInput.value;
                            }
                    },
                    dataType: 'json',
                    dataFilter: function(response) {
                        var jsonResponse = JSON.parse(response);
                        if(jsonResponse[0] == true) $("#sendVerificationLink").prop("disabled", !$("#updateEmailForm").valid());
                        return jsonResponse[0] == true ? "true" : "\"" + jsonResponse[0] + "\"";
                    }
                }
                }
            },
            submitHandler: function(form,event) 
            {
                return form.submit();
            }
        });
        $("#updateEmailForm :input").on("change keyup", function() {
            $("#sendVerificationLink").prop("disabled", !$("#updateEmailForm").valid());

            //if the email input is empty make the send button disabled
            if(emailInput.value == '')
            {
                $("#sendVerificationLink").prop("disabled", true);
            }
        });





    })
</script>
@endsection