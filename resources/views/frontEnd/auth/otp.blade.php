@extends('frontEnd.auth.layout.index')

@section('title')
Verify The You Otp Code
@endsection

@section('seo')
<meta name="description"
content="Login to your Lavish Ride account and enjoy luxury transportation services. Book premium rides, manage reservations, and track your chauffeurs with ease.">
<meta name="keywords"
content="airport transportation houston , houston airport car service, black car service houston, houston luxury car service, luxury car service houston, town car service in houston, car service houston airport, chauffeur service houston, houston chauffeur service">
<meta name="robots" content="index, follow">
<meta name="language" content="EN">
<link rel="canonical" href="{{ route('frontEnd.auth.otp') }}" />

<meta property="og:title" content="Login to Your Account | Lavish Ride" />
<meta property="og:description"
content="Login to your Lavish Ride account and enjoy luxury transportation services. Book premium rides, manage reservations, and track your chauffeurs with ease.">
<meta property="og:image" content="{{ asset('assets_new/img/LR-LogoSchema.png') }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="LavishRide - Secure Your Safety" />
<meta property="og:url" content="{{ route('frontEnd.auth.otp') }}" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@LavishRide" />
<meta name="twitter:title" content="Login to Your Account | Lavish Ride" />
<meta name="twitter:description"
content="Login to your Lavish Ride account and enjoy luxury transportation services. Book premium rides, manage reservations, and track your chauffeurs with ease.">
<meta name="twitter:image" content="{{ asset('assets_new/img/LR-LogoSchema.png') }}" />
@endsection

@section('style')

<!-- INTTEL -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.15/build/css/intlTelInput.css">

@endsection



@section('content')
<!-- Main Content START -->
<section class="vh-xxl-100">
	<div class="container h-100 d-flex px-0 px-sm-4">
		<div class="row justify-content-center align-items-center m-auto">
			<div class="col-12">
				<div class="shadow bg-mode rounded-3 overflow-hidden">
					<div class="row g-0 align-items-center">
                        <!-- Band Alert -->
						<!-- Vector Image -->
						<div class="col-lg-6 d-md-flex align-items-center order-2 order-lg-1 d-none d-sm-none d-md-block d-md-none d-lg-block">
							<div class="p-3 p-lg-5">
								<img src="{{ asset('frontEnd/assets/images/element/forgot-pass.svg') }}" alt="">
							</div>
							<!-- Divider -->
							<div class="vr opacity-1 d-none d-lg-block"></div>
						</div>
		
						<!-- Information -->
						<div class="col-lg-6 order-1">
							<div class="p-4 p-sm-7">

                                <!-- Band Alert -->
                                <div style="display: none !important;" id="bandAlert" class="alert alert-danger d-flex align-items-center rounded-3 mb-4" role="alert">
                                    <h4 class="mb-0 alert-heading"><i class="bi bi-exclamation-octagon-fill me-2"></i> </h4>
                                    <div class="ms-3">
                                      <h6 class="mb-0 alert-heading">Your phone number was baned</h6>
                                      <p class="mb-0">Please try again after 24 hours</p>
                                    </div>
                                </div>

                                
                                <!-- Wrong Otp Alert -->
                                <div style="display: none !important;" id="wrongOtpAlert" class="alert alert-danger d-flex align-items-center rounded-3 mb-4" role="alert">
                                    <h4 class="mb-0 alert-heading"><i class="bi bi-exclamation-octagon-fill me-2"></i> </h4>
                                    <div class="ms-3">
                                      <h6 class="mb-0 alert-heading">Wrong Code</h6>
                                      <p class="mb-0">Please check your otp code</p>
                                    </div>
                                </div>


								<!-- Title -->
								<h1 class="mb-2 h3">Verifiy The Otp</h1>
								<p class="mb-sm-0">We have sent a code to <b id="userPhoneNumber">{{ auth()->user()->phone }}</b></p>          
								
								<!-- Form START -->
								<form id="otpForm" class="mt-sm-4">

									<!-- Otp box -->
                                    <div id="otpBox">
                                        <p class="mb-1">Enter the code we have sent you:</p>
                                        <div class="autotab d-flex justify-content-between gap-1 gap-sm-3 mb-2">
                                            <input type="text" maxlength="1" class="form-control text-center p-3 otp-input">
                                            <input type="text" maxlength="1" class="form-control text-center p-3 otp-input">
                                            <input type="text" maxlength="1" class="form-control text-center p-3 otp-input">
                                            <input type="text" maxlength="1" class="form-control text-center p-3 otp-input">
                                            <input type="text" maxlength="1" class="form-control text-center p-3 otp-input">
                                            <input type="text" maxlength="1" class="form-control text-center p-3 otp-input">
                                        </div>
                                        <!-- Button link -->
                                        <div class="d-sm-flex justify-content-between small mb-4">
                                            <span>Don't get a code?</span>
                                            <a id="resendLink" class="btn btn-sm btn-link p-0 text-decoration-underline mb-0">Click to resend</a>
                                        </div>
                                    </div>
									<!-- Otp box -->
									
									<!-- Button -->
									<div>
                                        <button onclick="verifiyCode()" type="button" class="btn btn-primary w-100 mb-0">Verify and Process</button>
                                        <a href="{{ route('frontEnd.auth.change_phone_number') }}" class="btn btn-secendary w-100 mb-0 mt-2">Change Phone Number</button>
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
<!-- Main Content END -->
@endsection

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.2.15/build/js/intlTelInput.min.js"></script>
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/jquery.validate.js') }}"></script>
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/additional-methods.js') }}"></script>
<script src="{{ asset('FrontEnd/assets/vendor/jquery-validation/dist/additional-methods.min.js') }}"></script>

<script>
    /**
     * Verifiy Code 
     * 
     * doc: send verifiy code to the backend
     * 
     * @param
     * 
     * @returns object
     */
     function verifiyCode()
    {
        var inputs = document.querySelectorAll('#otpForm input');
        var otpValue = '';

        inputs.forEach(function (input) {
            otpValue += input.value;
        });

        //verifiy request 
        const request = '{{ route("frontEnd.auth.verify_code") }}';

        //body
        const body = {
            otp:otpValue
        };
        
        fetchData(request,'POST',body)
        .then((res)=>{

           const data = res.data;

           if(!data.is_correct)
           {
             $('#wrongOtpAlert').show();
           }
           else
           {
            //go to user dashboard page
            window.location.href = data.redirect_url;
           }

        })
        .catch((err)=>{
            console.log(err)
        })
    }

    $(document).ready(function() {

            var countdown;
            var resendLink = document.getElementById('resendLink');
            var otpInput = document.getElementById('otp');
            var resendContainer = document.getElementById('resendContainer');
            
            //Function to start the countdown
            function startCountdown() {
                var seconds = 60; // Set the countdown duration in seconds

                // Disable the link during the countdown
                resendLink.classList.add('disabled');
                resendLink.removeAttribute('href');

                // Update the link text with the remaining seconds
                function updateLinkText() {
                    resendLink.textContent = 'Resend OTP (' + seconds + 's)';
                }

                updateLinkText();

                // Update the countdown every second
                countdown = setInterval(function () {
                    seconds--;

                    if (seconds <= 0) {
                        // Enable the link and reset the text when the countdown is complete
                        clearInterval(countdown);
                        resendLink.classList.remove('disabled');
                        resendLink.href = '#'; // Add your resend link URL
                        resendLink.textContent = 'Click to resend';
                    } else {
                        updateLinkText();
                    }
                }, 1000);
            }

            //Event listener for the "Resend OTP" link click
            resendLink.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent the link from navigating

                //send "resend" request
                resendOtp();

                // Start the countdown
                startCountdown();
            });

            //Resend Otp
            async function resendOtp()
            {
                //Request
                const request = '{{ route("frontEnd.auth.resendOtp") }}';
            
                //Send request
                fetchData(request,'POST')
                .then(res =>{

                    //response format
                    const response = res.data;

                    // if the user was band if its show the band message
                    if(!response.is_send)
                    {
                         // Enable the link and reset the text when the countdown is complete
                         clearInterval(countdown);
                        //resendLink.classList.remove('disabled');
                        resendLink.href = '#'; // Add your resend link URL
                        resendLink.textContent = 'Click to resend';
                        
                        $('#bandAlert').show();

                        return;
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                });

            }

          
            
        });
</script>


@endsection

