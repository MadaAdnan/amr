<div class="col-md-6 col-xl-6">
    <div class="card card-hover-shadow pb-0 h-100">
        <!-- Overlay item -->
        <div class="position-relative">
            <!-- Image -->
            <img alt="{{ $item->image_alt }}" onerror="this.src='{{ asset('FrontEnd/assets/images/no_image.avif') }}';" src="{{ $item->avatar }}" class="card-img-top" alt="Card image">
            <!-- Overlay -->
            <div class="card-img-overlay d-flex flex-column p-4 z-index-1">
                <!-- Card overlay top -->
                <div>
                    <span class="badge text-bg-dark">Support Flight Tracking <i class="fa-solid fa-{{ $item->flight_tracking == 1 ? 'check' : 'xmark' }}"></i></span>
                </div>
            
            </div>
        </div>
        <!-- Image -->

        <!-- Card body START -->
        <div class="card-body px-3">
            <!-- Title -->
            <h5 class="card-title mb-0"><a href="{{ route('frontEnd.fleets.details',$item->slug) }}" class="stretched-link">{{ $item->title }}</a></h5>
            <span class="small max__char">
                <p>
                    {{ $item->category_description }}
                </p>
            </span>

            <!-- List -->
            <ul class="nav nav-divider mt-3 mb-0">
                <li class="nav-item h6 fw-normal mb-0">
                    <i class="fa-solid fa-person text-primary me-2"></i>{{ $item->passengers }} Passengers
                </li>
                <li class="nav-item h6 fw-normal mb-0">
                    <i class="fa-solid fa-person-walking-luggage text-primary me-2"></i>{{ $item->luggage }} Luggages
                </li>
            </ul>
        </div>
        <!-- Card body END -->

        <!-- Card footer START-->
        <div class="card-footer pt-0 d-flex justify-content-end">
            <!-- Price and Button -->
            <div class="d-sm-flex justify-content-sm-between align-items-end flex-wrap">
                
                <!-- Button -->
                <div class="mt-2 mt-sm-0 ">
                    <a href="{{ route('frontEnd.fleets.details',$item->slug) }}" class="btn btn-sm btn-primary mb-0">View Details</a>    
                </div>
            </div>
        </div>

    </div>
</div>