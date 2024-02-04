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
								<img src="{{ asset('frontEnd/assets/images/element/forget-password.svg') }}" alt="">
							</div>
							<!-- Divider -->
							<div class="vr opacity-1 d-none d-lg-block"></div>
						</div>
		
						<!-- Information -->
						<div class="col-lg-6 order-1">
							<div class="p-4 p-sm-7">

								<!-- Title -->
								<h1 class="mb-2 h3">Forget password</h1>
								<span>Enter Your Email </span>

								<!-- Form START -->
								<form method="POST" action="{{ route('frontEnd.auth.forget_password_submit') }}" id="sendForgetPasswordEmail" class="mt-sm-4">
                                    @csrf
									<!-- Otp phone -->
                                    <div id="otpPhone" class="mb-3">
                                       <input name="email" type="text" id="email" class="form-control" placeholder="Enter your email"/>
                                    </div>
									<!-- Otp phone -->
									
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


<!-- Jquery valdation -->
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/jquery.validate.js') }}"></script>
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/additional-methods.js') }}"></script>
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/additional-methods.min.js') }}"></script>

<!-- Custom -->
<script>
$(document).ready(function() {

const emailInput = document.getElementById('email');

/**
 * FORM VALDATION
 * 
 * 
 * */

//check unique phone request
const checkIfEmailExistInDatabase = '{{ route("frontEnd.auth.check_if_email_exist") }}';

$("#sendForgetPasswordEmail").validate({
    rules:{
        email:{
            required:true,
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
                        return jsonResponse[0] == true ? "true" : "\"" + jsonResponse[0] + "\"";
                    }
                }
        }
    }
});

});
</script>
@endsection
