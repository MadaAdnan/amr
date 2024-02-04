<!DOCTYPE html>
<html lang="en">
<head>
	<title>@yield('title')</title>

	<!-- Meta Tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Webestica.com">
	<meta name="description" content="Booking - Multipurpose Online Booking Theme">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('seo')

	<!-- Favicon -->
	<link rel="shortcut icon" href="assets/images/favicon.ico">

	<!-- Google Font -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Poppins:wght@400;500;700&display=swap">

	<!-- Plugins CSS -->
	<link rel="stylesheet" type="text/css" href="{{ asset('frontEnd/assets/vendor/font-awesome/css/all.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontEnd/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">

	<!-- Theme CSS -->
	<link rel="stylesheet" type="text/css" href="{{ asset('frontEnd/assets/css/style.css') }}">

	<!-- Custom CSS -->
	<link rel="stylesheet" type="text/css" href="{{ asset('FrontEnd/assets/css/custom.css') }}">


	@yield('style')

</head>

<body>

<!-- **************** MAIN CONTENT START **************** -->
<main>
	
<!--Main Content START -->
@yield('content')
<!--Main Content END -->

</main>
<!-- **************** MAIN CONTENT END **************** -->

<!-- Back to top -->
<div class="back-top"></div>

<!-- Bootstrap JS -->
<script src="{{ asset('frontEnd/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

<!-- ThemeFunctions -->
<script src="{{ asset('frontEnd/assets/js/functions.js') }}"></script>

<!-- Custom -->
<script src="{{ asset('FrontEnd/assets/js/custom.js') }}"></script>


@yield('script')

</body>
</html>