<div class="bg-mode border rounded p-4 mt-5">
    <!-- Avatar and info -->
    
    <h5 class="text-center">
        {{ env('APP_NAME') }}
    </h5>

    <!-- Content -->
    <p class="my-3">{{ config('general.about_the_company') }}</p>

    <!-- Buttons -->
    <div class="d-flex align-items-center justify-content-between">
        <!-- Social icons -->
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link ps-0 pe-2 fs-5" target="_blank" href="{{ config('general.social_media')['facebook'] }}"><i class="bi bi-facebook"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-2 fs-5" target="_blank" href="{{ config('general.social_media')['twitter'] }}"><i class="bi bi-twitter"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-2 fs-5" target="_blank" href="{{ config('general.social_media')['linkedin'] }}"><i class="fa-brands fa-linkedin-in"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-2 fs-5" target="_blank" href="{{ config('general.social_media')['instagram'] }}"><i class="fa-brands fa-instagram"></i></a>
            </li>
        </ul>					
        <!-- Button -->
        <a href="{{ route('frontEnd.reservations.choose_location') }}" class="btn btn-sm btn-primary mb-0">Book Now</a>
    </div>
</div>