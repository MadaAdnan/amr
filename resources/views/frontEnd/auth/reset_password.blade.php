@extends('frontEnd.auth.layout.index')

@section('title')
    Forget Password
@endsection

@section('seo')
<meta name="description"
content="Create a new Lavish Ride account and gain access to exclusive luxury transportation services.">
<meta name="keywords"
content="airport transportation houston , houston airport car service, black car service houston, houston luxury car service, luxury car service houston, town car service in houston, car service houston airport, chauffeur service houston, houston chauffeur service">
<meta name="robots" content="index, follow">
<meta name="language" content="EN">
<link rel="canonical" href="{{ route('frontEnd.auth.forget_password') }}" />
@endsection

@php
// Get User Info
$email = request()->get('email');
$token = request('token');
@endphp


@section('content')
<section class="vh-xxl-100">
	<div class="container h-100 d-flex px-0 px-sm-4">
		<div class="row justify-content-center align-items-center m-auto">
			<div class="col-12">
				<div class="shadow bg-mode rounded-3 overflow-hidden">
					<div class="row g-0 align-items-center">
						<!-- Vector Image -->
						<div class="col-lg-6 d-md-flex align-items-center order-2 order-lg-1 d-none d-sm-none d-md-block d-md-none d-lg-block">
							<div class="p-3 p-lg-5">
								<img src="{{ asset('frontEnd/assets/images/element/reset-password.svg') }}" alt="">
							</div>
							<!-- Divider -->
							<div class="vr opacity-1 d-none d-lg-block"></div>
						</div>
						
						<!-- Information -->
						<div class="col-lg-6 order-1">
							<div class="p-4 p-sm-7">

								<!-- Title -->
								<h1 class="mb-2 h3">Reset password</h1>
								<span>Update your password </span>

								<!-- Form START -->
								<form method="POST" action="{{ route('frontEnd.auth.reset_password') }}" id="resetPasswordForm" class="mt-sm-4">
                                    @csrf
									
									<!-- Password -->
									<div class="mb-2">
                                        <div class="position-relative">
                                            <label class="form-label">Enter new password</label>
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
                                            <label class="form-label">Confirm new password</label>
                                            <input name="confirm_password" id="confirm_password" class="form-control fakepassword-confirm" type="password">
                                            <span class="position-absolute top-50 end-0 translate-middle-y p-0 mt-3">
                                                <i class="fakepassword-icon-confirm fas fa-eye-slash cursor-pointer p-2"></i>
                                            </span>
                                        </div>
                                        <label id="custom-confirm-password-error-message" for="confirm_password"></label>
									</div>

									
									<!-- Button -->
									<div>
                                        <button type="submit" class="btn btn-primary w-100 mb-0">Reset</button>
                                        <a href="{{ route('frontEnd.auth.login') }}" class="btn btn-secendary w-100 mb-0 mt-2">Back To Login</button>
                                    </div>
		
									<!-- Copyright -->
                                    <div class="text-primary-hover mt-3 text-center"> Copyrights ©2023 Booking. Build by <a href="https://www.webestica.com/" class="text-body">Webestica</a>. </div>

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

			const token = '{{ $token }}';
			const email = '{{ $email }}';

			//add storng passowrd valdation
			strongPasswordValidation()

			//Valdation
			$("#resetPasswordForm").validate({
				rules:{
					password:{
						required:true,
						strongPassword:true
					},
					confirm_password:{
						required:true,
						equalTo: "#password"
					}
				},
				errorPlacement: function(error, element) {

                   if (element.attr("name") === "password") 
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
                  
                    //Create a new input element for token
                    const tokenInput = $("<input>")
                        .attr("type", "text")
                        .attr("style", "display:none;")
                        .attr("name", "token")
                        .val(token);

                    const emailInput = $("<input>")
                        .attr("type", "text")
                        .attr("style", "display:none;")
                        .attr("name", "email")
                        .val(email);

						
                    
                    //Append the country_code to the form
                    form.appendChild(tokenInput[0]);
                    form.appendChild(emailInput[0]);
                    
                    //  return false;
                     return form.submit();
            }
			})

		});

	</script>

@endsection