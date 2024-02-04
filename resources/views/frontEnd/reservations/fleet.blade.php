@extends('frontEnd.layouts.index')
@section('pageTitle')
        Book your very own private chauffeur service today
@endsection
@section('seo')
    <meta name="title" content="Book your very own private chauffeur service today">
    <meta name="description"
        content="Book your private chauffeur service today! Choose from a range of vehicles tailored to your needs. From luxury sedan, and luxury SUV, to sprinter van.">
    <meta name="keywords"
        content="call a ride reservations, airport transfer booking, book in advance, book a transfer online, transportation booking, rental limo services, rent limo service, book transport, advance reservation, reserve transportation, book a chauffeur, rent a limo service, book your ride online, book car service online, luxury suv rental houston, houston town car, best limo service in houston, town car service houston">
    <meta name="robots" content="index, follow">
    <meta name="language" content="EN">
    <link rel="canonical" href="https://lavishride.com/reservations/vehicles" />
    <meta name="google-signin-client_id" content="{{ env('GOOGLE_CLIENT_ID') }}">
@endsection
@section('content')
    <style>
        .modal {
            z-index: 1000000 !important;
        }
    </style>
     

    <div class="container py-1 mb-5">
        <div class="row mt-5 m-auto w-75 sidebar-container">
            <!--start said bar -->
            <div class="col-md-4 side-bar pb-5">
                <div class="card text-white side-bar">
                    <h4 class="card-header bg-dark side-bar ">Trip Summary</h4>
                    <hr class="mt-0">

                    <h5 class="card-title font-weight-bold ">Service Type</h5>

                    <div class="mb-2">
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

                    <h5 class="mt-2 card-title space-between-sections font-weight-bold">Miles</h5>
                    <div class="d-flex flex-wrap">
                        <span class="address-text mb-3">
                            {{ $data->mile == 0 ? 'N/A' : number_format($data->mile, 2, '.', ',') . ' Miles' }}
                        </span>
                    </div>



                    <h5 class="mt-2 card-title space-between-sections font-weight-bold">Trip Route</h5>
                    <div class="d-flex flex-wrap">
                        <span class="address-text mb-3">
                            From : {{ $data->pick_up_location }}
                        </span>
                    </div>

                    <div class="d-flex flex-wrap">
                        <span class="address-text">To: {{ $data->drop_off_location }}</span>
                    </div>


                    <h5 class="card-title space-between-sections font-weight-bold">Pickup Date & Time</h5>
                    <p class="card-text">{{ $data->pick_up_date->format('D, d/m/Y') }} -
                        {{ date('g:i A', strtotime($data->pick_up_time)) }}</p>
                    @if ($data->parent)
                        <h5 class="card-title mt-2 font-weight-bold">Return Time</h5>
                        <p class="card-text">{{ $data->parent->pick_up_date->format('D, d/m/Y') }} -
                            {{ date('g:i A', strtotime($data->parent->pick_up_time)) }}</p>
                    @endif
                    @if ($data->service_type == 2)
                        <h5 class="card-title mt-1 font-weight-bold">Duration</h5>
                        <p class="card-text">{{ $data->duration }} hours</p>
                    @endif
                    @if ($childSeatsFormatted->names != '' || $flightDetails != '')
                        <h5 class="card-title mt-1 font-weight-bold">Extra</h5>
                        @if ($childSeatsFormatted->names)
                            <p class="card-text">Child Seats: {{ $childSeatsFormatted->names }}</p>
                        @endif
                        @if ($flightDetails)
                            <p class="card-text mb-4">Flight Details: {{ $flightDetails }}</p>
                        @endif
                    @endif
                </div>
            </div>

            <!--end said bar -->
            <div class="col-md-8 add-margin-to-small-screen">
                <span class="pl-1 note-vat">All prices include VAT, fees, and toll.</span>

                <!-- card 1 -->
                @foreach ($fleetCategory as $key => $item)
                    <div class="card p-1 mb-3 shadow border border-dark-subtle">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center image-container">
                                <img src="{{ $item->image }}" alt="{{ $item->name }}" />
                                <div class="image-overlay"></div>
                            </div>
                            <div class="col-md-9">
                                <div class="card-body">
                                    <h4 class="fleet-title">{{ $item->name }}</h4>
                                    <div class="row g-0">
                                        <div class="col-8 fleet-specifications">
                                            <i class="fleet-icons bi bi-briefcase-fill"></i> Max.{{ $item->luggage }}
                                            <i class="fleet-icons bi bi-people-fill ml-1"></i>Max.{{ $item->passengers }}
                                        </div>
                                        {{-- <div class="col-2 fleet-specifications" > </div> --}}
                                    </div>


                                    <p class="card-text fleet-description">
                                        <span> {{ $item->types }},</span>
                                    </p>
                                    <div class="row">

                                        <div>
                                            <button class="custom-btn btn-5 btn btn-primary select-fleet-button22 w-100"
                                                onclick="getSelectedFleet('{{ $item->id }}','{{ $item->price }}')"
                                                class="btn btn-primary">${{ $item->price }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <!-- end cards -->

                <div class="card notes p-3">
                    <h6 class="note-vat">
                        All Categories Include:
                    </h6>

                    <ul class="list-group">
                        <li class="list-group-item d-flex">
                            <i class="bi bi-dash mr-2"></i> <span class="note-vat">Meet & Greet</span>
                        </li>
                        <li class="list-group-item d-flex">
                            <i class="bi bi-dash mr-2"></i> <span class="note-vat">Flight Tracking</span>
                        </li>
                        <li class="list-group-item d-flex">
                            <i class="bi bi-dash mr-2"></i> <span class="note-vat">Waiting time update: 60 minutes for
                                standard airport and courier services, 15 minutes for residentials. Your cooperation is
                                noted.</span>
                        </li>
                        <li class="list-group-item d-flex">
                            <i class="bi bi-dash mr-2"></i> <span class="note-vat">Guest/luggage capacities must be abided
                                by for safety reasons. If you are unsure, select a larger class as chauffeurs may turn down
                                service when they are exceeded.</span>
                        </li>
                        <li class="list-group-item d-flex">
                            <i class="bi bi-dash mr-2"></i> <span class="note-vat">The vehicle images above are examples.
                                You may get a different vehicle of similar quality.</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

    </div>

    <div class="modal" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header align-items-center">
                    <h5 class="modal-title" id="loginModalLabel">Login</h5>
                    <button onclick="closeModal()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Login form -->
                    <form>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter your email">
                        </div>
                        <div class="form-group mt-3">
                            <label for="password">Password</label>
                            <div class="input-group mb-3">
                                <input id="password" type="password" class="form-control" placeholder="Enter password" aria-label="password" aria-describedby="basic-addon1">
                                <div class="input-group-prepend">
                                  <span id="showPasswordIcon" class="input-group-text">
                                    <i id="eyeIcon" class="bi bi-eye"></i> 
                                  </span>
                                </div>
                              </div>
                              
                        </div>
                        <div class="text-center">
                            <button onclick="login()" type="button" class="btn btn-primary mt-4 max-width-login-button w-100">Login</button>

                            <h6 class="text-center continue-with-text">
                                Or continue with:
                                </h6>
                                <button type="button" onclick="openGoogleLoginPopup()" class="gsi-material-button w-100 align-middle text-center">
                                    <p class="text-soclie-login-button"> <img class="logo-soclie-login-button" src="https://cdn1.iconfinder.com/data/icons/google-s-logo/150/Google_Icons-09-512.png" alt=""> Google</p>
                                </button>                              
                        </div>
                    </form>
                    <p class="mt-3">Don't have an account? <a target="_blank"
                            href="{{ route('user.register') }}">Register here</a></p>
                </div>
            </div>
        </div>
    </div>



    </div>
    </div>

    <script>
        let reservation_id = '{{ $data->id }}';
        let isUserLoggedIn = '{{ auth()->check() }}';

        let global_id = 0;
        let global_price = 0;
        var passwordInput = document.getElementById('password');

        function getSelectedFleet(id, price, afterLoginRequest = false) {
           
            global_id = id;
            global_price = price;

            if (!isUserLoggedIn && !afterLoginRequest) {
                $('#loginModal').modal('show')
                return;
            }

            let request = '{{ route('reservations.update_fleet', ':id') }}';
            request = request.replace(':id', reservation_id);
            $.ajax({
                url: request,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    fleet_category_id: id,
                    price
                },
                success: (res) => {
                    let url = "{{ route('reservations.checkout') }}";
                    window.location.href = url;
                },
                error: (err) => {
                    console.log("error: ", err);
                }
            })
        }

        function login() {
            const data = {
                email: document.getElementById('email').value,
                password: passwordInput.value,
                _token: '{{ csrf_token() }}',
            }
            let request = "{{ route('reservations.login') }}";
            $.ajax({
                url: request,
                type: 'POST',
                data,
                success: (res) => {
                    getSelectedFleet(global_id, global_price, true)
                },
                error: (err) => {
                    alert('wrong credentials,please check your information.');
                }
            })
        }

        function onSuccess(googleUser) {
            var profile = googleUser.getBasicProfile();
            console.log('ID: ' + profile.getId());
            console.log('Name: ' + profile.getName());
            console.log('Email: ' + profile.getEmail());
            console.log('Image URL: ' + profile.getImageUrl());
        }

        function onFailure(error) {
            console.log('Error google: ',error);
        }

        function renderButton() {
            gapi.signin2.render('my-signin2', {
                'scope': 'profile email',
                'width': 240,
                'height': 50,
                'longtitle': true,
                'theme': 'dark',
                'onsuccess': onSuccess,
                'onfailure': onFailure
            });
        }

        function openGoogleLoginPopup() 
        {

            // Specify the URL for Google login
            var googleLoginUrl = "{{ route('google.google-auth') }}";
            

            var popup = window.open(googleLoginUrl, 'googleLoginPopup', 'width=600,height=600');

            window.addEventListener('message', function (event) {
                // Check if the message is from the expected origin (your site's domain)
                 if (event.origin === window.location.origin) {
                    // Check the message data to determine if it's a close request
                    getSelectedFleet(global_id, global_price, true)
                }
            });

            // Focus on the new window
            if (window.focus) {
                popup.focus();
            }
        }

        function closeModal()
        {
            $('#loginModal').modal('toggle')
        }
       

        showPasswordIcon.addEventListener('click', function() {
            var passwordContainer = document.getElementById('showPasswordIcon');
            
            if (passwordInput.type === 'password') 
            {
                passwordInput.type = 'text';
            } 
            else 
            {
                
                passwordInput.type = 'password';
            }
        });
        
    </script>
@endsection
