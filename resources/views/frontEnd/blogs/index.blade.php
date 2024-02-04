@extends('frontEnd.layouts.index')

@php

$pageTitle = isset($tagQuery) ? $tagQuery . ' - LavishRide Blog - Secure Your Safety' : 'LavishRide Blog - Secure Your Safety';
$route = Route::currentRouteName();
@endphp

@section('pageTitle')
<title>{{ isset($category) ? $category->seo_title : $pageTitle }}</title>   
@endsection

@section('seo')
    @if (isset($category))
        @php
            $categoryTitle = $category->title;
        @endphp
        <meta name="title" content="{{ $category->seo_title }}">
        <meta name="description" content="{{ $category->seo_description }}">
        <meta name="keywords" content="{{ $category->seo_keyphrase }}">
        <link rel="canonical" href="{{ route('frontEnd.blogs.category', ['slug' => $category->slug]) }}" />

        <meta property="og:title" content="{{ isset($category) ? $category->seo_title : $pageTitle }}">
        <meta property="og:type" content="blog" />
        <meta property="og:site_name" content="LavishRide Blog - Secure Your Safety" />
        <meta property="og:url" content="{{ route('frontEnd.blogs.category', ['slug' => $category->slug]) }}/" />
        <meta property="og:image" content="{{ asset('assets_new/img/LR-LogoSchema.png') }}" />
        <meta property="og:description" content="{{ $category->seo_description }}" />

        <meta name="twitter:card" content="summary" />
        <meta name="twitter:site" content="@LavishRide" />
        <meta name="twitter:title" content="LavishRide Blog | {{ isset($category) ? $category->seo_title : $pageTitle }}" />
        <meta name="twitter:description" content="{{ $category->seo_description }}" />
        <meta name="twitter:image" content="{{ asset('assets_new/img/LR-LogoSchema.png') }}" />
    @else
        @php
            $categoryTitle = isset($tagQuery) ? $tagQuery . ' - LavishRide Blog - Secure Your Safety' : 'LavishRide Blog - Secure Your Safety';
        @endphp

        <meta name="title"
            content="{{ isset($tagQuery) ? $tagQuery . ' - LavishRide Blog - Secure Your Safety' : 'LavishRide Blog - Secure Your Safety' }}">
        <meta name="description"
            content="Try LavishRide, your go-to platform for traveling in style. Get inspired with new destinations, stay on top of travel trends & learn from expert tips.">
        <meta name="keywords" content="Travel, Limousine and Black car, Business, Cities, Hotels, Airports">
        <link rel="canonical" href="{{ Request::url() }}" />

        <meta property="og:title"
            content="{{ isset($tagQuery) ? $tagQuery . ' - LavishRide Blog - Secure Your Safety' : 'LavishRide Blog - Secure Your Safety' }}">
        <meta property="og:type" content="blog" />
        <meta property="og:image" content="{{ asset('assets_new/img/LR-LogoSchema.png') }}" />
        <meta property="og:site_name" content="LavishRide Blog - Secure Your Safety" />
        <meta property="og:url" content="{{ Request::url() }}/" />
        <meta property="og:description"
            content="Try LavishRide, your go-to platform for traveling in style. Get inspired with new destinations, stay on top of travel trends & learn from expert tips.">

        <meta name="twitter:card" content="summary" />
        <meta name="twitter:site" content="@LavishRide" />
        <meta name="twitter:title" content="LavishRide Blog | {{ isset($category) ? $category->seo_title : $pageTitle }}" />
        <meta name="twitter:description"
            content="Try LavishRide, your go-to platform for traveling in style. Get inspired with new destinations, stay on top of travel trends & learn from expert tips." />
        <meta name="twitter:image" content="{{ asset('assets_new/img/LR-LogoSchema.png') }}" />
    @endif
@endsection

@section('content')

<!--Blog grid START -->
<section class="pt-0 pt-sm-5">
	<div class="container">

		<!-- Title -->
		<div class="row mb-4">
			<div class="col-12 text-center">
				<h2 class="mb-0">Latest Article</h2>
			</div>
		</div>
		<!-- Blog item Start -->
		<div class="row g-4">
			@foreach ($data as $item)
				<x-blog.blog :item="$item" />
			@endforeach
		</div>
			<!-- Blog item END -->
		<!-- Buttons -->
		<div class="d-flex justify-content-end">
			<div>
				<div class="text-end mt-5">
					{{ $data->links('pagination::bootstrap-4') }}
				</div>		
			</div>
		</div>
	</div>
</section>
<!--Blog grid END -->

<section class="pt-4 pt-md-5 mb-5">
	<div class="container">
		<!-- Title -->
		<div class="row mb-5">
			<div class="col-12 text-center">
				<h1 class="display-1 mb-0">Houston Blogs</h1>
			</div>
		</div>

		<!-- Blog START -->
		<div class="row g-4">

			<!-- Blog item START -->
			<div class="col-lg-6">
				<div class="card bg-transparent mb-4 mb-sm-0">
					<!-- Image -->
					<div class="position-relative">
						<img src="{{ asset('frontEnd/assets/images/blog/10.jpg') }}" class="card-img" alt="">
						<!-- Badge -->
						<div class="card-img-overlay d-flex align-items-start flex-column p-3">
							<a href="#" class="badge bg-dark">Adventure</a>
						</div>
					</div>	
					<!-- Card body -->
					<div class="card-body px-3 pb-0">
						<span><i class="bi bi-calendar2-plus me-2"></i>April 28, 2022</span>

						<!-- Title -->
						<h5 class="card-title"><a href="blog-detail.html">7 common mistakes everyone makes while traveling</a></h5>
						<p class="mb-0">Prospective agents should start broadly and then narrow their list down to.</p>

						<!-- Author name and button -->
						<div class="d-flex justify-content-between align-items-center mt-2">
							<h6 class="mb-0">By <a href="#">Joan Wallace</a></h6>
							<a href="blog-detail.html" class="btn btn-link p-0 mb-0">Read more <i class="bi bi-arrow-up-right"></i></a>
						</div>
					</div>
				</div>
			</div>
			<!-- Blog item END -->

			<!-- Blog list START -->
			<div class="col-lg-6 ps-lg-5">
				<div class="vstack gap-4">
					<!-- Blog item START -->
					<div class="card bg-transparent">
						<div class="row g-3 g-sm-4 align-items-sm-center">
							<!-- Image -->
							<div class="col-4">
								<img src="{{ asset('frontEnd/assets/images/blog/05.jpg') }}" class="card-img" alt="">
							</div>
							<div class="col-8">
								<!-- card body -->
								<div class="card-body p-0">
									<h5 class="card-title mb-sm-3"><a href="blog-detail.html" class="stretched-link">Bad habits that people in the business industry need to quit</a></h5>
									<!-- Author name and button -->
									<div class="d-flex justify-content-between align-items-center">
										<span class="small"><i class="bi bi-calendar2-plus me-2"></i>Sep 01, 2022</span>
										<a href="blog-detail.html" class="btn btn-link p-0 mb-0">Read more <i class="bi bi-arrow-up-right"></i></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Blog item END -->

					<hr class="my-0"> <!-- Divider -->

					<!-- Blog item START -->
					<div class="card bg-transparent">
						<div class="row g-3 g-sm-4 align-items-sm-center">
							<!-- Image -->
							<div class="col-4">
								<img src="{{ asset('frontEnd/assets/images/blog/06.jpg') }}" class="card-img" alt="">
							</div>
							<div class="col-8">
								<!-- card body -->
								<div class="card-body p-0">
									<h5 class="card-title mb-sm-3"><a href="blog-detail.html" class="stretched-link">Around the web: 20 fabulous info graphics about business</a></h5>
									<!-- Author name and button -->
									<div class="d-flex justify-content-between align-items-center">
										<span class="small"><i class="bi bi-calendar2-plus me-2"></i>Sep 15, 2022</span>
										<a href="blog-detail.html" class="btn btn-link p-0 mb-0">Read more <i class="bi bi-arrow-up-right"></i></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Blog item END -->

					<hr class="my-0"> <!-- Divider -->

					<!-- Blog item START -->
					<div class="card bg-transparent">
						<div class="row g-3 g-sm-4 align-items-sm-center">
							<!-- Image -->
							<div class="col-4">
								<img src="{{ asset('frontEnd/assets/images/blog/08.jpg') }}" class="card-img" alt="">
							</div>
							<div class="col-8">
								<!-- card body -->
								<div class="card-body p-0">
									<h5 class="card-title mb-sm-3"><a href="blog-detail.html" class="stretched-link">Ten unconventional tips about startups that you can't learn from books</a></h5>
									<!-- Author name and button -->
									<div class="d-flex justify-content-between align-items-center">
										<span class="small"><i class="bi bi-calendar2-plus me-2"></i>Sep 28, 2022</span>
										<a href="blog-detail.html" class="btn btn-link p-0 mb-0">Read more <i class="bi bi-arrow-up-right"></i></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Blog item END -->
				</div>
			</div>
			<!-- Blog list END -->

		</div>
		<!-- Blog END -->
	</div>
</section>
@endsection