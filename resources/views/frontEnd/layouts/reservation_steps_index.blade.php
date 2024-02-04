<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('pageTitle')</title>
    @yield('seo')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
    <link rel="stylesheet" href="{{ asset('FrontEnd/css/main.min.css?v=9') }}" />
    <link rel="stylesheet" href="{{ asset('FrontEnd/css/custom.css?v=11') }}" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker3.min.css"
        integrity="sha512-aQb0/doxDGrw/OC7drNaJQkIKFu6eSWnVMAwPN64p6sZKeJ4QCDYL42Rumw2ZtL8DB9f66q4CnLIUnAw28dEbg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('FrontEnd/main.css?v=12') }}">

    <style>
        #flight_number::-webkit-outer-spin-button,
        #flight_number::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }

        div:where(.swal2-container) div:where(.swal2-loader) {
            border-color: #ae2227 rgba(0, 0, 0, 0) #c42727 rgba(0, 0, 0, 0) !important;
        }
    </style>
    <script>
        function clearAllFormElements() {
            const forms = document.forms;
            for (let i = 0; i < forms.length; i++) {
                const elements = forms[i].elements;
                for (let j = 0; j < elements.length; j++) {
                    const element = elements[j];
                    const type = element.type;
                    if (type === 'text' || type === 'textarea' || type === 'password' || type === 'file' || type ===
                        'date' || type === 'time') {
                        element.value =
                            ''; // Clear text fields, textareas, password fields, file inputs, date, and time inputs
                    } else if (type === 'radio' || type === 'checkbox') {
                        element.checked = false; // Uncheck radio buttons and checkboxes
                    } else if (type === 'select-one' || type === 'select-multiple') {
                        // Reset select elements by choosing the first option
                        element.selectedIndex = 0;
                    }
                }
            }
        }







        /** refresh the page page when the user click back **/
        window.addEventListener('pageshow', function(event) {
            // Check if the page is being displayed from the bfcache
            if (event.persisted) {
                // Page is being shown from the bfcache, so clear form elements
                // clearAllFormElements();
            }
        });
    </script>


    @if (env('APP_URL') == 'https://lavishride.com')
        <script type="application/ld+json">
    {
        "url":"https://lavishride.com",
        "logo":"https://lavishride.com/assets_new/img/LR-LogoSchema.png",
        "name":"Lavishride",
        "@type":"Organization",
        "sameAs":[
            "https://www.facebook.com/LavishrideUS",
            "https://twitter.com/lavishride?s=21&t=te5wTudhQ7CKuDgP9glMqA",
            "https://www.instagram.com/lavish_ride/",
            "https://www.linkedin.com/company/lavish-ride-us/"
        ],
        "address":{
        "@type":"PostalAddress",
        "postalCode":"77024",
        "streetAddress":"333 West Loop North",
        "addressCountry":"United States of America",
        "addressLocality":"Houston, TX"
        },
        "@context":"http://schema.org",
        "description":"LaviahRide, Through her blog, tries to cover the most important topics for world travelers."
    }
    
        </script>
        {{-- <script async src="https://www.googletagmanager.com/gtag/js?id=AW-477815823"></script>
        <script>
            gtag('config', 'AW-477815823');
        </script> --}}


        {{-- ------------------------------ new ------------------------------------ --}}

        <!-- Google Tag Manager -->
        <script>
            (function(w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start': new Date().getTime(),
                    event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', 'GTM-MGW3TLFW');
        </script>
        <!-- End Google Tag Manager -->
    @endif

    {{-- @if (env('APP_URL') == 'https://lavishride.com')
        <script async src="https://www.googletagmanager.com/gtag/js?id=AW-477815823"></script>
        <script>
            gtag('config', 'AW-477815823');
        </script>
    @endif --}}
    <!-- Google tag (gtag.js) -->
    {{-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-7NQ06XN8LS"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-7NQ06XN8LS');
    </script> --}}





</head>

<style>
    .timeline {
        margin: 0 auto;
        justify-content: center;
        align-items: center;
        position: relative;
        justify-content: flex-end;
    }

    .timeline-item {
        flex: 0 1 23%;
        text-align: center;
        position: relative;
        margin-right: 0px;
    }

    .dot-container {
        display: flex;
        /* flex-direction: column; */
        /* align-items: center; */
        position: relative;
    }

    .dot {
        width: 18px;
        height: 18px;
        background-color: #ffff;
        border-radius: 50%;
        margin-bottom: 10px;
        border: solid;
        border-width: thin;
        z-index: 99999;
    }

    .active-dot {
        background-color: #a42328;
        border: none;
    }

    .line {
        width: 100%;
        height: 2px;
        background-color: #a42328;
        /* Line color */
        position: absolute;
        top: 9px;
        left: 0;
    }

    /* .line:last-child {
    background-color: red !important;
} */

    .line:first-child {
        background-color: transparent;
        /* Remove line before the first dot */
    }

    .line:last-child {
        background-color: transparent;
        /* Remove line after the last dot */
    }

    .text {
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .timeline {
            flex-direction: column;
        }
    }

    .active-line {
        background-color: #a42328 !important;
    }

    .unactive-line {
        background-color: gray !important;
    }


    .start-trip-text {
        margin-left: -22px;
    }

    .start-trip-text {
        margin-left: -134px;
    }
</style>

@php
    $routeName = request()
        ->route()
        ->getName();
@endphp

<body>
    <header class="navbar navbar-expand-lg bg-light shadow-sm">
        <div class="container">
            @if (
                $routeName == 'reservations.choose_location' ||
                    $routeName == 'reservations.select_fleet' ||
                    $routeName == 'reservations.checkout')
                <nav id="navbarCollapse1" class="collapse navbar-collapse row">
                    <div class="row d-flex justify-content-center align-items-center text-center">
                        <div class="col-2 text-left">
                            <div class="d-inline-block">
                                <a href="/">
                                    <img class="logoReservation"
                                        src="{{ asset('assets_new/img/lavishride_original_logo.webp') }}" width="90"
                                        alt="" />
                                </a>
                            </div>
                        </div>
                        <div class="col-8 pt-4">
                            <div class="d-inline-block time-line-section">


                                <div class="justify-center justify-content-center align-items-center">
                                    <div class="timeline m-auto">
                                        <div class="timeline-item">
                                            <div class="dot-container first-dot-container">
                                                <div
                                                    class="dot first-dot {{ $routeName == 'reservations.choose_location' ? 'active-dot' : '' }}">
                                                </div>
                                                <div
                                                    class="line {{ $routeName == 'reservations.select_fleet' || $routeName == 'reservations.checkout' ? 'active-line' : 'unactive-line' }}">
                                                </div>
                                                <!-- Line connecting the first item to the dot of the second item -->
                                            </div>
                                            <div class="start-trip-text">Trip Details</div>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="dot-container">
                                                <div
                                                    class="dot {{ $routeName == 'reservations.select_fleet' ? 'active-dot' : '' }}">
                                                </div>
                                                <div
                                                    class="line {{ $routeName == 'reservations.checkout' ? 'active-line' : 'unactive-line' }} ">
                                                </div>
                                            </div>
                                            <div class="start-trip-text">Select a Vehicle</div>

                                        </div>
                                        <div class="timeline-item">
                                            <div class="dot-container last-dot-container">
                                                <div
                                                    class="dot {{ $routeName == 'reservations.checkout' ? 'active-dot' : '' }}">
                                                </div>
                                            </div>
                                            <div class="start-trip-text">Checkout</div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="col-2 text-center">
                            <div class="d-inline-block">
                                <a href="/">
                                    <i class="bi bi-text-indent-left h4 icon-timeline timeline-dot"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </nav>
        </div>
    </header>
    @endif


    @yield('content')
    @if (env('APP_URL') == 'https://lavishride.com')

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MGW3TLFW" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
        @endif
</body>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@yield('scripts')

</html>
