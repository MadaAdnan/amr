@extends('frontEnd.layouts.reservation_steps_index')
@section('pageTitle')
Checkout - Book a Limo Now!
@endsection

@section('seo')
    <meta name="title" content="Checkout - Book a Limo Now!">
    <meta name="description"
        content="Schedule your luxury transportation with Lavishride and enjoy the convenience of having a professional chauffeur at your service. Book a ride!">
    <meta name="keywords"
        content="book your ride, book car service, booking transport, book rides online, houston limo rental, book now appointment, lavish ride- secure your safety, easy online booking system, lavish shuttle experience, book airport shuttle, luxury reservations, lavish ride - secure your safety, houston town car, limo services in houston, houston limousine rental, hourly limo service houston, elite car service houston tx, hourly car rental houston">
    <meta name="robots" content="index, follow">
    <meta name="language" content="EN">
    <link rel="canonical" href="https://lavishride.com/reservations/checkout" />
@endsection

@section('content')
    @php
        $user = Auth::user();
    @endphp
    <div class="container py-1 mb-5 mt-1 content-container">
        <div class="row mt-5 m-auto sidebar-container gap-2">

            <!--start said bar -->
            <div class="col-md-3">

                <div class="card text-white side-bar side-bar p-3 mt-0">
                    <h4 class="card-header bg-dark side-bar responsive-font-size-title">Trip Summary</h4>
                    <hr class="mt-0">

                    <h5 class="card-title font-weight-bold responsive-font-size-title">Service Type</h5>

                    <div>
                        @if (isset($data) && $data->service_type == 2)
                            <p class="card-text">
                                Hourly
                            </p>
                        @elseif(isset($data) && $data->service_type == 1)
                            <p class="card-text">
                                Point to Point
                            </p>
                        @endif


                    </div>

                    @if ($data->service_type == 1)
                        <h5 class="card-title space-between-sections font-weight-bold">Transfer Type</h5>
                        <p class="card-text">
                            @if ((isset($data) && $data->parent) || $data->child)
                                Round trip
                            @else
                                One Way
                            @endif
                        </p>
                    @endif

                    <h5 class="mt-2 card-title space-between-sections font-weight-bold responsive-font-size-title">Miles
                    </h5>
                    <div class="d-flex flex-wrap">
                        <span class="address-text mb-3">
                            {{ $data->mile == 0 ? 'N/A' : number_format($data->mile, 2, '.', ',') . ' Miles' }}
                        </span>
                    </div>

                    <h5 class="card-title space-between-sections font-weight-bold responsive-font-size-title">Trip Route
                    </h5>
                    <div class="d-flex flex-wrap">
                        <span class="address-text mb-3">
                            From : {{ $data->pick_up_location }}
                        </span>
                    </div>

                    <div class="d-flex flex-wrap">
                        <span class="address-text">To: {{ $data->drop_off_location }}</span>
                    </div>


                    <h5 class="card-title space-between-sections font-weight-bold responsive-font-size-title">Pickup Date &
                        Time</h5>
                    <p class="card-text">{{ $data->pick_up_date->format('D, d/m/Y') }} -
                        {{ date('g:i A', strtotime($data->pick_up_time)) }}</p>
                    @if ($data->parent)
                        <h5 class="card-title mt-2 font-weight-bold responsive-font-size-title">Return Time</h5>
                        <p class="card-text">{{ $data->parent->pick_up_date->format('D, d/m/Y') }} -
                            {{ date('g:i A', strtotime($data->parent->pick_up_time)) }}</p>
                    @endif

                    @if ($data->service_type == 2)
                        <h5 class="card-title mt-1 font-weight-bold responsive-font-size-title">Duration</h5>
                        <p class="card-text">{{ $data->duration }} hours</p>
                    @endif

                    @if ($childSeatsFormatted->names != '' || $flightDetails != '')
                        <h5 class="card-title mt-1 font-weight-bold responsive-font-size-title">Extra</h5>
                        @if ($childSeatsFormatted->names)
                            <p class="card-text">Child Seats: {{ $childSeatsFormatted->names }}</p>
                        @endif
                        @if ($flightDetails)
                            <p class="card-text mb-4">Flight Details: {{ $flightDetails }}</p>
                        @endif
                    @endif
                </div>

                <div class="card text-white side-bar side-bar p-3 mt-2">
                    <h4 class="card-header bg-dark side-bar responsive-font-size-title">Pricing Summary</h4>
                    <hr class="mt-0">
                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="card-title font-weight-bold responsive-font-size-text">Original price:</h6>
                        <h6 class="text-white responsive-value">
                            ${{ $data->price }}
                        </h6>

                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="card-title font-weight-bold responsive-font-size-text">Tip:</h6>
                        <h6 class="text-white">
                            <span id="tipAdded" class="responsive-value">N/A</span>
                        </h6>

                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="card-title font-weight-bold responsive-font-size-text">Coupon Code:</h6>
                        <h6 class="text-white">
                            <span id="couponCodePrice" class="responsive-value">N/A</span>
                        </h6>

                    </div>

                    <hr>
                    <div class="d-flex justify-content-between">
                        <h6 class="card-title font-weight-bold responsive-font-size-text">Total price:</h6>
                        <h6 class="text-white">
                            $<span id="overAllPrice" class="responsive-value">{{ $data->price }}</span>
                        </h6>

                    </div>
                </div>


            </div>


            <div class="col-md-8">
                <form id="AddForm" class="bg-light" method="POST"
                    action="{{ route('reservations.checkout_submit', $data->id) }}">
                    @csrf
                    <div class="row g-3 shadow border border-dark-subtle mt-0 px-3 pb-5 form-table">
                        <h3 class="check-out-title">Contact Information</h3>
                        <div class="col-md-6">
                            <label for="first_name" class="form-label required-label">First Name</label>
                            <input type="text" disabled value="{{ $user->first_name }}"
                                placeholder="Enter Your First Name" class="form-control" id="first_name"
                                name="first_name" />
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label required-label">Last Name</label>
                            <input placeholder="Enter Your Last Name" type="text" class="form-control" id="last_name"
                                name="last_name" disabled value="{{ $user->last_name }}" />
                        </div>
                        <div class="col-6">
                            <label for="e_mail" class="form-label required-label">E-mail</label>
                            <input type="email" class="form-control" id="inputAddress" placeholder="Enter Your E-mail"
                                id="email" name="email" disabled value="{{ $user->email }}" />
                        </div>
                        <div class="col-6">
                            <label for="phone" class="form-label required-label">Phone</label>
                            <input required type="text" class="form-control" id="phone"
                                placeholder="Enter Your Phone Number" name="phone" disabled
                                value="{{ $user->phone }}" />
                            <span id="phone-error-mesage"></span>
                        </div>

                        <div class="input-group mt-3">
                            <span class="comment-label">Add Comment</span>

                            <textarea id="comment" name="comment" class="form-control" maxlength="223" aria-label="With textarea"
                                placeholder="Leave us your special request"></textarea>
                        </div>
                        <h3 class="check-out-title">Card Information</h3>
                        <div class="form-group">
                            <label for="card">Choose your Card:</label>
                            <select name="card" id="card" class="form-control" onchange="toggleCardFields()">
                                <option value="default">Select Card</option>
                                @forelse ($uniqueCards??[] as $card)
                                    <option value="{{ $card->id }}">
                                        {{ $card->brand }} **** **** **** {{ $card->last4 }}

                                    </option>
                                @empty
                                    No Cards Found! Please Add a new card.
                                @endforelse
                            </select>
                        </div>
                        <div id="additionalCardFields" class="row">
                            <div class="col-6 mt-3">
                                <div class="input-group">
                                    <label for="card_number" class="form-label required-label">Card Number</label>
                                </div>
                                <input type="text" class="form-control" id="card_number" name="card_number"
                                    placeholder="1234 1234 1234 1234" maxlength="19" />
                            </div>
                            <div class="col-6 mt-3">
                                <label for="holdrer_name" class="form-label required-label">Card Holder Name</label>
                                <input type="text" class="form-control" name="name" id="holdrer_name"
                                    placeholder="Enter Your Card Holder Name " />
                            </div>


                            <div class="col-md-6 mt-3">
                                <label for="card_expiry" class="form-label required-label">Card Expiry</label>
                                <input type="text" placeholder="MM/YY" class="form-control" name="exp_year"
                                    id="exp_year" />
                            </div>

                            <div class="col-md-6 mt-3">
                                <label for="card_cvc" class="form-label required-label">Card CVC</label>
                                <input type="text" class="form-control" id="cvc" placeholder="CVC"
                                    name="cvc" maxlength="4" />
                            </div>
                            <h3 class="check-out-title mt-3">Billing Address</h3>
                            <div class="col-6 mt-3">
                                <label for="address1" class="form-label required-label">
                                    Country
                                </label>
                                <select id="country" name="country" class="form-select form-select-md">
                                    @foreach ($countries as $item)
                                        <option>{{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mt-3">
                                <label for="address_req" class="form-label required-label">Address</label>
                                <input type="text" class="form-control" id="street" name="street"
                                    placeholder="Enter Address" />
                            </div>

                            <div class="col-md-6 mt-3">
                                <label for="state" class="form-label required-label">State</label>
                                <input placeholder="Enter State" required type="text" class="form-control"
                                    id="state" name="state">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="city" class="form-label required-label">City</label>
                                <input placeholder="Enter City" required type="text" name="city"
                                    class="form-control" id="city" />
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="postal_code" class="form-label required-label">Postal Code</label>
                                <input placeholder="Enter Postal Code" type="text" name="postal_code"
                                    class="form-control" id="postal_code" />
                            </div>
                        </div>
                        <div class="col-6 mt-3">
                            <label for="tip" class="form-label">Tip the Chauffeur</label>
                            <select name="tip" class="form-select" id="tip">
                                <option value="0">Choose the tip percentage</option>
                                <option value="10">10%</option>
                                <option value="15">15%</option>
                                <option value="20">20%</option>

                            </select>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label for="last_name" class="form-label">Coupon</label>
                            <div class="input-group">
                                <input maxlength="8" name="coupon_code_input" id="coupon_code" type="text"
                                    class="form-control" placeholder="Add Coupon Code">
                                <div class="input-group-append" id="couponButtonDiv">
                                    <button onclick="applyCoupon()" id="checkCouponCode"
                                        class="ml-2 btn btn-primary check-coupon-code-button" type="button">
                                        <span id="couponActionSpan"><i class="bi bi-search"></i></span>
                                    </button>
                                </div>
                            </div>
                            <p id="coupon-error-message"></p>
                        </div>

                        <div class="col-12 d-flex justify-content-center mt-5 text-center">
                            <button class="btn btn-primary col-sm-12 col-md-12 px-4 w-75" type="submit" id="submit">
                                Finish
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    @endsection @section('scripts')
    <script>
        const checkCouponCodeButtonId = 'checkCouponCode';
        const showTip = document.getElementById("show_tip");
        const priceDiv = document.getElementById('original_price');
        const tipSelectElement = document.getElementById("tip");
        const showTotalPrice = document.getElementById('total_price');
        const couponCodeInput = document.getElementById('coupon_code');
        const couponDiscount = document.getElementById('coupon_discount')
        const couponActionSpan = document.getElementById('couponActionSpan');
        const checkCouponCodeButtonElement = document.getElementById(checkCouponCodeButtonId)
        const coupon = 0;
        const overAllPriceId = 'overAllPrice';
        const couponCodePriceId = 'couponCodePrice';
        const tipAddedId = 'tipAdded';
        const couponButtonDiv = 'couponButtonDiv';
        const tipId = 'tip';
        let original_price = parseFloat('{{ $data->price }}');
        const fixed_original_price = parseFloat('{{ $data->price }}');
        let price = parseFloat('{{ $data->price }}');
        let discountedPriceOverAll = 0;
        let selectedCoupon = null;
        let count = 0;

        $(document).ready(function() {
            toggleCardFields()
        });


        const input = document.querySelector("#phone");
        window.intlTelInput(input, {
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
        });

        const reservation_id = "{{ $data->id }}";
        $("#AddForm").validate({
            rules: {
                first_name: {
                    required: true,
                },
                last_name: {
                    required: true,
                },
                email: {
                    required: true,
                },

                card_number: {
                    required: true,
                },
                last_name: {
                    required: true,
                },
                name: {
                    required: true,
                },
                postal_code: {
                    required: true,
                },
                state: {
                    required: true,
                },
                exp_year: {
                    required: true,
                    expirationDate: true,
                    dateInFuture: true
                },
                cvc: {
                    required: true,
                    maxlength: 4,
                    minlength: 3,
                    digits: true,
                },
                country: {
                    required: true,
                },
                street: {
                    required: true,
                },
                city: {
                    required: true,
                }


            },
            messages: {
                cvc: {
                    maxlength: "CVC should be 3 digits or more.",
                    minlength: "CVC should be 3 digits or more.",
                    digits: "CVC should contain only digits.",

                },
                exp_year: {
                    expirationDate: "Invalid expiration date format Ex: 08/20.",
                    dateInFuture: "Please enter a future year"
                }



            },
            errorPlacement: function(error, element) {
                var inputId = element.attr("id");

                if (inputId == "start_date") {
                    error.addClass("error-message");
                    error.appendTo($("#returnTimeInput-error"));
                }
                if (inputId == "phone") {
                    error.addClass("error-message");
                    error.appendTo($("#phone-error-mesage"));
                } else {
                    error.addClass("error-message");
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).removeClass("valid").addClass("error");
            },
            unhighlight: function(element) {
                $(element).removeClass("error").addClass("valid");
            },
            submitHandler: function(form) {
                var selectedCardId = $('#card').val();
                var isCardSelected = selectedCardId !== 'default';

                Swal.fire({
                    title: 'Submiting data.',
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                        const b = Swal.getHtmlContainer().querySelector('b')
                        timerInterval = setInterval(() => {
                            b.textContent = Swal.getTimerLeft()
                        }, 100)
                    },
                    willClose: () => {
                        clearInterval(timerInterval)
                    }
                }).then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === Swal.DismissReason.timer) {
                        
                    }
                })


                // var formData = new FormData(document.getElementById("AddForm"));
                var formData = new FormData(form);

                // Include or exclude card-related fields based on whether a card is selected
               

                // Assuming you have already initialized the plugin on your input field.
                const iti = window.intlTelInputGlobals.getInstance(
                    input);
                const number = iti.getNumber(intlTelInputUtils.numberFormat.NATIONAL);



                // // Here you can access the selected country's information.

                const selectedCountryData = iti.getSelectedCountryData();
                var isoCode = selectedCountryData.iso2;

                var countryCode = selectedCountryData.dialCode;

                let inputValue = document.getElementById("exp_year").value;

                console.log('is it disabled : ',couponCodeInput.disabled == false)

                // Make the coupon input enabled so we get it's value
                if(couponCodeInput.disabled == true)
                {
                    formData.append("coupon_code", couponCodeInput.value);
                    couponCodeInput.disabled = false;
                }

                if (isCardSelected) {
                    // Include card-related fields
                    formData.append("card_id", selectedCardId);
                  
                } else {
                    // Exclude card-related fields

                    formData.append("country_code", countryCode);
                    formData.append("phone", number);
                    formData.append("country", isoCode);
                    formData.append("price", price);

                }
             


                // // You can also serialize the form data to send it via AJAX or perform other actions
                var serializedFormData = $(form).serialize();

                // // Now, you can submit the form data or perform any other necessary actions
                let url = "{{ route('reservations.checkout_submit', ':id') }}";
                url = url.replace(":id", reservation_id);
                $.ajax({
                    url,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status == 201) 
                        {
                            // Redirect to the URL provided in the response
                            window.location.href = response.data.url;
                        } 
                        else 
                        {
                            // Handle the case where the response does not contain a valid URL
                            
                        }
                    },
                    error: function(xhr, status, error) 
                    {
                        console.log(xhr)
                        console.log(error)
                        const errorMessage = xhr.responseJSON ? xhr.responseJSON.err : xhr.err
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage ?? 'Server Error!',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 4000,
                        });
                    },
                });

                return false;
            }
        });

        // Custom validation method for card expiration date
        $.validator.addMethod("expirationDate", function(value, element) {
            // Implement your custom expiration date validation logic here
            // Example validation: Check if the value is in the format MM/YY (e.g., 01/23)
            return /^\d{2}\/\d{2}$/.test(value);
        }, "Invalid expiration date format.");

        $.validator.addMethod("dateInFuture", function(value, element) {
            // Get the current year in YY format
            var currentYear = new Date().getFullYear() % 100;

            // Parse the entered year from the input
            var enteredYear = parseInt(value.slice(3, 5)); // Extract the last 2 characters

            // Parse the entered month from the input
            var enteredMonth = parseInt(value.slice(0, 2));

            // Calculate the full entered year
            var fullEnteredYear = currentYear < 50 ? 2000 + enteredYear : 1900 + enteredYear;

            // Create a JavaScript Date object with the entered year and month
            var enteredDate = new Date(fullEnteredYear, enteredMonth - 1, 1); // Month is 0-indexed

            // Get the current date
            var currentDate = new Date();

            // Check if the entered date is in the future
            return enteredDate > currentDate;

        }, "Year must be in the future");


        /** Event listener's to an inputs **/
        document.getElementById('card_number').addEventListener('input', function() {
            formatCreditCardInput();
        });

        document.getElementById(tipId).addEventListener('change', function(event) {

            /** get the tip value and make sure it's number */
            let tipValue = parseInt(tipSelectElement.value);

            /** return the user to it's original */
            price = original_price;

            /** Get the added tip **/
            addPercentageToPrice(price, tipValue);

        });

        function formatCreditCardInput() {
            const input = document.getElementById('card_number');
            const inputValue = input.value.replace(/\s/g, '');
            let formattedValue = '';

            for (let i = 0; i < inputValue.length; i++) {
                formattedValue += inputValue[i];
                if ((i + 1) % 4 === 0 && i < inputValue.length - 1) {
                    formattedValue += ' ';
                }
            }

            input.value = formattedValue;
        }

        function isValidCreditCard(number) {
            // Use Luhn's algorithm for credit card number validation
            let sum = 0;
            let doubleUp = false;

            for (let i = number.length - 1; i >= 0; i--) {
                let digit = parseInt(number.charAt(i), 10);
                if (doubleUp) {
                    digit *= 2;
                    if (digit > 9) {
                        digit -= 9;
                    }
                }
                sum += digit;
                doubleUp = !doubleUp;
            }

            return sum % 10 === 0;
        }

        document.addEventListener('DOMContentLoaded', function() {
            var expYearInput = document.getElementById('exp_year');
            expYearInput.addEventListener('input', formatExpirationYear);
        });

        function formatExpirationYear() {
            var expYearInput = document.getElementById('exp_year');
            var formattedValue = expYearInput.value;

            // Remove any non-digit characters
            formattedValue = formattedValue.replace(/\D/g, '');

            // Format the year as MM/YY
            if (formattedValue.length >= 2) {
                formattedValue = formattedValue.slice(0, 2) + '/' + formattedValue.slice(2, 4);
            }

            expYearInput.value = formattedValue;
        }

        document.addEventListener('DOMContentLoaded', function() {
            var cvcInput = document.getElementById('cvc');
            cvcInput.addEventListener('input', formatCVC);
            cvcInput.addEventListener('blur', validateCVC);
        });

        function formatCVC() {
            var cvcInput = document.getElementById('cvc');
            var formattedValue = cvcInput.value;

            // Remove any non-digit characters
            formattedValue = formattedValue.replace(/\D/g, '');

            cvcInput.value = formattedValue;
        }

        function validateCVC() {
            var cvcInput = document.getElementById('cvc');
            var cvcValue = cvcInput.value;

            if (cvcValue.length !== 3 && cvcValue.length !== 4) {

                cvcInput.value = '';
            }
        }

        function addPercentageToPrice(price, percentage, addDome = true) {

            /** Take the original_price */
            let updatedPrice = price;

            /** amount will be added */
            let amountToAdd = 0;

            if (typeof price !== 'number' || typeof percentage !== 'number') {
                // Ensure that both inputs are valid numbers
                return "Invalid input. Please provide valid numbers.";
            }


             if (price != 0) {

               if (selectedCoupon) {
                     const discountedPrice = checkCouponCode(price, selectedCoupon.discount, selectedCoupon.discount_type);
                    // updatedPrice = discountedPrice.overAllPrice;
                    updatedPrice = price;


                 }
                
                 amountToAdd = (updatedPrice * percentage) / 100;

                 updatedPrice = parseFloat(updatedPrice) + parseFloat(amountToAdd);

                 updatedPrice = updatedPrice < 0 ? 0 : updatedPrice;

             }
            if (addDome) {
                $('#' + tipAddedId).html(amountToAdd == 0 ? 'N/A' : '$' + parseFloat(amountToAdd).toFixed(2));
                $('#' + overAllPriceId).html(updatedPrice == 0 ? 'N/A' : parseFloat(updatedPrice).toFixed(2));
            }

            price = updatedPrice

            return updatedPrice;
        }

        function checkCouponCode(priceArg, couponValueArg, type) {
            let discountedPrice = 0;
            let discount = 0

            if (type == 'Percentage') {
                if (typeof priceArg !== 'number' || typeof couponValueArg !== 'number' || couponValueArg < 0 ||
                    couponValueArg > 100) {
                    return "Invalid input. Please provide valid numbers for price and coupon percentage between 0 and 100.";
                }
                // Calculate the discount amount
                discount = (priceArg * couponValueArg) / 100;
                // Calculate the discounted price
                discountedPrice = price - discount;
                
            } else {
                discountedPrice = price - couponValueArg;
                discount = couponValueArg
                
                discountedPrice = discountedPrice > 0 ? discountedPrice : 0;
            }
           
            const response = {
                'overAllPrice': parseFloat(discountedPrice).toFixed(2),
                'discountedPrice': parseFloat(discount).toFixed(2),
            }
            return response;

        }

        function applyCoupon() {
           
           
            $('#coupon-error-message').empty();

            let request = '{{ route('reservations.get_coupon_code', ':code') }}'
            request = request.replace(':code', couponCodeInput.value)
            $.ajax({
                url: request,
                type: 'GET',
                success: (res) => {
                    let couponValue = res.data.discount;
                    selectedCoupon = res.data;
                    // get the coupon info
                    let getCouponValue = checkCouponCode(price, res.data.discount, res.data.discount_type);
                    /** Show the price on the front end **/
                    $('#' + overAllPriceId).html(getCouponValue.overAllPrice);
                    $('#' + couponCodePriceId).html('$' + getCouponValue.discountedPrice);
                   

                    let tipValue = parseInt(tipSelectElement.value);

                    addPercentageToPrice(parseFloat(getCouponValue.overAllPrice), tipValue);
                    
                    /** change the coupon code button icon and the onclick function and make the coupon input disabled */
                    createCouponButton('Remove')
                    couponCodeInput.disabled = true


                },
                error: (err) => {
                    const errorResponseMsg = err.responseJSON.err;
                    $('#coupon-error-message').append(errorResponseMsg + '.');

                    checkCouponCodeButtonElement.disabled = false;
                }
            });

        }
        


        function removeCoupon() {

            const discount = selectedCoupon;
            let addedPriceToOverAll = 0;

            /** Remove it from the total price by adding the price other than decresse it*/
            // if(selectedCoupon.discount_type == 'Price')
            // {
            //     addedPriceToOverAll = selectedCoupon.discount
            // }
            // else
            // {
            //     /** Get the updated price with the tip **/
            //     let tipPercentage = parseInt(tipSelectElement.value);
            //     let getPriceWithTip = addPercentageToPrice(original_price,tipPercentage);
            //     addedPriceToOverAll = (selectedCoupon.discount / 100) * getPriceWithTip;
            // }
            let tipPercentage = parseInt(tipSelectElement.value);
            selectedCoupon = null
            addPercentageToPrice(original_price, tipPercentage)

            /** Update the overall price in the front-end*/
            // $('#'+overAllPriceId).html(price.toFixed(2));
            // $('#'+couponCodePriceId).html('N/A');

            /** Make the input enabled and change the button to search icon **/
            createCouponButton('apply')
            couponCodeInput.disabled = false;

        }

        function createCouponButton(function_type) {
            if(function_type !== 'Remove'){ 
                $('#couponCodePrice').html( 'N/A' );

            }
            $("#" + checkCouponCodeButtonId).remove();
            const actionFunction = () => {
                function_type == 'Remove' ? removeCoupon() : applyCoupon()
            };
            const buttonActionIcon = function_type == "Remove" ? "trash" : "search";
            const buttonElement = $(
                '<button id="checkCouponCode" class="ml-2 btn btn-primary check-coupon-code-button" type="button"><span id="couponActionSpan"><i class="bi bi-' +
                buttonActionIcon + '"></i></span></button>');
            buttonElement.on("click", actionFunction);

            $('#' + couponButtonDiv).append(buttonElement);
        }

        function toggleCardFields() {
            var cardSelect = document.getElementById('card');
            var additionalCardFields = document.getElementById('additionalCardFields');

            // Get the selected option
            var selectedOption = $('#card').val();
            var additionalCardFields = $('#additionalCardFields');

            // Toggle visibility of additional card fields based on the selected option
            additionalCardFields.toggle(selectedOption == 'default');
        }
    </script>
@endsection