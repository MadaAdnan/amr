@extends('frontEnd.auth.layout.index')

@section('title')
Login to Your Account
@endsection

@section('seo')
<meta name="description"
content="Login to your Lavish Ride account and enjoy luxury transportation services. Book premium rides, manage reservations, and track your chauffeurs with ease.">
<meta name="keywords"
content="airport transportation houston , houston airport car service, black car service houston, houston luxury car service, luxury car service houston, town car service in houston, car service houston airport, chauffeur service houston, houston chauffeur service">
<meta name="robots" content="index, follow">
<meta name="language" content="EN">
<link rel="canonical" href="{{ route('frontEnd.auth.login') }}" />

<meta property="og:title" content="Login to Your Account | Lavish Ride" />
<meta property="og:description"
content="Login to your Lavish Ride account and enjoy luxury transportation services. Book premium rides, manage reservations, and track your chauffeurs with ease.">
<meta property="og:image" content="{{ asset('assets_new/img/LR-LogoSchema.png') }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="LavishRide - Secure Your Safety" />
<meta property="og:url" content="{{ route('frontEnd.auth.login') }}" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@LavishRide" />
<meta name="twitter:title" content="Login to Your Account | Lavish Ride" />
<meta name="twitter:description"
content="Login to your Lavish Ride account and enjoy luxury transportation services. Book premium rides, manage reservations, and track your chauffeurs with ease.">
<meta name="twitter:image" content="{{ asset('assets_new/img/LR-LogoSchema.png') }}" />
@endsection



@section('content')
<!-- Main Content START -->
<section class="vh-xxl-100">
	<div class="container h-100 d-flex px-0 px-sm-4">
		<div class="row justify-content-center align-items-center m-auto">
			<div class="col-12">
				<div class="bg-mode shadow rounded-3 overflow-hidden">
					<div class="row g-0">
						<!-- Vector Image -->
						<div class="col-lg-6 d-flex align-items-center order-2 order-lg-1">
							<div class="p-3 p-lg-5">
								<img src="{{ asset('frontEnd/assets/images/element/signin.svg') }}" alt="">
							</div>
							<!-- Divider -->
							<div class="vr opacity-1 d-none d-lg-block"></div>
						</div>
		
						<!-- Information -->
						<div class="col-lg-6 order-1">
							<div class="p-4 p-sm-7">
								<!-- Title -->
								<h1 class="mb-2 h3">Welcome to Lavish Ride Login</h1>
								<p class="mb-0">New here?<a class="text-primary" href="{{ route('frontEnd.auth.register') }}"> Create an account</a></p>
		
 								<!-- Wrong Credentials Alert -->
								 @if(session('error'))
									<div id="wrongCred" class="alert alert-danger d-flex align-items-center rounded-3 mb-4 m-3" role="alert">
										<h4 class="mb-0 alert-heading"><i class="bi bi-exclamation-octagon-fill me-2"></i> </h4>
										<div class="ms-3">
										<h6 class="mb-0 alert-heading">Wrong Credentials</h6>
										<p class="mb-0"> {{ session('error') }}</p>
										</div>
									</div>
							 	@endif
						 
						 
                             

								<!-- Form START -->
								<form id="loginForm"  method="POST" action="{{ route('frontEnd.auth.login_submit') }}" class="mt-4 text-start">
									@csrf
									<!-- Email -->
									<div class="mb-3">
										<label class="form-label">Email</label>
										<input name="email" type="email" class="form-control">
									</div>
									<!-- Password -->
									<div class="mb-3">
										<div class="position-relative">
											<label class="form-label">Password</label>
											<input name="password" class="form-control fakepassword" type="password" id="psw-input">
											<span class="position-absolute top-50 end-0 translate-middle-y p-0 mt-3">
												<i class="fakepasswordicon fas fa-eye-slash cursor-pointer p-2"></i>
											</span>
										</div>
										<label id="custom-password-error-message" for="password"></label>
									</div>
									<!-- Remember me -->
									<div class="mb-3 d-sm-flex justify-content-between">
										<div>
											<input name="remember_me" type="checkbox" class="form-check-input" id="rememberCheck">
											<label class="form-check-label" for="rememberCheck">Remember me?</label>
										</div>
										<a class="text-primary" href="{{ route('frontEnd.auth.forget_password') }}">Forgot password?</a>
									</div>
									<!-- Button -->
									<div><button type="submit" class="btn btn-primary w-100 mb-0">Login</button></div>
		
									<!-- Divider -->
									<div class="position-relative my-4">
										<hr>
										<p class="small bg-mode position-absolute top-50 start-50 translate-middle px-2">Or sign in with</p>
									</div>
		
									<!-- Google and facebook button -->
									@include('frontEnd.auth.include.social_media')
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
<!-- Main Content END -->
@endsection

@section('script')

<!-- JQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<!-- IntTel -->
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.15/build/js/intlTelInput.min.js"></script>

<!-- Jquery valdation -->
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/jquery.validate.js') }}"></script>
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/additional-methods.js') }}"></script>
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/additional-methods.min.js') }}"></script>

<!-- Custom -->
<script>
    $(document).ready(function() {

		/**
		 * FORM VALDATION
		 * 
		 * 
		 * */
		$("#loginForm").validate({
			rules:{
				email:{
					required:true
				},
				password:{
					required:true
				}
			},
			errorPlacement: function(error, element) {
                    if (element.attr("name") === "password") 
                    {
                        error.appendTo("#custom-password-error-message");
                    }
                    else 
                    {
                        error.insertAfter(element);
                    }
            },
		});
	});
</script>
@endsection