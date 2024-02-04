<div class="col-lg-10 mx-auto">
    <h5 class="my-4">{{ $title }}</h5> 
    <div class="row g-4">

        {{-- if need to render the image on the left  --}}
        @if ($imagePlace == 'left')
            <div class="col-md-6 col-lg-5">
                <img src="{{ $image }}" class="rounded" alt="">
            </div>
        @endif
        <!-- List -->
        <div class="col-md-6 col-lg-7">
            {!! $description !!}
        </div>
        @if ($imagePlace == 'right')
            <div class="col-md-6 col-lg-5">
                <img src="{{ $image }}" class="rounded" alt="">
            </div>
        @endif
    </div>