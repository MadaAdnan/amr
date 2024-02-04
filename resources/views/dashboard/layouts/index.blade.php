<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} - {{ auth()->user()->full_name }} Dashboard</title>
    
    <link rel="stylesheet" href="{{ asset("dashboard/assets/css/bootstrap.css") }}">
    <link rel="stylesheet" href="{{ asset("dashboard/assets/vendors/perfect-scrollbar/perfect-scrollbar.css") }}">
    <link rel="stylesheet" href="{{ asset("dashboard/assets/css/app.css") }}">
    <link rel="shortcut icon" href="{{asset('favIcon.png')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css">
    <link href="
    https://cdn.jsdelivr.net/npm/js-datepicker@5.18.2/dist/datepicker.min.css
    " rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.2.1/css/intlTelInput.css" integrity="sha512-s51TDsIcMqlh1YdQbF3Vj4StcU/5s97VyLEEpkPWwP0CJfjZ/C5zAaHnG+DZroGDn1UagQezDEy61jP4yoi4vQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <img href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.2.1/img/flags.png'/>
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/custom.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex,nofollow">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.css" integrity="sha512-E4kKreeYBpruCG4YNe4A/jIj3ZoPdpWhWgj9qwrr19ui84pU5gvNafQZKyghqpFIHHE4ELK7L9bqAv7wfIXULQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    

</head>
<body>
    <div id="app">
        <div id="sidebar" class='active'>
            <div class="sidebar-wrapper active">
    <div class="sidebar-header text-center">
        <a href="{{ route('dashboard.home') }}">
            <img class="logo-dashboard" src="{{ asset('img/logo2.png') }}" alt="" srcset="">
        </a>
    </div>
    @include('dashboard.layouts.sidebar')
    <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
</div>
        </div>
        <div id="main">
            <nav class="navbar navbar-header navbar-expand navbar-light">
                <a class="sidebar-toggler" href="#"><span class="navbar-toggler-icon"></span></a>
                <button class="btn navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav d-flex align-items-center navbar-light ml-auto">
                        <li class="dropdown nav-icon">
                            
                           
                        </li>
                        
                        <li class="dropdown">
                            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                                <div class="avatar mr-1">
                                    <img src="{{ asset('dashboard/assets/images/user_defualt.png') }}" alt="" srcset="">
                                </div>
                                <div class="d-none d-md-block d-lg-inline-block">Hi, {{ auth()->user()->full_name }}</div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route("dashboard.admins.update_profile") }}"><i data-feather="settings"></i> Update Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('dashboard.logout') }}"><i data-feather="log-out"></i> Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            
    <div class="main-content container-fluid">
        @yield('content')
    </div>
            
        </div>
    </div>
    @include('sweetalert::alert')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="{{ asset('dashboard/assets/js/feather-icons/feather.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="{{ asset('dashboard/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/app.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/main.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('dashboard/ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Cairo' rel='stylesheet'>
    <link href="https://fonts.cdnfonts.com/css/roboto" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/zxcvbn/dist/zxcvbn.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.2.1/js/intlTelInput.min.js" integrity="sha512-uZZS8rsETXJQX6Jy57Zdc7PAmP83GCjC1F/LX0xUj12wY5SlfUX+CVnYFEX89doutQPeFbI9FjUCkpuTWqlBwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.2.1/js/intlTelInput-jquery.js" integrity="sha512-FhdxuQlB84ZIFiX/84s61yflIV6UOHBY2cCal6yrUjqq8ogAhmp3NwWYRG1t1mnrUabFNGx29ShlYub9AUchSw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.2.1/js/intlTelInput-jquery.min.js" integrity="sha512-sVhsc+r7sEickzS6LohO+VDVv2ler/3QY7op8ScWV8KVLLq+m1WAl6uplr/YHmqI0L0j99ehNRh2cIwn7zXcdg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.2.1/js/intlTelInput.js" integrity="sha512-xi5w2NwusrPUdyIeJqXcFCPIHSzt3Gilx81G7gMp3AxcQ1nlIEPU2rJWbLHdQ2ooiYoZ6Eua48EEJM1kxXQ53A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.2.1/js/utils.js" integrity="sha512-3ounU7oKeL95+I30ZnBb6bWAYS4RtXCW7kBtp8/JOqXLdhJNLCDkehi9e0O8cwjWBxR6HLCvAtDKslAcliunSQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.2.1/js/utils.min.js" integrity="sha512-viyGJzhGVZqq0awVdFrcUjKyAtoYoxXzAZBBkMd1E19TkkdpMM+UpfgF+yaCst2D4Vsz4dMMW1wi2wyvU79BoQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src ="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js" integrity="sha512-42PE0rd+wZ2hNXftlM78BSehIGzezNeQuzihiBCvUEB3CVxHvsShF86wBWwQORNxNINlBPuq7rG4WWhNiTVHFg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src ="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>  
    <script src="https://cdn.socket.io/4.6.0/socket.io.min.js" integrity="sha384-c79GN5VsunZvi+Q/WObgk2in0CbZsHnjEqvFxC5DxHn9lTfNce2WW6h2pH6u/kF+" crossorigin="anonymous"></script>
    {{-- for testing --}}
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&libraries=places"></script> --}}

    {{-- <script src="{{ asset('js/dashboard/ReservationManagerDashboard.js') }}"></script> --}}
    @php

   $user_data = (object)Session::get('user_data');
    @endphp

    <script>
        const Toast = Swal.mixin({
        toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })


        // const createReservation = new ReservationManagerDashboard();

        // async function checkLocation() {
        //     try 
        //     {
        //         const locations = await createReservation.getLocation('dropOff', 'new york');
        //         if (!locations || locations.length === 0) {
        //             alert('no Data');
        //         }
        //     } 
        //     catch (error) 
        //     {
        //         console.error('An error occurred in checking the location:', error.message);
        //     }
        // }
        // checkLocation();
    </script>


    @yield('scripts')
</body>
</html>


