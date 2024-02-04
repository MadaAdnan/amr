@extends('frontEnd.auth.layout.index')

@section('title')
Register New Account | Lavish Ride
@endsection

@section('seo')
<meta name="description"
content="Create a new Lavish Ride account and gain access to exclusive luxury transportation services.">
<meta name="keywords"
content="airport transportation houston , houston airport car service, black car service houston, houston luxury car service, luxury car service houston, town car service in houston, car service houston airport, chauffeur service houston, houston chauffeur service">
<meta name="robots" content="index, follow">
<meta name="language" content="EN">
<link rel="canonical" href="{{ route('frontEnd.auth.register') }}" />
@endsection

@section('style')

<!-- SELECT 2 -->
<link href="{{ asset('FrontEnd/assets/vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('FrontEnd/assets/vendor/select2/dist/css/theme/bootstrap-5/select2-bootstrap-5-theme.min.css')}}" />

<!-- INTEL -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.15/build/css/intlTelInput.css">

<!-- DROPZONE -->
<link rel="stylesheet" type="text/css" href="{{ asset('FrontEnd/assets/vendor/dropzone/css/dropzone.css') }}">

@endsection

@section('content')
<section class="vh-xxl-100">
	<div class="container h-100 d-flex px-0 px-sm-4">
		<div class="row justify-content-center align-items-center m-auto">
			<div class="col-12">
				<div class="bg-mode shadow rounded-3 overflow-hidden">
					<div class="row g-0">
						<!-- Vector Image -->
						<div class="col-lg-6 d-md-flex align-items-center order-2 order-lg-1 d-none d-sm-none d-md-block d-md-none d-lg-block">
							<div class="p-3 p-lg-5">
								<img src="{{ asset('frontEnd/assets/images/element/signin.svg') }}" alt="">
							</div>
							<!-- Divider -->
							<div class="vr opacity-1 d-none d-lg-block"></div>
						</div>
		
						<!-- Information -->
						<div class="col-lg-6 order-1">
							<div class="p-4 p-sm-6">
								<!-- Title -->
								<h1 class="mb-2 h3">Create Your Lavish Ride Account</h1>
								<p class="mb-0">Already a member?<a class="text-primary" href="{{ route('frontEnd.auth.login') }}"> Log in</a></p>
		
								<!-- Form START -->
								<form method="POST" action="{{ route('frontEnd.auth.register_submit') }}" id="registerUserForm" class="mt-4 text-start">
                                    @csrf
                                     <!-- Full name -->
                                    <div class="row">
                                        <div class="col-6">
                                            <!-- First Name -->
                                            <div class="mb-3">
                                                <label class="form-label">First Name</label>
                                                <input id="first_name" name="first_name" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <!-- Last Name -->
                                            <div class="mb-3">
                                                <label class="form-label">Last Name</label>
                                                <input id="last_name" name="last_name" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                     <!-- Email -->
                                     <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input id="email" name="email" class="form-control">
                                    </div>

                                      <!-- Phone -->
                                    <div class="mb-3">
                                        <label class="form-label">Phone</label>
                                        <input id="phone" type="tel" name="phone" class="form-control">
                                        <span class="mt-3 form-text">Note:Include a phone number for confirming the reservation with you</span>
                                        <label id="custom-phone-error-message" for="phone"></label>
                                    </div>

                                    
									<!-- Password -->
									<div class="mb-2">
                                        <div class="position-relative">
                                            <label class="form-label">Enter password</label>
                                            <input name="password" id="password" class="form-control fakepassword" type="password">
                                            <span class="position-absolute top-50 end-0 translate-middle-y p-0 mt-3">
                                                <i class="fakepasswordicon fas fa-eye-slash cursor-pointer p-2"></i>
                                            </span>
                                        </div>
                                        <label id="custom-password-error-message" for="password"></label>
									</div>

									<!-- Confirm Password -->
									<div class="mb-3">
                                        <div class="position-relative">
                                            <label class="form-label">Confirm Password</label>
                                            <input name="confirm_password" id="confirm_password" class="form-control fakepassword-confirm" type="password">
                                            <span class="position-absolute top-50 end-0 translate-middle-y p-0 mt-3">
                                                <i class="fakepassword-icon-confirm fas fa-eye-slash cursor-pointer p-2"></i>
                                            </span>
                                        </div>
                                        <label id="custom-confirm-password-error-message" for="confirm_password"></label>
									</div>

									
									<!-- Button -->
									<div><button type="submit" class="btn btn-primary w-100 mb-0">Register</button></div>
		
									<!-- Divider -->
									<div class="position-relative my-4">
										<hr>
										<p class="small position-absolute top-50 start-50 translate-middle bg-mode px-1 px-sm-2">Or sign in with</p>
									</div>
		
									<!-- Social Logi's button -->
                                    @include('frontEnd.auth.include.social_media')
		
									<!-- Copyright -->
									<div class="text-primary-hover text-body mt-3 text-center"> By clicking the register button, you agree to our <a class="text-primary" target="_blank" href="{{ route('frontEnd.policy.terms_condition') }}">Terms & Conditions,</a> and <a class="text-primary" target="_blank" href="{{ route('frontEnd.policy.privacy_policy') }}">Privacy Policy</a>. </div>
									
                                    <!-- Copyright -->
                                    @include('frontEnd.auth.include.footer')

								</form>
								<!-- Form END -->
							</div>		
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

@section('script')

<!-- JQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<!-- IntTel js -->
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.15/build/js/intlTelInput.min.js"></script>

<!-- Jquery valdation -->
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/jquery.validate.js') }}"></script>
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/additional-methods.js') }}"></script>
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/additional-methods.min.js') }}"></script>

<!-- Custom -->
<script>
    $(document).ready(function() {

        //phone number input
        const personalPhoneQuery = document.querySelector("#phone");
        const phoneInput =  createIntlTelInput(personalPhoneQuery);

        //add storng passowrd valdation
        strongPasswordValidation()

        //check unique phone request
        const checkUniquePhoneNumberRequest = '{{ route("frontEnd.auth.check_unique_phone_number") }}';


        //Form valdation
        $("#registerUserForm").validate({
            rules:{
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
                    required:true,
                    phoneValidation:true,
                    remote: {
                        url: checkUniquePhoneNumberRequest,
                        type: 'post',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            phone_number: function() {
                                return phoneInput.getNumber();
                            }
                        },
                        dataType: 'json',
                        dataFilter: function(response) {
                            var jsonResponse = JSON.parse(response);
                            return jsonResponse[0] == true ? "true" : "\"" + jsonResponse[0] + "\"";
                        }
                    }
                },
                password:{
                    required:true,
                    strongPassword:true
                },
                confirm_password:{
                    required:true,
                    equalTo: "#password"
                }
            },
            messages: {
                phone: {
                    required: "Phone number is required.",
                    remote: "Phone number is not unique. Please choose a different one."
                },
                confirm_password:{
                    equalTo:"The passowrd dose not match"
                },
                email:{
                    email:"Please enter a valid email, e.g. example@email.com"
                }
            },
            errorPlacement: function(error, element) {
                    if (element.attr("name") === "phone") 
                    {
                        error.appendTo("#custom-phone-error-message");
                    }
                    else if (element.attr("name") === "password") 
                    {
                        error.appendTo("#custom-password-error-message");
                    }
                    else if (element.attr("name") === "confirm_password") 
                    {
                        error.appendTo("#custom-confirm-password-error-message");
                    }
                    else 
                    {
                        error.insertAfter(element);
                    }
            },
            submitHandler: function(form,event) {

                    event.preventDefault();
                    

                    //Get country code
                    const countryCode = phoneInput.getSelectedCountryData().dialCode;

                    //Create a new input element
                    const countryCodeInput = $("<input>")
                        .attr("type", "text")
                        .attr("style", "display:none;")
                        .attr("name", "country_code")
                        .val(countryCode);
                    
                    //Append the country_code to the form
                    form.appendChild(countryCodeInput[0]);
                    

                    return form.submit();
            }
        })

    })
</script>
@endsection