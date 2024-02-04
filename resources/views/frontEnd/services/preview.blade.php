@extends('layouts.app')
@section('page_name')
<style>
     .display-4 {
            font-size: 16px !important;
        }
        .back-buuton {
            background-color: #ad2227;
            border-color: red;
            padding: 5px;
            font-size: 25px;
            border-radius: 5px;
            color: #ffff;
        }
</style>
    <a title="{{$data->slug}}" href="{{ route('dashboard.services.edit',$data->id) }}" class="btn btn-success back-buuton mb-2">Back to edit</a><br/>
    <br/>
    <h1 class="text-white">{{ $data->title }}</h1>
    <p class="display-4 margin-top-25 text-white">{{ $data->description }}</p>
@endsection

@section('seo')
<meta name="title" content="{{ $data->seo_title }}">
<meta name="description" content="{{ $data->seo_description }}">
<link rel="canonical"    href="{{ route('frontEnd.services.details',$data->slug) }}">
<meta name="keywords" content="{{ $data->seo_key_phrase }}">

@endsection

@section('pageTitle')<title>{{ $data->title }}</title>@endsection

@section('content')

    <style>
        .text-light {
            color: #ffff !important;
        }
        .section-title{
            font-weight: bold !important;
        }
        h3.section-title {
            font-size: 22px !important;
        }
       
    </style>

    <!-- about-area -->
    <div class="service-page cp-md-margin-top-80 cp-margin-top-20">
        <section class="white-section section-block">
            <div class="container">
                <div class="service-content">
                    @foreach ($data->sections()->orderBy('sort_number','asc')->get() as $item)
                        
                        <div class="row {{ $item->is_left?'flex-row-reverse':'' }} cp-md-margin-bottom-80 cp-margin-bottom-20">
                                <div class="col-md-6 col-12 wow fadeInLeft animated" data-wow-duration=".5s" data-wow-delay=".3s">
                                    <div class="about-img">
                                        <img src="{{ $item->paragraph_image_url ?? $item->thumbnail }}" class="img-fluid" alt="{{ $item->alt }}" cap loading="lazy">
                                        @if ($item->caption)
                                            <figcaption>{{ $item->caption }}</figcaption>
                                        @endif
                                    </div>
                                </div>                                
                            <div class="col-md-6 col-12 cp-margin-top-15">
                                <div class="content-area">
                                    <div class="padding-bottom-15 border-bottom-main-1">
                                        <h2 class="section-title">{{ $item->title }}</h2>
                                    </div>
                                    <div class="margin-top-25">
                                        {!! $item->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                      
                    @endforeach

                   

                    
                </div>
            </div>
        </section>
        

        <section class="light-section padding">
            <div class="container">
                <div class="p-row grid grid-cols-12 row-gap-2">
                    <div class="col-span-12 lg:col-span-12 mb-5">
                        <div class="">
                            <h2 class="text-2xl text-center section-title">Plan Trips in Three Steps</h2>
                            <div class="d-flex justify-content-center">
                            </div>
                        </div>

                        <div class="mt-5 text-center ">
                            <p class="margin-0">You can book your ride in under one minute by using our easy-to-use booking system.</p>
                    </div>

                    <div class="col-span-12 margin-top-50 text-center">
                        <div class="grid-cols trip-plan d-flex justify-content-center">
                            <div class="col">
                                <div class="service-step has-arrow">
                                    {!! file_get_contents(public_path('svgIcons/note-line-icon.svg')) !!}
                                    <p class="text-center font-medium">Fill In Your Information</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="service-step has-arrow">
                                    {!! file_get_contents(public_path('svgIcons/suv-car-icon.svg')) !!}
                                    <p class="text-center font-medium">Choose Your Vehicle of Choice</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="service-step">
                                    {!! file_get_contents(public_path('svgIcons/comfortable-comfort-icon.svg')) !!}
                                    <p class="text-center font-medium">Enjoy Your Ride</p>
                                </div>
                            </div>
                           
                        </div>
                        {{-- <div class="col-span-12 lg:col-span-12 margin-top-5 margin-bottom-25">
                            <h4 class="text-2xl font-weight-600 text-center"> IT’S TIME TO TAKE A LAVISH RIDE! </h4>
                        </div>
                        <div class="col-span-12 lg:col-span-12">
                            <img src="/img/featured-1.webp" data-src="/src/assets/images/pages/featured-1.webp" alt="lavish ride fleet" class="m-auto" lazy="loaded">
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="big-space"></div>
        </section>

        <section style="background-image:url({{ asset('why_chose_us.jpg') }})" class="paralax clearfix cp-md-padding-bottom-55 cp-md-padding-top-55">
            <div class="container">
                <h2 class="section-intro text-white text-40 cp-md-margin-bottom-60 cp-margin-bottom-20" >Why Choose Us?</h2>
                <p class="text-white text-16">
                    Our outstanding commitment to achieving great luxury transportation service is summed up through prioritizing your satisfaction and going above and beyond to ensure that every aspect of your limo experience exceeds your expectations.

                </p>
                <ul class="service-list d-flex justify-content-center row">
                    <li class="service-detail col-4">
                        <img class="service-icon" src="{{asset('assets_new/img/post/post2.jpg')}}" loading="lazy" />
                        <h3 class="section-title">Professional Chauffeurs</h3>
                        <p class="text-light">We employ chauffeurs with extensive driving experience and excellent knowledge of the cities ensuring they are always neatly dressed, polite, and punctual.</p>
                    </li>
                    <li class="service-detail col-4">
                        <img class="service-icon" src="{{asset('assets_new/img/post/post2.jpg')}}" loading="lazy"/>
                        <h3 class="section-title">24 Hours Availability</h3>
                        <p class="text-light">Our Team is available every daytime of the week at all times to provide the services you need, ensure your trip is going according to plan, and give you the experience you deserve.</p>
                    </li>
                    <li class="service-detail col-4">
                        <img class="service-icon" src="{{asset('assets_new/img/post/post2.jpg')}}" loading="lazy"/>
                        <h3 class="section-title">Safety</h3>
                        <p class="text-light">No only our vehicles undergo regular maintenance, but also our background checked chauffeurs follow safe driving practices and regularly undergo safety training to make them equipped to handle any situation that may arise to ensure your safety through your journey with Lavish Ride.</p>
                    </li>
                    <li class="service-detail col-4">
                        <img class="service-icon" src="{{asset('assets_new/img/post/post2.jpg')}}" loading="lazy"/>
                        <h3 class="section-title">Trustworthy</h3>
                        <p class="text-light">Our team is composed of experienced and dedicated professionals who are committed to delivering exceptional service with integrity and transparency, by taking the responsibility of clients satisfaction and strive to earn and maintain your trust at every interaction.</p>
                    </li>
                 
                </ul> <!-- end service list -->
            </div> <!-- end container -->
        </section>
    </div>
@endsection