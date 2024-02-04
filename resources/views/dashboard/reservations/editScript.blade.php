<script>
    const pickUpLocationInput = document.getElementById('pickUploaction');
    const dropOffLocation = document.getElementById('drop-off-location');
    const placesGrid = document.getElementById('modalPlacesGrid');
    const searchResultsList = document.getElementById('dropdownList');
    const searchResultsListDropOff = document.getElementById('dropdownList-dropoff-location');
    const pickUpButton = document.getElementById('dropdownButton');
    const dropOffButton = document.getElementById('dropdownButton-dropoff');
    const searchInput = document.getElementById('pick_up_location');
    const searchResults = document.getElementById('search-results');
    const returnTimeInput = document.getElementById('return_time');
    const searchForm = document.getElementById('search-form');
    const serviceTypeInput = document.getElementById('service_type');
    const durationInput = document.getElementById('duration');
    const livePricingInput = document.getElementById('live_pricing');
    const fleetInput = document.getElementById('fleet');
    const chaffeurInput = document.getElementById('chaffeur');
    const createButton = document.getElementById('createButton');
    const returnAsPickUpButton = document.getElementById('returnAsPickUpButton');
    const firstNameInput = document.getElementById('firstNameInput');
    const lastNameInput = document.getElementById('lastNameInput');
    const emailInput = document.getElementById('emailInput');
    const customerIdInput = document.getElementById('customerId');
    const paymentIdInput = document.getElementById('paymentId');
    const transferTypeInput = document.getElementById('transfer_type');
    const phoneInput = document.getElementById('phone');
    const pickUpLocationPointToPointInput = document.getElementById('pickUploactionPointToPoint');
    const dropdownListPointToPointInput = document.getElementById('dropdownListPointToPoint');
    const returnInfoSection = document.getElementById('return_info_section');
    const returnDateInput = document.getElementById('return_date');
    const getReservingTimeInput = document.getElementById('getReservingTime');
    const couponCodeInput = document.getElementById('coupon_code');
    const priceInput = document.getElementById('price');
    const milesInput = document.getElementById('miles')
    const cityInput = document.getElementById('city');
    const enablePriceCehckBox = document.getElementById('enablePrice');
    const pickUpTimeInput = document.getElementById('pickUpTime');
    const pickUpDateInput = document.getElementById('start_date');
    const child_seats = @json($childSeats);

    const routeName = '{{ Route::currentRouteName() }}'
    const pageType = '{{ $pageType }}'

    const isEditPage = {{ isset($reservation) ? 'true' : 'false' }}
    // let reservation = '{{ isset($reservation) ? json_encode($reservation) : null }}';
    let reservation = {!! isset($reservation) ? $reservation : 'null' !!};

    /** Importent variables **/
    let isPickAsDropOff = false;
    let flag = false;

if(isEditPage){
    pickUplocationObj = {
        latitude: reservation.latitude,
        longitude: reservation.longitude
    };

    dropOffLocationObj = {
        drop_latitude: reservation.dropoff_latitude,
        drop_longitude: reservation.dropoff_longitude
    };
    calculateDistanceAndPrice();
}



    let fleet = null;
    let distance = null;
    let map = null
    let isAirport = false;
    var firstOpen = true;
    let reservation_id = null;
    var time;
    let getLivePricingEdit = false;
    let changeCounter = 3;
    let reservationfleet = {{ $reservation->fleet_id ?? 'null' }}
    let defualtPriceInput = 1;

    /** Phone **/
    var input = document.querySelector("#phone");
    var flagContainer = document.querySelector("#flag-container");
    var iti = window.intlTelInput(input, {
        initialCountry: "US",
        geoIpLookup: function(callback) {},
        separateDialCode: true,
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
    });


    /** Package need to be loaded when the document is ready **/
    $(document).ready(function() {

        $('.select2').select2({
            theme: "bootstrap-5"
        });

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        $('#load-more').click(function() {
            var nextPageUrl = $(this).data('next-page');
            if (nextPageUrl) {
                $.ajax({
                    url: nextPageUrl,
                    method: 'GET',
                    success: function(data) {
                        // Append the new records to the container
                        $('#payment-intent-logs-container').append(data);

                        // Update the "Load More" button with the next page URL
                        var nextPage = $(data).find('#load-more').data('next-page');
                        $('#load-more').data('next-page', nextPage);

                        // Hide the button if there are no more pages
                        if (!nextPage) {
                            $('#load-more').hide();
                        }
                    }
                });
            }
        });



    });


    /** Section's need to be hiden **/
    $('#return_info_section').hide();
    $('#pointTpoint').hide();

    /** Form Valdation */

    /** Valdation for the end date before the start date */
    $.validator.addMethod("endDateAfterStartDate", function(value, element) {
        var startDate = $('#start_date').datepicker('getDate');
        var endDate = $('#return_date').datepicker('getDate');
        if (startDate && endDate) {
            return startDate <= endDate;
        }
        return true;
    }, "End date must be after start date");


    $("#AddForm").validate({
        rules: {
            question: {
                required: true
            },
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            email: {
                required: true
            },
            price: {
                required: {
                    depends: function() {
                        return livePricingInput.value == '' ? true : false;
                    }
                }
            },
            phone: {
                required: true
            },
            answer: {
                required: true
            },
            type: {
                required: true
            },
            start_date: {
                required: true
            },
            start_time: {
                required: true
            },
            drop_off_location: {
                required: {
                    depends: function() {
                        return isPickAsDropOff == false || serviceTypeInput.value == 1;
                    }
                }
            },
            fleet_category: {
                required: true
            },
            duration: {
                required: true
            },
            pick_up_location: {
                required: true
            },
            return_date: {
                required: {
                    depends: function() {
                        return transferTypeInput.value == "Round" ? true : false
                    }
                },
                endDateAfterStartDate: true
            },
            return_time: {
                required: {
                    depends: function() {
                        return transferTypeInput.value == "Round" ? true : false
                    }
                }
            },

        },
        errorPlacement: function(error, element) {
            var inputId = element.attr('id');

            if (inputId == 'phone') {
                error.addClass('error-message');
                error.appendTo($('#phoneInput-error'));

            } else if (inputId == 'return_time') {
                error.addClass('error-message');
                error.appendTo($('#returnTimeInput-error'));

            } else if (inputId == 'fleet_category') {
                error.addClass('error-message');
                error.appendTo($('#fleet_category-error'));


            } else if (inputId == 'pickUpTime') {
                error.addClass('error-message');
                error.appendTo($('#pickUpTime-error'));

            } else if (inputId == 'start_date') {
                error.addClass('error-message');
                error.appendTo($('#start_date-error'));
            } else {
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
        submitHandler: function(form) {
            if (!pickUplocationObj.latitude || !pickUplocationObj.longitude) {
                Toast.fire({
                    icon: 'error',
                    title: "Kindly locate the pickup point by clicking on the 'get' button under the pickup input"
                })
                return false;
            }
            if (!dropOffLocationObj.drop_latitude || (!dropOffLocation.disabled && !dropOffLocationObj
                    .drop_longitude)) {
                Toast.fire({
                    icon: 'error',
                    title: "Kindly locate the dropoff point by clicking on the 'get' button under the dropoff input"
                })
                return false;
            }

            return true;
        }
    });

    /** Call this function when edit appear */
    if (routeName == 'dashboard.reservations.edit') applyEditData();



    /** Wait for the document loaded than do some logic in javescript */
    document.addEventListener('DOMContentLoaded', function() {

        const dropdownButton = document.getElementById('dropdownButton');
        const selectedValueInput = document.getElementById('pickUploaction');
        const dropdownList = document.getElementById('dropdownList');
        const dateInput = document.getElementById('start_date');
        dateInput.addEventListener('click', function() {
            $('#start_date').datepicker('show');
        });


        // Set selected value in the input when a dropdown item is clicked
        dropdownList.addEventListener('click', function(event) {
            if (event.target.tagName === 'LI' || event.target.tagName == 'BUTTON') {
                selectedValueInput.value = event.target.textContent;
                dropdownList.style.display = 'none';
            }
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!dropdownList.contains(event.target) && event.target !== dropdownButton) {
                dropdownList.style.display = 'none';
            }
        });

        searchResultsListDropOff.addEventListener('click', function(event) {
            if (event.target.tagName === 'LI' || event.target.tagName == 'BUTTON') {
                dropOffLocation.value = event.target.textContent;
                searchResultsListDropOff.style.display = 'none';
            }
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!searchResultsListDropOff.contains(event.target) && event.target !== dropOffButton) {
                searchResultsListDropOff.style.display = 'none';
            }
        });

        /** For remove the data if the user start typing on the input **/
        document.getElementById("pickUploaction").addEventListener("keydown", function(event) {
            pickUplocationObj.latitude = null;
            pickUplocationObj.longitude = null;
            distance = null;
            milesInput.value = null;
            $('#mileSpan').empty();
        });
        document.getElementById("drop-off-location").addEventListener("keydown", function(event) {
            dropOffLocationObj.drop_latitude = null;
            dropOffLocationObj.drop_longitude = null;
            distance = null;
            $('#mileSpan').empty();
        });





    });


    function openChildSeatsModal() {
        if (!chickChildSeatsInputs()) {
            Toast.fire({
                icon: 'info',
                title: 'You have reached the max number of seats inputs'
            })
            return;
        }
        if (!checkChildSeatsNumber()) {
            Toast.fire({
                icon: 'info',
                title: 'You have reached the max number of seats'
            })
            return;
        };
        getChildSeats();

        // let options = "<option value=''>Select</option>";
        let options = "";
        var min = 1;
        var max = 10000;
        var idDivSeats = Math.floor(Math.random() * (max - min + 1)) + min;
        var idDivAmount = Math.floor(Math.random() * (max - min + 1)) + min;
        var containerId = Math.floor(Math.random() * (max - min + 1)) + min

        for (let index = 0; index < child_seats.length; index++) {
            const element = child_seats[index];
            options = options +
                `<option data-title="${element.title}" data-price="${element.price}" value="${element.id}">${element.title}</option>`;
        }

        let item = ` <div id="${containerId}" class="row mt-3">
            <div class="col-6">
                <label for="">Seat Name</label>
                <select onchange="getPriceAccordingToFleet()" class="form-control select2" name="child_seats[][seats]" id="${idDivSeats}">
                    ` + options + `
                </select>
        </div>
        <div class="col-6">
             <label for="">Amount</label>
            <select id="${idDivAmount}" onchange="checkChildSeatsNumber()" class="form-control select2" name="child_seats[][amount]">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
            <div class="text-right">
                    <button onclick="deleteChildSeat(${containerId})" class="mt-2 btn btn-danger btn-sm"> <i class="fa fa-trash"></i> </button>                    
            </div>
        </div>
      </div>`

        $('#seatsSections').append(item);
        getChildSeats();
        getPriceAccordingToFleet();
    }

    function checkChildSeatsNumber() {
        var childSeatsAmountInputs = document.querySelectorAll('select[name^="child_seats[][amount]"]');
        var seatsPrice = document.querySelectorAll('select[name^="child_seats[][seats]"]');

        let seatsNumber = 0
        childSeatsAmountInputs.forEach(function(selectElement) {
            seatsNumber = seatsNumber + Number(selectElement.value);
        });
        console.log(reservation_id)
        let actionButtonJquery = reservation_id ? $('#editButton') : $('#createButton');

        if (seatsNumber > 3) {
            actionButtonJquery.disabled = true;
            actionButtonJquery.empty();
            actionButtonJquery.append('You exceed the number of child seats');
        } else {
            actionButtonJquery.disabled = false;
            actionButtonJquery.empty();
            actionButtonJquery.append(reservation_id ? 'Update' : 'Create');
        }
        if (seatsNumber <= 3) {
            getPriceAccordingToFleet();
            $("#warningLabel").empty()
            return true;
        } else {
            var warningLabel = $('<span class="text-danger"> *Exceeded child seats limit</span>');
            $("#warningLabel").empty().append(warningLabel);
        }
        return false;
    }

    function chickChildSeatsInputs() {
        var inputs = document.querySelectorAll('select[name^="child_seats[][seats]"]');
        if (inputs.length + 1 > 3) return false;
        return true;
    }

    function showEndDate() {
        const isChecked = $('#fixRoundTrip').prop('checked');
        if (isChecked) {
            $('.end-time').show();
        } else {
            $('.end-time').hide();
        }
    }

    function showDropOffLocation() {

        const isChecked = $('#showDropofflocation').prop('checked');
        if (!isChecked) {
            $('#pick-up-location').addClass('col-6');
            $('#pick-up-location').removeClass('col-12');
            $('.drop-offlocation').show();
        } else {
            $('#pick-up-location').removeClass('col-6');
            $('#pick-up-location').addClass('col-12');
            $('.drop-offlocation').hide();
        }
    }

    function getLocation(type, serviceType = 'Hourly') {


        const service = new google.maps.places.PlacesService(document.createElement('div'));
        const pickUpQuery = serviceType == 'Hourly' ? pickUpLocationInput.value : pickUpLocationPointToPointInput.value;
        const dropdownListContainer = serviceType == "Hourly" ? 'dropdownList' : 'dropdownListPointToPoint';
        // Get references to the button and loader
        var button = document.getElementById('dropdownButton');
        var loader = document.getElementById('loader');

        // Disable the button
        button.disabled = true;

        // Show the loader
        loader.style.display = 'inline-block';

        // Simulate a time-consuming process (replace this with your actual process)
        setTimeout(function() {
            // Enable the button
            button.disabled = false;

            // Hide the loader
            loader.style.display = 'none';
        }, 3000);
        service.textSearch({
            query: type == 'pickUp' ? pickUpQuery : dropOffLocation.value
        }, (results, status) => {
            if (results.length == 0) {
                Toast.fire({
                    icon: 'info',
                    title: 'Address not found'
                })
                if (type == "pickUp") {
                    pickUplocationObj.latitude = null;
                    pickUplocationObj.longitude = null;
                    pickUplocationObj.name = null;
                    returnAsPickUpButton.disabled = true;
                } else {
                    dropOffLocationObj.drop_latitude = null;
                    dropOffLocationObj.drop_longitude = null;
                    dropOffLocationObj.drop_name = null;
                }


                return;
            }
            if (status === google.maps.places.PlacesServiceStatus.OK) {

                $('#'.dropdownListContainer).empty();
                let dropdown = results.map(result =>
                    `<button type="button" class="w-100 reset-button" onclick="selectLocation('dropOff','${result.name}','${result.geometry.location.lat()}','${result.geometry.location.lng()}')" class="reset-button">${result.formatted_address}</button>`
                ).join('');
                if (type == "pickUp") dropdown = results.map(result =>
                    `<button type="button" class="w-100 reset-button" onclick="selectLocation('pickUp','${result.name}','${result.geometry.location.lat()}','${result.geometry.location.lng()}')" class="reset-button">${result.formatted_address}</button>`
                ).join('');

                /** Show List **/
                if (type == "pickUp") {
                    searchResultsList.innerHTML = dropdown;
                    dropdownList.style.display = 'block';
                } else {
                    searchResultsListDropOff.innerHTML = dropdown;
                    searchResultsListDropOff.style.display = 'block';
                }

            }
        });
    }

    function selectLocation(type, placeName, latitude, longitude) {

        if (type == "pickUp") {
            pickUplocationObj.name = placeName;
            pickUplocationObj.latitude = latitude;
            pickUplocationObj.longitude = longitude;
            returnAsPickUpButton.disabled = false;
            calculateDistanceAndPrice();

        } else {
            dropOffLocationObj.drop_name = placeName;
            dropOffLocationObj.drop_latitude = latitude;
            dropOffLocationObj.drop_longitude = longitude;
            calculateDistanceAndPrice();

        }



    }

    function calculateDistanceAndPrice() {
        const originLat = parseFloat(pickUplocationObj.latitude);
        const originLng = parseFloat(pickUplocationObj.longitude);
        const destinationLat = parseFloat(dropOffLocationObj.drop_latitude);
        const destinationLng = parseFloat(dropOffLocationObj.drop_longitude);

        if (!isNaN(originLat) && !isNaN(originLng) && !isNaN(destinationLat) && !isNaN(destinationLng)) {
            const origin = new google.maps.LatLng(originLat, originLng);
            const destination = new google.maps.LatLng(destinationLat, destinationLng);

            const directionsService = new google.maps.DirectionsService();

            directionsService.route({
                    origin: origin,
                    destination: destination,
                    travelMode: google.maps.TravelMode.DRIVING
                },
                function(response, status) {
                    $('#mileSpan').empty()
                    if (status === 'OK') {
                        const distanceInMeters = response.routes[0].legs[0].distance.value;
                        const distanceInMiles = (distanceInMeters / 1609.34);
                        // $('#mileSpan').append(distanceInMiles+" Miles");
                        distance = distanceInMiles
                        console.log(distance);
                        milesInput.value = distance;
                        getPriceAccordingToFleet();
                        console.log(`Driving Distance: ${distanceInMiles}`);
                    } else {
                        $('#mileSpan').append('A car cannot cover the specified distance.')
                        dropOffLocation.value = '';
                        distance = null;
                    }
                }
            );
        } else {
            $('#mileSpan').append('Please enter valid latitude and longitude coordinates')
            console.log('Please enter valid latitude and longitude coordinates');
        }

    }

    function getPriceAccordingToFleet() {
        
        
        let fleetSelect = document.getElementById('fleet');
        let selectedFleetId = fleetSelect.value;
        let fleet_id = document.getElementById('fleet_category').value;
        const childSeats = getChildSeats();

        let selectedOption = cityInput.options[cityInput.selectedIndex];
        defualtPriceInput = cityInput.value == '' ? 1 : 0;


        if (cityInput.value !== '') {
            getReservingTimeInput.removeAttribute('disabled');
        } else {
            getReservingTimeInput.setAttribute('disabled', 'disabled');
        }
        let cityName = selectedOption.getAttribute("title");
        const data = {
            cityName: cityName,
            serviceType: serviceTypeInput.value,
            distance: distance,
            lat: pickUplocationObj.latitude,
            long: pickUplocationObj.longitude,
            drop_lat: dropOffLocationObj.drop_latitude,
            drop_long: dropOffLocationObj.drop_longitude,
            hours: durationInput.value,
            childSeats: childSeats,
            fleetDefaultPricing: defualtPriceInput,
            isRoundTrip: transferTypeInput.value == 'Round' ? 1 : 0,
            couponCode: couponCodeInput.value,
            getReservingTime: getReservingTimeInput.value ?? 0,
            pickUpTime: pickUpTimeInput.value,
            pickUpDate: pickUpDateInput.value,
            _token: '{{ csrf_token() }}'
        };


        let url = '{{ route('dashboard.reservations.getPrice', ':id') }}';
        if (!fleet_id) return;
        url = url.replace(':id', fleet_id)
        $.ajax({
            url,
            type: "POST",
            data,
            success: function(res) {
                const data = res.data;
                changeCounter--
       
                if (changeCounter <= 0) {

                    //If this was enabled don't take the live pricing. 
                    if (!enablePriceCehckBox.checked && reservation) {
                        console.log('change price 1')

                        // livePricingInput.value = reservation.price_with_tip;
                        livePricingInput.value = data.price;

                    } else {
                        console.log('change price 2')
                        // update the live priceing without charge 
                        if (reservation) {
                            console.log('chekcbox')
                            livePricingInput.value = enablePriceCehckBox.checked ? reservation
                                .price_with_tip : (data.price).toFixed(2);
                        } else if (!reservation && !enablePriceCehckBox.checked) {
                            livePricingInput.value = (data.price).toFixed(2);
                        } else {
                            livePricingInput.value = document.getElementById('new_id_value').value;
                        }

                    }

                } else {

                    if (!enablePriceCehckBox.checked) //liveprice
                    {
                        console.log('change price 3')
                        livePricingInput.value = data.price;

                    }

                }

                // Get the currently selected fleet before clearing the options
                let selectedFleet = fleetSelect.options[fleetSelect.selectedIndex];

                $('#fleet').empty();
                if (data.fleets.length == 0) {
                    Toast.fire({
                        icon: 'info',
                        title: 'No fleets found'
                    })
                    fleetSelect.disabled = true;
                    chaffeurInput.value = "";
                    return;
                }
                fleetSelect.disabled = false;

                const defaultOption = '<option value="" selected>Select a fleet</option>';
                $('#fleet').append(defaultOption);
                for (let i = 0; i < data.fleets.length; i++) {
                    const item = data.fleets[i];
                    const option =
                        `<option ${ reservationfleet && reservationfleet == item.id ? 'selected' : '' } value="${item.id}" >${item.title}</option>`;
                    $('#fleet').append(option);
                }

                // Restore the selected fleet after updating the options
                if (selectedFleet) {
                    fleetSelect.value = selectedFleet.value;
                }
            },
            error: function(err) {
                Toast.fire({
                    icon: 'error',
                    title: 'Server error getting the price!'
                })
            }
        })
    }

    function returnAsPickUp() {
        isPickAsDropOff = !isPickAsDropOff;
        if (isPickAsDropOff) {
            dropOffLocation.disabled = true;
            pickUpLocationInput.disabled = true;
            pickUpButton.disabled = true;
            dropOffButton.disabled = true;
            $('#returnAsPickUpButton').empty('');
            $('#returnAsPickUpButton').append('Remove')
            dropOffLocationObj.drop_latitude = pickUplocationObj.latitude;
            dropOffLocationObj.drop_longitude = pickUplocationObj.longitude;
            dropOffLocation.value = pickUpLocationInput.value
            calculateDistanceAndPrice();
        } else {
            dropOffLocation.disabled = false;
            pickUpLocationInput.disabled = false;
            pickUpButton.disabled = false;
            dropOffButton.disabled = false;
            dropOffLocation.value = '';
            $('#returnAsPickUpButton').empty('');
            $('#returnAsPickUpButton').append('Return as pick up')
            dropOffLocationObj = {};
            calculateDistanceAndPrice();
        }
    }

    function getChildSeats() {
        let childSeatsArray = [];
        var childSeatsSeatsInputs = document.querySelectorAll('select[name^="child_seats[][seats]"]');
        console.log(childSeatsSeatsInputs);

        var childSeatsAmountInputs = document.querySelectorAll('select[name^="child_seats[][amount]"]');
        childSeatsSeatsInputs.forEach(function(selectElement) {
            var seatSelect = document.getElementById(selectElement.id);
            var selectedOption = seatSelect.options[seatSelect.selectedIndex];
            var dataPriceValue = selectedOption.getAttribute('data-price');
            var dataTitleVAlue = selectedOption.getAttribute('data-title');
            var obj = {
                'title': dataTitleVAlue,
                'price': dataPriceValue
            };
            childSeatsArray.push(obj);
        });

        childSeatsAmountInputs.forEach(function(selectElement, index) {
            var seatSelect = document.getElementById(selectElement.id);
            childSeatsArray[index].amount = seatSelect.value
        });
        return childSeatsArray;
    }

    function deleteChildSeat(id) {
        $("#" + id).remove();
        chickChildSeatsInputs();
        checkChildSeatsNumber();
        getPriceAccordingToFleet();
    }

    function getCustomer() {
        Swal.fire({
            title: "Fill in the customer's email",
            input: 'text',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Look up',
            showLoaderOnConfirm: true,
            preConfirm: (email) => {
                let url = '{{ route('dashboard.reservations.get_customer', ':email') }}';
                url = url.replace(":email", email)
                return $.ajax({
                    url,
                    type: 'GET',
                    success: (res) => {
                        return res;

                    },
                    error: (err) => {
                        firstNameInput.value = '';
                        lastNameInput.value = '';
                        emailInput.value = '';
                        customerIdInput.value = '';
                        phoneInput.value = '';

                        firstNameInput.disabled = false;
                        lastNameInput.disabled = false;
                        emailInput.disabled = false;
                        customerIdInput.disabled = false;
                        phoneInput.disabled = false;
                        Swal.showValidationMessage(
                            `Customer not in records`
                        )
                        Swal.hideLoading()
                    }
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((res) => {
            if (!res.value.data.is_found) {
                Toast.fire({
                    icon: 'info',
                    title: 'User not in records',
                })
                return;
            }

            let data = res.value.data.user;
            firstNameInput.value = data.first_name;
            lastNameInput.value = data.last_name;
            emailInput.value = data.email;
            phoneInput.value = data.phone;
            customerIdInput.value = data.stripe_id ?? null;
            iti.setNumber("+" + data.country_code + data.phone);


            firstNameInput.disabled = true;
            lastNameInput.disabled = true;
            emailInput.disabled = true;
            customerIdInput.disabled = true;
            phoneInput.disabled = true;

        })
    }

    function validateData() {
        const currentPage = '{{ $pageType }}'
        let timerInterval = Swal.fire({
            title: 'Awaiting data validation',
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading()

            },
            willClose: () => {
                clearInterval(timerInterval)
            }
        }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log('I was closed by the timer')
            }
        })

        const url = '{{ route('dashboard.reservations.checkReservationInfo') }}';
        $.ajax({
            url,
            type: "POST",
            data: {
                email: emailInput.value,
                customer_id: customerIdInput.value,
                // payment_id: paymentIdInput.value,
                phone: phoneInput.value,
                _token: '{{ csrf_token() }}'
            },
            success: (res) => {
                let isCurrentCustomer = emailInput.disabled;
                let data = res.data;
                // if (data.is_email_exist && !isCurrentCustomer) {
                //     Toast.fire({
                //         icon: 'error',
                //         title: 'Email already exist'
                //     })
                //     return;
                // }
                // if (data.is_phone_exist && !isCurrentCustomer) {
                //     Toast.fire({
                //         icon: 'error',
                //         title: 'Phone already exist'
                //     })
                //     return;
                // }
                if (data.found_customer && !isCurrentCustomer) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Customer Already exist'
                    })
                    return;
                }
                if (currentPage == 'create' && !data.is_customer_id_available && isCurrentCustomer) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Customer id is not available'
                    });
                    return;
                }

                if (data.paymentStatus != null && data.paymentStatus != 'Found!') {
                    Toast.fire({
                        icon: 'error',
                        title: data.paymentStatus
                    })
                    return;
                }
                if (data.payment_Intent_is_exist) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Payment intent id already exist'
                    })
                    return;
                }

                Toast.close();
                submitData();
            },
            error: (err) => {
                console.log(err)
            }
        })
    }

    function submitData() {
        if (pickUplocationObj.latitude == null || pickUplocationObj.longitude == null) {
            Toast.fire({
                icon: 'error',
                title: "Kindly locate the pickup point by clicking on the 'get' button under the pickup input"
            })
            return;
        }
        if (dropOffLocationObj.drop_latitude == null || dropOffLocationObj.drop_longitude == null) {
            Toast.fire({
                icon: 'error',
                title: "Kindly locate the dropoff point by clicking on the 'get' button under the dropoff input"
            })
            return;
        }
        if ($("#AddForm").valid()) {
            var myDiv = document.getElementById('pointTpoint');
            myDiv.style.display = 'block';

            var inputElements = myDiv.querySelectorAll('input');

            inputElements.forEach(function(inputElement) {
                inputElement.parentNode.removeChild(inputElement);
            });

            /** IF THE SERVICE TYPE 1 **/
            if (serviceTypeInput.value == 2) {
                var myDiv = document.getElementById('pointTpoint');
                var disabledContainerInput = myDiv.querySelectorAll('input');
                disabledContainerInput.forEach(function(inputElement) {
                    console.log(inputElement)
                    inputElement.disabled = true;
                });
            }

            let timerInterval = Swal.fire({
                title: 'Submiting data',
                timerProgressBar: true,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
            }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log('I was closed by the timer')
                }
            })
            /** check the lat/long data **/
            const formElement = document.getElementById("AddForm");
            const url = formElement.action;
            firstNameInput.disabled = false;
            pickUpLocationInput.disabled = false;
            dropOffLocation.disabled = false;
            lastNameInput.disabled = false;
            emailInput.disabled = false;
            customerIdInput.disabled = false;
            phoneInput.disabled = false;
            const formData = new FormData(formElement);
            formData.append('price', livePricingInput.value);

            const childSeatInputs = document.querySelectorAll('[name^="child_seats"]');
            let seats = [];
            let counter = 0;
            childSeatInputs.forEach(function(input, index) {
                let obj = {};
                let name = input.name;
                if (name == 'child_seats[][seats]' && obj.seats == null) {
                    obj.seats = childSeatInputs[index].value;
                    obj.amount = childSeatInputs[index + 1].value;
                    seats.push(obj)
                } else {
                    obj.seats = null;
                    obj.amount = null;
                }
            });




            // Append additional data to the FormData object if needed
            for (var i = 0; i < seats.length; i++) {
                formData.append(`seats[${i}][seat]`, seats[i].seats);
                formData.append(`seats[${i}][amount]`, seats[i].amount);
            }
            //Add LatLong objects

            for (const key in pickUplocationObj) {
                if (pickUplocationObj.hasOwnProperty(key)) {
                    formData.append(key, pickUplocationObj[key]);
                }
            }
            for (const key in dropOffLocationObj) {
                if (dropOffLocationObj.hasOwnProperty(key)) {
                    formData.append(key, dropOffLocationObj[key]);
                }
            }
            // Get the selected country data
            var selectedCountryData = iti.getSelectedCountryData();

            // Add Extra inputs
            var selectedCountryCode = selectedCountryData.dialCode;
            formData.append('country_code', selectedCountryCode);

            if (returnTimeInput.value && returnDateInput.value) {
                formData.append('return_time', returnTimeInput.value);
                formData.append('return_date', returnDateInput.value);

            }

            // Send AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open("POST", url, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 201) {
                        window.location.href = '{{ route('dashboard.reservations.index') }}';
                    } else {
                        console.error("Error:", xhr.statusText);
                    }
                }
            };
            xhr.send(formData);
        }

    }

    function changeServiceType(event) {
        const value = event.target.value;

        if (value == 1) {
            $('#hourly').hide();
            $('#pointTpoint').show();
            transferTypeInput.disabled = false;
            transferTypeInput.value = "One Way";
            $('.returnAsPickUpSection').hide();
            $('#mileSpan').show();
            calculateDistanceAndPrice();
            getPriceAccordingToFleet();
            returnAsPickUpButton.checked = false;
            pickUpLocationInput.disabled = false;
            dropOffLocation.disabled = false;
            pickUpButton.disabled = false;
            dropOffButton.disabled = false;
        } else {
            $('#pointTpoint').hide();
            $('#hourly').show();
            transferTypeInput.disabled = true;
            transferTypeInput.value = "0";
            $('.returnAsPickUpSection').show();
            $('#mileSpan').show();

            // $('#mileSpan').hide();
            distance = null;
            // milesInput.value = '';
            calculateDistanceAndPrice();
            getPriceAccordingToFleet();
        }

    }

    function changeTransferType(event) {
        const value = event.target.value;
        const section = $('#return_info_section');
        if (value == 'Round') section.show();
        else section.hide();

        getPriceAccordingToFleet();
    }

    function getLivePricing() {
        priceInput.value = livePricingInput.value;
        return;
    }

    function applyEffect() {
        if (enablePriceCehckBox.checked) {
            livePricingInput.removeAttribute('disabled');
            livePricingInput.id = "new_id_value";
            flag = false;



        } else {
            livePricingInput.setAttribute('disabled', 'true');
            livePricingInput.id = "live_pricing";
            flag = true;


        }
    }


    // Add an event listener to the checkbox
    enablePriceCehckBox.addEventListener('change', applyEffect);

    function applyEditData() {
        @if (isset($reservation))
            reservation_id = '{{ $reservation->id }}';
            reservation = {!! json_encode($reservation) !!}
            fleet = '{{ $reservation->fleets }}';
            dropOffLocationObj = {
                drop_latitude: {{ $reservation->dropoff_latitude }},
                drop_longitude: {{ $reservation->dropoff_longitude }},
            };
            pickUplocationObj = {
                latitude: reservation.dropoff_latitude,
                longitude: reservation.dropoff_longitude,
            };
        @endif

        $('#fleet_category').trigger('change');
        $('#pickUploaction').trigger('click');
        $('#return_info_section').hide();

        // changeServiceType(event);
        getPriceAccordingToFleet();
        applyEffect();

    }


    function autoComplete() {
        var email = $('#emailInput').val();
        var path = "{{ route('dashboard.reservations.autocomplete') }}";
        $.ajax({
            type: 'GET',
            url: path, // Make sure 'path' variable is defined
            data: {
                'email': email
            },
            success: function(data) {
                // Clear the previous results
                $('#emailResults').empty();

                // Display available emails and make them clickable
                if (data.length > 0) {
                    for (var i = 0; i < data.length; i++) {
                        $('#emailResults').append('<div class="email-suggestion" data-email="' + data[i]
                            .email + '">' + data[i].email + '</div>');
                    }
                    // $('#customerId').prop('disabled', true);

                } else {
                    $('#firstNameInput').val('');
                    $('#lastNameInput').val('');
                    $('#phone').val('');
                    $('#emailResults').append('<div>No results found</div>');
                    $('#customerId').prop('disabled', false);
                    $('#emailResults').empty();

                }
            }

        });
        $(document).on('click', function(event) {
            var emailInput = $('#emailInput');
            var emailResults = $('#emailResults');

            // Check if the click is not on the email input or the email suggestion div
            if (!emailInput.is(event.target) && !emailResults.is(event.target) && emailResults.has(event
                    .target).length === 0) {
                emailResults.empty(); // Hide the email suggestion div
            }
        });
    }
    $(document).on('click', '.email-suggestion', function() {
        $('#emailResults').empty();
        $('#firstNameInput').val('');
        $('#lastNameInput').val('');
        $('#phone').val('');
        $('#customerId').val('');
        var selectedEmail = $(this).data('email');

        $('#emailInput').val(selectedEmail);
        // var selectedEmail = $(this).text(); // Get the email address from the element's text

        $('#emailInput').val(selectedEmail);
        // $('#emailResults').empty(); // Clear the suggestion list

        // Clear the corresponding input fields

        $('#customerId').prop('disabled', false); // Enable the customerId input field

        // Make an additional Ajax request to fetch related data based on the selected email
        $.ajax({
            type: 'GET',
            url: "{{ route('dashboard.reservations.autocomplete') }}",
            data: {
                'email': selectedEmail
            },
            success: function(data) {
                if (data.length > 0) {
                    // Populate the corresponding input fields with the retrieved data
                    $('#firstNameInput').val(data[0].first_name);
                    $('#lastNameInput').val(data[0].last_name);
                    $('#phone').val(data[0].phone);
                    $('#customerId').val(data[0].stripe_id);
                    // $('#customerId').prop('disabled', true);
                    // getPaymentMethods();
                }
            },
            error: function(xhr, status, error) {
                console.log('Error: ' + error);
            }
        });
    });



    document.getElementById('emailInput').addEventListener('input', function() {
        // getPaymentMethods();
        getcustomerIDs();
    });

    function getcustomerIDs() {
        var selectedEmail = document.getElementById('emailInput').value;
        console.log(selectedEmail);

        var customerIDSelect = document.getElementById('customerId');
        customerIDSelect.innerHTML = ''; // Clear the previous options

        if (selectedEmail) {
            // Make an AJAX request to get customer IDs for the selected email
            $.ajax({
                type: 'GET',
                url: "{{ route('dashboard.reservations.fetch-customer-ids') }}", // Update the route name
                data: {
                    email: selectedEmail
                },
                success: function(data) {
                    $('#customerId').empty();
                    console.log(data);

                    if (data && data.length > 0) {
                        $('#customerId').prop('disabled', false);

                        data.forEach(function(customerID) {
                            var option = document.createElement('option');
                            option.value = customerID;
                            option.text = customerID;
                            console.log(option.text);
                            customerId.appendChild(option);
                        });
                        customerIDSelect.selectedIndex = 0;

                    } else if (data.length == 0) {
                        $('#customerId').prop('disabled', true);

                    }
                }
            });
        }
    }

    // function getPaymentMethods() {
    //     var selectedEmail = document.getElementById('emailInput').value;
    //     console.log(selectedEmail);

    //     var paymentMethodSelect = document.getElementById('paymentMethodSelect');
    //     paymentMethodSelect.innerHTML = ''; // Clear the previous options

    //     if (selectedEmail) {
    //         // Make an AJAX request to get payment methods for the selected email
    //         $.ajax({
    //             type: 'GET',
    //             url: "{{ route('dashboard.reservations.fetch-payment-methods') }}",
    //             data: {
    //                 email: selectedEmail
    //             },
    //             success: function(data) {
    //                 $('#paymentMethodSelect').empty();
    //                 console.log(data);

    //                 if (data.data && data.data.length > 0) {
    //                     $('#paymentMethodSelect').prop('disabled', false);

    //                     data.data.forEach(function(method) {
    //                         var option = document.createElement('option');
    //                         option.value = method.id; // Use the appropriate value
    //                         option.text = method.card.brand + '  **** **** **** ' + method.card
    //                             .last4;
    //                         console.log(option.text);
    //                         paymentMethodSelect.appendChild(option);
    //                     });
    //                 } else {
    //                     console.log('no payment methods');
    //                 }
    //             }
    //         });
    //     }
    // }
</script>
