<div class="container">
    <div class="row">
        <div class="col-md-8 text-center mx-auto">
            <!-- SVG decoration START -->
            <img src="{{ $image }}" class="h-300px my-4" alt="">
            <!-- SVG decoration END -->
            
            <!-- Title -->
            <h1 class="fs-3">{{ $title }}</h1>

            <!-- description -->
            <p>{{ $description }}</p>

            <!-- Buttons -->
            <a href="{{ route('frontEnd.index') }}" class="btn btn-light mb-5">Back to Homepage</a>
        </div>
    </div>
</div>
