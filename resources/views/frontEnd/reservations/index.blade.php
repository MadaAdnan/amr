@extends('frontEnd.layouts.index')
@section('pageTitle') 
    Book Your Private Car Service in Houston Online Today
@endsection

@section('seo')
    <meta name="title" content="Book Your Private Car Service in Houston Online Today">
    <meta name="description"
        content="Experience Lavishride epitome luxury private car service. From corporate transportation to airport transfers, we offer impeccable service and style.">
    <meta name="keywords"
        content="online booking system, book online, book now, reserve a ride, book limo, book a ride, instant booking, booking system, car service book, transportation booking software, book the ride,book a limo, rental limo service, book airport transfer, book transportation, luxury car rental houston tx, limos in houston, limousine rental houston tx, limousine houston tx, limousine service houston tx">
    <meta name="robots" content="index, follow">
    <meta name="language" content="EN">
    <link rel="canonical" href="https://lavishride.com/reservations" />


    
@endsection

@section('content')


<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-8 col-md-10 col-sm-12">
            
            <div id="service-group-div" class="services-group">
                    <div class="btn-group d-flex col-sm-6 col-md-3 servicesButtons" role="group"
                        aria-label="Solid button group border border-black">
                        <button type="button" onclick="ServiceTypeValue(2)" id="hourly-btn"
                            class="w-100 rounded-0 fw-bold filter-buttons active-btn service-buttons">
                            Hourly
                        </button>
                        <button type="button" id="point-to-point-btn" onclick="ServiceTypeValue(1)"
                            class="rounded-0 fw-bold filter-buttons w-100 service-buttons">
                            Point to Point
                        </button>
                    </div>
                </div>
                <div id="content" class="mt-5 mt-md-2 content-form">
                   
                    <form class="needs-validation pb-2 pb-lg-2 col-sm-12 col-lg-12 rounded shadow-sm rounded-0" novalidate=""
                        id="AddForm" method="POST" action="{{ route('frontEnd.reservations.store') }}">
                        @csrf
                        <div id="hourly" class="row pb-2 p-3 px-sm-5 pt-0 pt-sm-0 filter-cards active">

                            <!-- start radio point-to-point -->
                            <div class="d-none hidden-point-to-point mt-4 mb-2">
                                <div class="form-check form-check-inline">
                                    <input id="round-trip" class="form-check-input" type="radio" name="transfer_way"
                                        value="Round" />
                                    <label class="form-check-label" for="inlineRadio2">Round Trip</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input id="one-way-trip" class="form-check-input" type="radio" name="transfer_way"
                                        id="inlineRadio1" value="One Way" />
                                    <label class="form-check-label" for="inlineRadio1">One Way Trip</label>
                                </div>
                            </div>
                            <!-- end radio point-to-point -->
                            
                            <div class="hourly-view mt-4 mb-2">
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input checkbox-rounded custom-checkbox" id="return-back" />
                                    <label for="return-back">Return back to the pick-up location</label>
                                </div>
                            </div>
        
                                <!-- Pick-up Location -->
                                <div class="row" id="Pick-Up">
        
                                    <div id="pickup-location" class="col-sm-6">
                                        <label for="city" class="form-label fs-base d-flex align-items-center mb-0">
                                            <i class="bi bi-geo-alt mx-2 me-1"></i>
            
                                            Pick-Up Location<span class="fs-4 text-danger">*</span>
                                            <div id="pickUpLocationLoader" class="ml-1 spinner-border spinner-border-sm d-none" role="status">
                                                <span class="sr-only">Loading...</span>
                                              </div>                                              
                                              

                                        </label>
                                        <input id="pick_up_location" type="text" class="form-control form-control-md"
                                             placeholder="Select Location" name="pick_up_location" />
                                            <div style="display: none;" class="dropdown-result" id="search-results"></div>
                                            <div>
                                               <span id="error-message-content-pick-up-location"></span>
                                            </div>
                                    </div>
                                    <!-- Pick-up Location -->
                                    <div class="col-sm-6" id="Drop-up">
                                        <label for="drop_off_location" class="form-label fs-base d-flex align-items-center mb-0">
                                            <i class="bi bi-geo-alt mx-2 me-1"></i>
            
                                            Drop-Off Location<span class="fs-4 text-danger">*</span>
                                            <div id="dropOffLocationLoader" class="ml-1 spinner-border spinner-border-sm d-none" role="status">
                                                <span class="sr-only">Loading...</span>
                                              </div>     
                                        </label>
                                        <input id="drop_off_location" type="text" class="form-control form-control-md"
                                            placeholder="Select Location" name="drop_off_location" />
                                            <div style="display: none;" id="search-results-dropoff" class="dropdown-result"></div>
                                        <div class="invalid-feedback">Please choose your city!</div>
                                        <div>
                                            <span id="error-message-content-drop-off-location"></span>
                                        </div>
                                    </div>
                                 
        
                                    <!-- Date && Time -->
                                    <div class="col-md-6 col-sm-12 mt-3">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-6" id="ChangeDateWidth">
                                                <label for="address2" class="form-label fs-base d-flex align-items-center mb-0">
                                                    <i class="bi bi-calendar mx-2 me-1" id="clock-icon"></i>
                        
                                                    Date<span class="fs-4 text-danger">*</span>
                                                </label>
                                                
                                                    <input onfocus="this.showPicker()" id="start_date" type="date"
                                                        class="form-control" placeholder="dd/mm/yyyy"
                                                        name="start_date" />
                                                <div id="returnTimeInput-error"></div>
                                                
                                            </div>
                                            <div class="col-sm-6 col-md-6" id="ChangeTimeWidth">
                                                <label for="address2" class="form-label fs-base d-flex align-items-center mb-0">
                                                    <i class="bi bi-clock mx-2 me-1" id="clock-icon"></i>
                        
                                                    Time<span class="fs-4 text-danger">*</span>
                                                </label>
                                                <input id="start_time" type="time" class="form-control"
                                                    name="start_time" />
                                            </div>
                                        </div>
                                    </div>

                                       <!-- Duration -->
                                    <div class="col-sm-6 hourly-view mt-3">
                                        <label for="state" class="form-label fs-base d-flex align-items-center mb-0 ">
                                            <i class="bi bi-stopwatch mx-2 me-1"></i>
                                            Duration<span class="fs-4 text-danger">*</span>
                                        </label>
                                        <select id="duration" name="duration" class="form-select form-select-md ">
                                            <option value="" disabled="">choose...</option>
                                            @for ($i = 2; $i <= $hoursDurations; $i++)
                                                <option value="{{ $i }}">{{ $i }} Hour</option>                                
                                            @endfor
                                        </select>
                                    </div>
                                    
        
                                    <!-- start return date -->
                                    <div class="col-sm-6 col-md-3 d-none hidden-point-to-point mt-3" id="hiddenWidth">
                                     


                                        <label for="return_time" class="form-label fs-base d-flex align-items-center mb-0">
                                            <i class="bi bi-calendar mx-2 me-1" id="clock-icon"></i>
                
                                            Return Date<span class="fs-4 text-danger">*</span>
                                        </label>
                                        <input 
                                            id="return_date"
                                            type="date"
                                            class="form-control"
                                            placeholder="dd/mm/yyyy"
                                            name="return_date" 
                                         />
                                         <div id="reutnrDateInput-error"></div>
                
                                    </div>
                                    <!-- end return date -->
                                  
                                    <!-- start return time -->
                                    <div class="col-sm-6 col-md-3 d-none hidden-point-to-point mt-3" id="hidden">
                                        <label for="return_time" class="form-label fs-base d-flex align-items-center mb-0">
                                            <i class="bi bi-clock mx-2 me-1" id="clock-icon"></i>
                
                                            Return Time<span class="fs-4 text-danger">*</span>
                                        </label>
                                        <input id="return_time" type="time" class="form-control form-control-md"
                                            name="return_time" />
                                    </div>
                                    <!-- end return time -->
        
                                </div>
                                <div class="optional">
                                    <hr />
                                </div>
                                <div class="row">
                                    <!-- Airline Name -->
                                    <div class="col-sm-12 col-md-3 mb-4">
                                        <label for="airline_name" class="form-label fs-base d-flex align-items-center">
                                            <i class="bi bi-airplane mx-2 me-2"></i>
                                            Airline Name
                                        </label>
                                        <select id="airline_name" name="airline_name" class="form-select form-select-md">
                                            <option value="">choose...</option>
                                            @foreach ($airlines as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
        
                                    <div class="col-sm-12 col-md-3 mb-4">
                                        <label for="flight_number" class="form-label fs-base d-flex align-items-center">
                                            <i class="bi bi-123 mx-2 me-2"></i>
                                            Flight Number<small class="text-muted"></small>
                                        </label>
                                        <input 
                                            id="flight_number"
                                            name="flight_number" 
                                            class="form-control form-control-md"
                                            maxlength="4" 
                                            type="number" 
                                            placeholder="1234" 
                                         />
                                    </div>
                
                                    <!-- Child Seats -->
                                    <div class="col-md-6 col-sm-12 mb-4" id="child">
                                        <div class="mb-1">
                                            <label for="flight_number" class="form-label fs-base d-flex align-items-center">
                                                <i class="bi bi-check2-circle mx-2 me-2"></i>
                                                Child Seats<small class="text-muted"></small>
                                            </label>
                                        </div>
                                        <div class="row">
                                            @foreach ($childSeats as $index => $item)
                                                <div class="form-floating col-4 col-sm-4">
                                                    <input 
                                                        child-seat-id="{{ $item->id }}"
                                                        type="number"
                                                        class="form-control child-seats-input"
                                                        min="0"
                                                        max="3"
                                                        value="0"
                                                        step="1"
                                                        placeholder="Rear-facing"
                                                    />
                                                    <label for="floatingPassword" class="label-ellipsis">{{ $item->title }}</label>
                                                </div>                                
                                            @endforeach
                                        </div>
                                        <div id="error-message-content-child-seats">
                                            
                                        </div>
                                    </div>
                                </div>
                                

                            <div class="d-flex justify-content-end me-5">
                                <button type="submit" class="btn btn-danger py-3 px-3 bg-primary action-button">
                                    Select Your Car
                                </button>
                            </div>
        
                        </div>
                    </form>
                </div>
        </div>
        
    </div>

    {{-- <div class="row justify-content-center">
    </div> --}}
</div>

    <!--  -->
@endsection
@section('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&libraries=places"></script>

    <script>

        /** BASIC VARIBLES **/
        const pickUpLocationInput = document.getElementById('pick_up_location');
        const dropOffLocationInput = document.getElementById('drop_off_location');
        const pickupDateInput = document.getElementById('start_date');
        const pickupTimeInput = document.getElementById('start_time');
        const returnBackCheckBox = document.getElementById('return-back');
        const returnDateInput = document.getElementById('return_date');
        const returnTimeInput = document.getElementById('return_time');
        const pickUpLocationLoader = document.getElementById('pickUpLocationLoader');
        const dropOffLocationLoader = document.getElementById('dropOffLocationLoader');
        const today = new Date().toISOString().split('T')[0];
        
        /** make the pickup input not taking date less than today */
        pickupDateInput.setAttribute('min', today);


        var input = document.getElementById('pick_up_location');
        let isPickAsDropOff = false;
        let pickUpLocationgoogleId = '';
        let dropoffLocationgoogleId = '';
        let distance = null;
        let map = null
        let isAirport = false;
        let serviceTypeInput = 2;
        let isReturnAsPickUpActive = false;
        let Round = true
        let UpdateServiceTypeValue = 2;
        const apiKey = '{{ env("GOOGLE_API_KEY") }}';

        const customCheckbox = document.getElementById('return-back');
        let radioButtonStatus = false;

        customCheckbox.addEventListener('click', (e) => {

            toggleReturnTimeInputs()
            
            if (!radioButtonStatus) {
                customCheckbox.checked = true;
                radioButtonStatus = true;
            } else {
                customCheckbox.checked = false;
                radioButtonStatus = false;
            }

        });


        // Get the "btn point-to-point" button
        const pointToPointBtn = document.getElementById("point-to-point-btn");

        // Get elements with the "hidden-point-to-point" class
        const hiddenElements = document.querySelectorAll(".hidden-point-to-point");

        // Get elements with the "hourly-view" class
        const hourlyViewElements = document.querySelectorAll(".hourly-view");

          // Select all filter buttons and filterable cards
        const filterButtons = document.querySelectorAll(".filter-buttons ");
        const filterableCards = document.querySelectorAll(".filter-cards");

        // Get the "btn hourly" button
        var hourlyBtn = document.getElementById("hourly-btn");

        var timeout = null;
        let oldInputValue = '';
        let oldInputValueDropoff = '';

        const autocompleteOptions = {
            types: ['geocode'], // Restrict results to addresses only
            componentRestrictions: {
                country: 'US'
            }, 
        };

        let apiTimer = 1000;
        let setTimeOutFunction = null;

        $.validator.addMethod("dateComparison", function(value, element, params) {
           
            var startDate = $("#start_date").val();
            var returnDate = $("#return_date").val();
            var startTime = $("#start_time").val();
            var returnTime = $("#return_time").val();

            // Check if both dates and times are filled
            if (startDate && returnDate && startTime && returnTime) {
                var startDateTime = new Date(startDate + " " + startTime);
                var returnDateTime = new Date(returnDate + " " + returnTime);

                // Ensure startDateTime is less than returnDateTime
                if (startDateTime >= returnDateTime) {
                    return false;
                }

                // Ensure a minimum of 2 hours difference
                var timeDifference = returnDateTime - startDateTime;
                var hoursDifference = timeDifference / (1000 * 60 * 60);

                return hoursDifference >= 2;
            }
            return true; // No comparison if one or more date/time values are empty


        });
        
        $("#AddForm").validate({
            rules: {
                start_date: {
                    required: true,
                    dateComparison:"greaterthan"
                },
                start_time: {
                    required: true
                },
                drop_off_location: {
                    required: {
                        depends: function() {
                            return isReturnAsPickUpActive == false || serviceTypeInput == 1;
                        }
                    }
                },
                duration: {
                    required: true
                },
                pick_up_location: {
                    required: true
                },
                return_date: {
                    dateComparison: "greaterthan",
                    required: {
                        depends: function() {
                            return Round;
                        }
                    }
                },
                return_time: {
                    required: {
                        depends: function() {
                            return Round;
                        }
                    }
                }
            },
            messages: {
                start_date: {
                    required: "Please enter a start date.",
                    date: "Please enter a valid date format.",
                    dateComparison: "The date is incorrect."
                },
                return_date: {
                    required: "Please enter a return date.",
                    date: "Please enter a valid date format.",
                    dateComparison: "The date is incorrect."
                }
            },
            errorPlacement: function(error, element) {
                var inputId = element.attr('id');
                var childSeatsClass = element.hasClass('child-seats-input')
                
                if (inputId == 'start_date') {
                    error.addClass('error-message');
                    error.appendTo($('#returnTimeInput-error'));

                }else if(inputId == 'pick_up_location')
                {

                    error.addClass('error-message');
                    error.appendTo($('#error-message-content-pick-up-location'));
                }
                else if(inputId == 'drop_off_location')
                {

                    error.addClass('error-message');
                    error.appendTo($('#error-message-content-drop-off-location'));
                }else if(inputId == 'return_date')
                {
                    error.addClass('error-message');
                    error.appendTo($('#reutnrDateInput-error'));
                }
                else if(childSeatsClass)
                {
                    error.addClass('error-message');
                    $('#error-message-content-child-seats').empty();
                    error.appendTo($('#error-message-content-child-seats'));
                }

                else {
                    error.addClass('error-message');
                    error.insertAfter(element);
                }



            },
            highlight: function(element) {
                $(element).removeClass('valid').addClass('error');
            },
            unhighlight: function(element) {
                $(element).removeClass('error').addClass('valid');
            },
            submitHandler:function(form) {


                new Promise( async (resolve, reject) => 
                {

                   

                    if(pickUpLocationgoogleId == '')
                    {
                        try
                        {
                            await getPlaces('pickUpLocation',pickUpLocationInput.value,true,reject)
                        }
                        catch(error)
                        {
                            alert("please add a pick location")
                        }
                    }

                    if(radioButtonStatus)
                    {
                        dropoffLocationgoogleId = pickUpLocationgoogleId;
                    }

                    if(dropoffLocationgoogleId == '')
                    {
                        
                        await getPlaces('dropOffLocation',dropOffLocationInput.value,true,reject);
                    }

                    await checkTripInfo(form);
                                        
                })

               
               
                
                // if(pickUpLocationgoogleId == '')
                // {
                //     // Swal.fire({
                //     //     title: 'Error!',
                //     //     text: 'please select pick-up location',
                //     //     icon: 'error',
                //     //     confirmButtonText: 'Close'
                //     // })
                //    // getPlaces('pickUpLocation',pickUpLocationInput.value,true)
                //    // return false;
                // }
                // if(dropoffLocationgoogleId == '')
                // {
                //     // Swal.fire({
                //     //     title: 'Error!',
                //     //     text: 'please select dropoff location',
                //     //     icon: 'error',
                //     //     confirmButtonText: 'Close'
                //     // })
                //    // getPlaces('dropOffLocation',dropOffLocationInput.value,true)
                //     //return false;
                // }

                //return await checkTripInfo(form);
            

                return false;


            }
        });

        function ServiceTypeValue(value) 
        {
            UpdateServiceTypeValue = value
        }

        window.addEventListener("DOMContentLoaded", () => {
            const roundTripRadio = document.getElementById("round-trip");
            roundTripRadio.checked = true;
            var inputs = document.getElementsByClassName('child-seats-input');
            
            for (var i = 0; i < inputs.length; i++) {
                
                inputs[i].addEventListener('input', function () {
                    var currentValue = this.value;
                    var total = 0;

                    // Calculate the total of all input values
                    for (var j = 0; j < inputs.length; j++) {
                    total += parseFloat(inputs[j].value) || 0;
                    }

                    // If the total exceeds 3, distribute the excess to other inputs
                    if (total > 3) {
                    var excess = total - 3;

                    for (var k = 0; k < inputs.length; k++) {
                        var input = inputs[k];
                        var inputVal = parseFloat(input.value) || 0;

                        // Distribute the excess to other inputs
                        if (input !== this && inputVal > 0) {
                        var reduction = (inputVal / (total - currentValue)) * excess;
                        input.value = (inputVal - reduction).toFixed(0);
                        }
                    }
                    }
                });
            }
        });

        document.getElementById("one-way-trip").addEventListener("change", (e) => {
            const timeElement = document.getElementById("hidden");
            const dateElement = document.getElementById("hiddenWidth");
            const pickUpElement = document.getElementById("Drop-up");
            pickUpElement.classList.remove("d-none");
            dateElement.classList.add("d-none");
            timeElement.classList.add("d-none");
            returnDateInput.value = '';
            returnTimeInput.value = '';
        });

        document.getElementById("round-trip").addEventListener("change", (e) => {
            const timeElement = document.getElementById("hidden");
            const dateElement = document.getElementById("hiddenWidth");
            timeElement.classList.remove("d-none");
            dateElement.classList.remove("d-none");
        });

        // Add a click event listener to the "btn point-to-point" button
        pointToPointBtn.addEventListener("click", function() {
            serviceTypeInput = 1;
            // Show elements with the "hidden-point-to-point" class
            const ChangeDateWidth = document.getElementById("ChangeDateWidth");
            const roundTripRadio = document.getElementById("round-trip");
            const dropUpElementDiv = document.getElementById("Drop-up");
            const pickUpElementDiv = document.getElementById("pickup-location");
            const widthElement = document.getElementById("width");
            const iconArrow = document.getElementById("icon-arrow");
            roundTripRadio.checked = true;
            radioButtonStatus = false;

            if(!radioButtonStatus)
            {                
                dropUpElementDiv.classList.remove("d-none");
                pickUpElementDiv.classList.remove("d-inline");
                pickUpElementDiv.classList.remove("col-sm-12");
                pickUpElementDiv.classList.remove("col-12");
                pickUpElementDiv.classList.remove("col-md-12");
            }
            else
            {
                dropUpElementDiv.classList.add("d-none");
                pickUpElementDiv.classList.add("d-inline");
                pickUpElementDiv.classList.add("col-sm-12");
                pickUpElementDiv.classList.add("col-12");
                pickUpElementDiv.classList.add("col-md-12");
            }
       

            const pickUpElement = document.getElementById("Drop-up");
            pickUpElement.classList.remove("d-none");
            hiddenElements.forEach(function(element) {
                element.classList.remove("d-none");
            });

            // Hide elements with the "hourly-view" class
            hourlyViewElements.forEach(function(element) {
                element.style.display = "none";

            });

            // Check if the return time was selected do uncheck
        

           
           
        });

        // Add a click event listener to the "btn hourly" button
        hourlyBtn.addEventListener("click", function() {
            serviceTypeInput = 2;
            const ChangeDateWidth = document.getElementById("ChangeDateWidth");
            const roundTripRadio = document.getElementById("return-back");
            roundTripRadio.checked = false;
            // Hide elements with the "hidden-point-to-point" class
            hiddenElements.forEach(function(element) {
                element.classList.add("d-none");
            });

            // Show elements with the "hourly-view" class
            hourlyViewElements.forEach(function(element) {
                element.style.display = "block";
            });
          

        });

        // Define the filterCards function
        function filterCards(e) {
            document.querySelector(".active-btn").classList.remove("active-btn");
            e.target.classList.add("active-btn");
        }

        // Add click event listener to each filter button
        filterButtons.forEach((button) =>
            button.addEventListener("click", filterCards)
        );

        /** Call Google api on key up and copy events to get address*/
        pickUpLocationInput.addEventListener('keyup',function(e){
            const value = e.target.value
            if(value.length >= 3)
            {
                clearTimeout(setTimeOutFunction);
                setTimeOutFunction = setTimeout(() => {
                    if(oldInputValue.length <= value.length)
                    {
                        getPlaces('pickUpLocation',value)
                    }
                    oldInputValue = value;
                }, apiTimer);

            }
            else
            {
                $('#search-results').hide();
            }
        })

        pickUpLocationInput.addEventListener('copy',function(e){
            const value = e.target.value
            if(value.length >= 3)
            {
                clearTimeout(setTimeOutFunction);
                setTimeOutFunction = setTimeout(() => {
                    if(oldInputValue.length <= value.length)
                    {
                        getPlaces('pickUpLocation',value)
                    }
                    oldInputValue = value;
                }, apiTimer);

            }
            else
            {
                $('#search-results').hide();
            }
        })

        dropOffLocationInput.addEventListener('keyup',function(e){
            const value = e.target.value
            if(value.length >= 3)
            {
                clearTimeout(setTimeOutFunction);
                setTimeOutFunction = setTimeout(() => {
                    if(oldInputValueDropoff.length <= value.length)
                    {
                        getPlaces('dropOffLocation',value)
                    }
                    oldInputValueDropoff = value;
                }, apiTimer);

            }
            else
            {
                $('#search-results').hide();
            }
        });

        dropOffLocationInput.addEventListener('copy',function(e){
            const value = e.target.value
            if(value.length >= 3)
            {
                clearTimeout(setTimeOutFunction);
                setTimeOutFunction = setTimeout(() => {
                    if(oldInputValueDropoff.length <= value.length)
                    {
                        getPlaces('dropOffLocation',value)
                    }
                    oldInputValueDropoff = value;
                }, apiTimer);

            }
            else
            {
                $('#search-results').hide();
            }
        });

       async function getPlaces(input_id,query,take_first_result = false , reject = false)
        {
            $('#search-results').hide();
            $('#search-results-dropoff').hide();

            /** Add Loader Input **/
            if(input_id == "dropOffLocation")
            {
                /** Add Loader **/
                dropOffLocationLoader.classList.remove('d-none');
                dropOffLocationLoader.classList.add('d-block');

            }
            else
            {
                pickUpLocationLoader.classList.remove('d-none');
                pickUpLocationLoader.classList.add('d-block');
            }

            const placesApi = new google.maps.places.AutocompleteService();
            const options = {
                input:query,
                componentRestrictions:{
                    country:['us']
                }
            };

           
            /** GET PREDICATION (ADDRESS) FROM GOOGLE **/
           await placesApi.getPlacePredictions(options, function(predictions, status){
                if(status == 'OK')
                {
                    $('#search-results-dropoff').empty();
                    $('#search-results').empty();
                    predictions.forEach((prediction,index) => {

                        if(index < 4)
                        {
                            if(input_id == 'dropOffLocation')
                            {
                                if(index == 0&&take_first_result == true)
                                {
                                    dropoffLocationgoogleId = prediction.place_id;
                                    dropOffLocationInput.value = prediction.description;
                                    return;
                                }

                                $('#search-results-dropoff').show();
                                const placeId = prediction.place_id;
                                const option = `<button onclick="getPlaceDetails('${placeId}','dropoff','${prediction.description}')" type="button" class="w-100 option"> <i class="bi bi-geo-alt"></i> ${prediction.description}</button>`;
                                $('#search-results-dropoff').append(option);
                            }
                            else
                            {
                                if(index == 0&&take_first_result == true)
                                {
                                    pickUpLocationgoogleId = prediction.place_id;
                                    pickUpLocationInput.value = prediction.description;
                                    return;
                                }
                                $('#search-results').show();
                                const placeId = prediction.place_id;
                                const option = `<button onclick="getPlaceDetails('${placeId}','pickUp','${prediction.description}')" type="button" class="w-100 option"> <i class="bi bi-geo-alt"></i> ${prediction.description}</button>`;
                                $('#search-results').append(option);
                            }
                        }
                        if(input_id == "dropOffLocation")
                        {
                            /** Add Loader **/
                            dropOffLocationLoader.classList.add('d-none');
                            dropOffLocationLoader.classList.remove('d-block');

                        }
                        else
                        {
                            pickUpLocationLoader.classList.add('d-none');
                            pickUpLocationLoader.classList.remove('d-block');
                        }
                    });
                }
                else if(status == 'ZERO_RESULTS')
                {
                    if(input_id == "dropOffLocation")
                        {
                            /** Add Loader **/
                            dropOffLocationLoader.classList.add('d-none');
                            dropOffLocationLoader.classList.remove('d-block');

                        }
                        else
                        {
                            pickUpLocationLoader.classList.add('d-none');
                            pickUpLocationLoader.classList.remove('d-block');
                        }


                        if(reject)
                        {
                           new Error('Zero results found');
                        }
                       

                        return false;
                }
                


            });
        }

        function getPlaceDetails(place_id,type = 'pickUp',place_name)
        {
            /** GET THE PLACES DETAILS */
            if(type == 'pickUp')
            {
                pickUpLocationgoogleId = place_id;
                pickUpLocationInput.value = place_name;
                $('#search-results').hide();
            }
            else
            {
                dropoffLocationgoogleId = place_id;
                dropOffLocationInput.value = place_name;
                $('#search-results-dropoff').hide();
            }
            
        }

       async function checkTripInfo(form)
        {
            let request = '{{ route("frontEnd.reservations.check_info") }}';

            if(!pickUpLocationgoogleId)
            {
                alert('please add correct pick up address');
                return;
            }
            if(!dropoffLocationgoogleId)
            {
                alert('please add correct drop off address');
                return;
            }

            // add pickup and dropoff google locations ids
            request = request+'?pickUpLocation='+pickUpLocationgoogleId;
            request = request+'&dropOffLocation='+dropoffLocationgoogleId;

            //add pickup time and date
            request = request+'&pickupDate='+pickupDateInput.value;
            request = request+'&pickupTime='+pickupTimeInput.value;

            // initiate the loading 
            Swal.fire({
                title: '',
                allowEscapeKey: false,
                allowOutsideClick: false,
                showConfirmButton: false,
                timerProgressBar: true,
                didOpen: () => {
                            Swal.showLoading()
                            const b = Swal.getHtmlContainer().querySelector('b')
                          
                        },
                onOpen: () => {
                Swal.showLoading();
            }
            });

            $.ajax({
                url:request,
                type:'GET',
                success:(res)=>{
                    const rules = res.data;
                    Swal.close();
                    console.log('service_id: ',serviceTypeInput)

                    //check if the service point to point is avalibale for the pick up
                    if(rules.service_restriction_pick_up&&serviceTypeInput == 1 && rules.service_restriction_pick_up.point_to_point == false)
                    {
                        $('#returnTimeInput-error').html('Point to point is not available for the pickup location.');
                        return false;
                    }

                    //check if the service hourly is avalibale for the pick up
                    if(rules.service_restriction_pick_up&&serviceTypeInput == 2 && rules.service_restriction_pick_up.hourly == false)
                    {
                        $('#returnTimeInput-error').html('Hourly is not available');
                        return false;
                    }

                    //check if the address is avalibale for the pickup
                    if(rules.service_restriction_pick_up&& rules.service_restriction_pick_up.pick_up == false)
                    {
                        $('#returnTimeInput-error').html('Hourly is not available for the pickup location.');
                        return false;
                    }

                    //check if the point to point is avaliable for the drop off
                    if(rules.service_restriction_dropoff&&serviceTypeInput == 1 && rules.service_restriction_dropoff.point_to_point == false)
                    {
                        $('#returnTimeInput-error').html('Point to point is not available for the drop-off location.');
                        return false;
                    }

                    //check if the 
                    // if(rules.service_restriction_dropoff&&serviceTypeInput == 2 && rules.service_restriction_dropoff.hourly == false)
                    // {
                    //     $('#returnTimeInput-error').html('Hourly is not available for the drop-off location.');
                    //     return false;
                    // }

                    // if(rules.service_restriction_dropoff&& rules.service_restriction_dropoff.pick_up == false)
                    // {
                    //     $('#returnTimeInput-error').html('Hourly is not available for the drop-off location.');
                    //     return false;
                    // }

                    if(!rules.check_pickup_time_availability)
                    {
                        $('#returnTimeInput-error').html('The pickup time is not available');

                        return false;
                    }
                    if(rules.service_restriction_dropoff)
                    {
                        $('#error-message-content-drop-off-location').html('the drop off is not available');
                        return false;
                    }
                   

                    return sendData(form);
                    
                },
                error:(err)=>
                {
                    console.log(err)
                }
            });
        }
       
        function sendData(form)
        {
                // initiate the loading 
                Swal.fire({
                title: '',
                allowEscapeKey: false,
                allowOutsideClick: false,
                showConfirmButton: false,
                timerProgressBar: true,
                didOpen: () => {
                            Swal.showLoading()
                            const b = Swal.getHtmlContainer().querySelector('b')
                          
                        },
                onOpen: () => {
                Swal.showLoading();
            }
            });
            console.log("pick up location: ",pickUpLocationgoogleId)
            if(!pickUpLocationgoogleId)
            {
                alert('please add correct address pick up address');
                return;
            }
            if(!pickUpLocationgoogleId)
            {
                alert('please add correct drop off address');
                return;
            }
                    
                    var formData = new FormData(document.getElementById('AddForm'));
    
                    // Append additional data
                    formData.append("service_type", UpdateServiceTypeValue);
                    formData.append("pickup_location_google_id", pickUpLocationgoogleId);
                    formData.append("dropOff_location_google_id", dropoffLocationgoogleId);
    
                    const childSeatsInputs = document.getElementsByClassName('child-seats-input');
                    let childSeats = [];
    
                    for (var i = 0; i < childSeatsInputs.length; i++) {
                        var child_seats_id = childSeatsInputs[i].getAttribute('child-seat-id');
                        let amount = childSeatsInputs[i].value;
    
                        let obj = {
                            child_seats_id,
                            amount
                        };
                       
                        childSeats.push(obj);
                    }
    
                    // formData.append('seats',childSeats);
                    formData.append('seats', JSON.stringify(childSeats));
                    
                    // You can also serialize the form data to send it via AJAX or perform other actions
                    var serializedFormData = $(form).serialize();
    
                    // Now, you can submit the form data or perform any other necessary actions
                    $.ajax({
                        url: "{{ route('frontEnd.reservations.store') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.data && response.data.url) {
                                // Redirect to the URL provided in the response
                               // clearInputs();
                                Swal.close()
                                window.location.href = response.data.url;
                            } else {
                                // Handle the case where the response does not contain a valid URL
                                console.log("Invalid or missing URL in the response.");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('err')
                        }
                    });
        }

        function clearInputs() 
        {
            var inputElements = document.querySelectorAll('input');
            inputElements.forEach(function(input) {
                if (input.type === 'text' || input.type === 'number' || input.type === 'password' || input.type === 'search' || input.type === 'tel' || input.type === 'url' || input.type === 'email' || input.type === 'date' || input.type === 'time' || input.type === 'datetime-local' || input.type === 'week' || input.type === 'month' || input.type === 'color' || input.type === 'range') {
                input.value = "";
                } else if (input.type === 'checkbox' || input.type === 'radio') {
                input.checked = false;
                } else if (input.type === 'file') {
                input.value = null;
                } else {
                input.value = "";
                }
            });
        }

        function toggleReturnTimeInputs()
        {
            const dropUpElement = document.getElementById("Drop-up");
            const pickUpElement = document.getElementById("pickup-location");
            const widthElement = document.getElementById("width");
            const iconArrow = document.getElementById("icon-arrow");
            const ChangeDateWidth = document.getElementById("ChangeDateWidth");
            dropUpElement.classList.toggle("d-none");
            pickUpElement.classList.toggle("d-inline");
            pickUpElement.classList.toggle("col-sm-12");
            pickUpElement.classList.toggle("col-12");
            pickUpElement.classList.toggle("col-md-12");
        }

    </script>
@endsection