<div class="col-md-6 col-lg-4">
    <div class="card bg-transparent">
        <!-- Image -->
        <div class="position-relative">
            <img onerror="this.src='{{ asset('FrontEnd/assets/images/no_image.avif') }}';" alt="{{ $item->title }}"  src="{{ $item->avatar }}" class="card-img">
            <!-- Badge -->
            <div class="card-img-overlay p-3">
                @if ($item->categories)
                    @foreach ($item->categories as $category)
                        <a href="#" class="badge text-bg-warning mb-2">{{ $category->title }}</a>									
                    @endforeach									
                @endif
            </div>
        </div>

        <!-- Card body -->
        <div class="card-body p-3 pb-0">
            <!-- Title -->
            <h5 class="card-title mt-2"><a href="{{ route('frontEnd.blogs.details',$item->slug) }}">{{ $item->title }}</a></h5>
            <h6 class="fw-light mb-0">By <a href="#">Lavish Ride Team</a></h6>
        </div>
    </div>
</div>