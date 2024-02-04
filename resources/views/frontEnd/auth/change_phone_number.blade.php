@extends('frontEnd.auth.layout.index')

@section('title')
Change Phone Number
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

<!-- IntTel -->
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
						<!-- Vector Image -->
						<div class="col-lg-6 d-md-flex align-items-center order-2 order-lg-1 d-none d-sm-none d-md-block d-md-none d-lg-block">
							<div class="p-3 p-lg-5">
								<img src="{{ asset('frontEnd/assets/images/element/change-phone.svg') }}" alt="">
							</div>
							<!-- Divider -->
							<div class="vr opacity-1 d-none d-lg-block"></div>
						</div>
		
						<!-- Information -->
						<div class="col-lg-6 order-1">
							<div class="p-4 p-sm-7">
								<!-- Logo -->
								<!-- Title -->
								<h1 class="mb-2 h3">Change Your Number</h1>
								<p class="mb-sm-0">Current number <b id="userPhoneNumber">{{ auth()->user()->phone }}</b></p>
								
								<!-- Form START -->
								<form method="POST" action="{{ route('frontEnd.auth.update_phone_number') }}" id="changePhoneNumber" class="mt-sm-4">
                                    @csrf
									<!-- Otp phone -->
                                    <div id="otpPhone" class="mb-3">
                                       <input name="phone" type="tel" id="phone" class="form-control" />
                                       <label id="custom-phone-error-message" for="phone"></label>
                                    </div>
									<!-- Otp phone -->
									
									<!-- Button -->
									<div>
                                        <button type="submit" class="btn btn-primary w-100 mb-0">Update</button>
                                        <a href="{{ route('frontEnd.auth.otp') }}" class="btn btn-secendary w-100 mb-0 mt-2">Back To Verifiy Phone</button>
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

        //phone number input
        const personalPhoneQuery = document.querySelector("#phone");
        const phoneInput =  createIntlTelInput(personalPhoneQuery);

        //check unique phone request
        const checkUniquePhoneNumberRequest = '{{ route("frontEnd.auth.check_unique_phone_number") }}';

        //valdation
        $("#changePhoneNumber").validate({
            rules:{
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
                    },
                },
                messages: {
                    phone_number: {
                        remote: "Phone number is not unique. Please choose a different one."
                    }
                },
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") === "phone") 
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

    });

</script>


@endsection