<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Lavish Ride Authentication</title>
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/bootstrap.css') }}">
    
    <link rel="shortcut icon" href="{{ asset('favIcon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/custom.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <meta name="description" content="Authenticate your Lavish Ride account to access secure features and personalized services.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('dashboard.login') }}" />

</head>

<body>
    <div id="auth">
        
<div class="container">
   @yield('main')
</div>

    </div>
    @include('sweetalert::alert')

    <script src="{{ asset('dashboard/assets/js/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/app.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/main.js') }}"></script>

    @yield('scripts')



</body>

</html>
