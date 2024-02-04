@extends('layouts.app')
@section('content')
    <style>
        .banner-section{
            padding: 2.1% 0 !important;
        }

        .banner-section::before{
            /*background-color: #0b0b0b;*/
        }
    </style>
    <!-- about-area -->
    <section class="white-section section-block">
        <div class="container text-center">
            <div class="margin-top-100 margin-bottom-100">
                <img src="{{asset('favIcon.png')}}" style="width: 150px; height: auto">
                <div class="margin-top-20 margin-bottom-40">
                    <h1 class="font-size-42 font-weight-800">@yield('message')</h1>
                </div>
                <div class="margin-top-25 margin-bottom-40">
                    <p class="text-20" style="color: rgb(255, 68, 6);">Your Email Is not Confirmed Successfully</p>
                </div>
                <div class="margin-top-25">
                    <a title="Back to Home" href="{{route('frontEnd.index')}}" class="button main ripple-effect button-sliding-icon"  data-animation="fadeInUp" data-delay=".6s">
                        Go Back Home <i class="fa fa-angle-right"></i>
                    </a>
                </div>

            </div>
        </div>
    </section>



@endsection
