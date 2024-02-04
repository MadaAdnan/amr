@extends('frontEnd.layouts.index')


@section('title')
	{{ isset($data->seo_title) ? $data->seo_title : '' }}
@endsection


@section('seo')
    <meta name="title" content="{{ $data->seo_title }}">
    <meta name="description" content="{{ $data->seo_description }}">
    <meta name="keywords" content="{{ implode(',',$data->keywords()->pluck('title')->toArray()) }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('frontEnd.blogs.details', $data->slug) }}" />

    <meta property="og:title" content="{{ $data->seo_title }}" />
    <meta property="og:description" content="{{ $data->summary }}" />
    <meta property="og:image" content="{{ $data->avatar }}" />
    <meta property="og:type" content="blog" />
    <meta property="og:site_name" content="LavishRide Blog - Secure Your Safety" />
    <meta property="og:url" content="{{ route('frontEnd.blogs.details', $data->slug) }}/" />

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@LavishRide" />
    <meta name="twitter:title" content="LavishRide Blog | {{ $data->seo_title }}" />
    <meta name="twitter:description" content="{{ $data->summary }}" />
    <meta name="twitter:image" content="{{ $data->avatar }}" />
@endsection


@section('content')

{{-- Share Buttons --}}
<x-general.sticky-social-bar />

{{-- Blog Header Section --}}
<section class="py-0">
	<div class="container">
		<!-- Title -->
		<div class="row g-4">
			<!-- Image -->
			<div class="col-12 d-flex justify-content-center w-100">
				<img onerror="this.src='{{ asset('FrontEnd/assets/images/no_image.avif') }}';" src="{{ $data->avatar }}" class="rounded-3" alt="">
			</div>
			<!-- Title and content -->
			<div class="col-11 col-lg-10 mx-auto position-relative mt-n5 mt-sm-n7 mt-md-n8">
				<div class="bg-mode shadow rounded-3 p-4">
					<!-- Badge -->
                    @foreach ($data->categories as $item)
					    <div class="badge text-bg-success mb-2">{{ $item->title }}</div>   
                    @endforeach
					<!-- Title -->
					<h1 class="fs-3">{{ $data->title }}</h1>
					
					<!-- List -->
					<ul class="nav nav-divider align-items-center">
						<li class="nav-item">
							<div class="nav-link">
								<div class="d-flex align-items-center">
									<!-- Avatar -->
									<div class="avatar avatar-sm">
										<img class="avatar-img rounded-circle object-fit-contain" src="{{ asset('images/lavishLogo.png') }}" alt="avatar">
									</div>
									<!-- Info -->
									<div class="ms-2">
										<h6 class="mb-0"><a href="#">Lavish Ride</a></h6>
									</div>
								</div>
							</div>
						</li>
						<li class="nav-item">{{ $data->date->format("M d, Y") }}</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>

{{-- Blog Content --}}
<section>
	<div class="container">
		<div class="row">

			<div class="col-lg-10 mx-auto">
                <!-- Content -->
                {!!  $data->content !!}
                
				<!-- Author info -->
                <x-blog.about-the-company-card />

				<!-- Social links and tags -->
				<div class="d-lg-flex justify-content-lg-between mt-4">
					<!-- Social media button -->
					<div class="align-items-center mb-3 mb-lg-0">
						<h6 class="d-inline-block mb-2 me-4">Share on:</h6>
						<ul class="list-inline hstack flex-wrap gap-3 h6 fw-normal mb-0">
							<li class="list-inline-item"> <a class="text-facebook" href="#"><i class="fa-brands fa-facebook-square"></i> Facebook</a> </li>
							<li class="list-inline-item"> <a class="text-instagram-gradient" href="#"><i class="fa-brands fa-instagram-square"></i> Instagram</a> </li>
							<li class="list-inline-item"> <a class="text-twitter" href="#"><i class="fa-brands fa-twitter-square"></i> Twitter</a> </li>
						</ul>
					</div>

					<!-- Popular tags -->
					<div class="align-items-center">
						<h6 class="d-inline-block mb-2 me-4">Categories</h6>
						<ul class="list-inline mb-0">
                            @foreach ($data->categories as $item)
							    <li class="list-inline-item"> <a class="btn btn-light btn-sm mb-xl-0" href="#">{{ $item->title }}</a> </li>                                
                            @endforeach
						</ul>
					</div>
				</div>
				<!-- Social links and END -->	
			</div>	
		</div>
	</div>
</section>

@endsection