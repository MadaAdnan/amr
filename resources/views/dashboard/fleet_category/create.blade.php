@extends('dashboard.layouts.index')


@section('content')
    <style>
        input#flexSwitchCheckDefault {
            width: 50px;
            height: 25px;
        }

        #previewImage {
            height: 300px;
            object-fit: cover;
        }
    </style>
    <div class="card p-3">
        @if ($errors->any())
            <div class="alert alert-danger p-5" role="alert">
                @foreach ($errors->all() as $error)
                    <li> {{ $error }}</li>
                @endforeach
            </div>
        @endif
        <form enctype="multipart/form-data" action="{{ route('dashboard.fleetCategories.store') }}" id="AddForm"
            method="post">
            @csrf
            <div class="text-center">
                <img id="previewImage" src="{{ asset('dashboard/assets/images/upload_img.png') }}" alt="">
            </div>
            <div class="mb-3">
                <label for="imageAlt" class="form-label">Image Alt</label>
                <input name="image_alt" type="text" class="form-control" id="imageAlt">
            </div>
            <input name="image" onchange="handeImageInputChange(event)" id="uploadImage" type="file" hidden>

            <div class="mb-3">
                <label for="titleInput" class="form-label">Title</label>
                <input name="title" type="text" class="form-control" id="titleInput" />
            </div>
            <div class="mb-3">
                <label for="short_title" class="form-label">Short Title</label>
                <input value="{{ old('short_title') }}" name="short_title" type="text" class="form-control"
                    id="short_title" required />
            </div>
            <div class="mb-3">
                <label for="slugTitle" class="form-label">Slug</label>
                <input name="slug" type="text" class="form-control" id="slug" />
            </div>
            <div class="mb-3">
                <label for="category_description" class="form-label">Description</label>
                <textarea id="category_description" name="category_description" class="form-control"></textarea>
            </div>
            <div class="row">
                <div class="col">
                    <div class="mb-3">
                        <label for="descriptionInput" class="form-label">Passengers</label>
                        <input id="passengers" name="passengers" type="number" class="form-control">
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="descriptionInput" class="form-label">Luggage</label>
                        <input id="luggage" name="luggage" type="number" class="form-control">
                    </div>
                </div>
            </div>

            <div class="mb-3 form-check">
                <input name="flight_tracking" type="checkbox" class="form-check-input" id="flight_tracking">
                <label class="form-check-label" for="exampleCheck1">Flight Tracking</label>
            </div>

            <h3 class="mt-5">Seo</h3>
            <hr class="mb-3">

            <div class="col">
                <div class="mb-3">
                    <label for="descriptionInput" class="form-label">Title</label>
                    <input name="seo_title" type="text" class="form-control">
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label for="descriptionInput" class="form-label">Description</label>
                    <input name="seo_description" type="text" class="form-control">
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label for="descriptionInput" class="form-label">Keyphrase</label>
                    <input name="seo_keyphrase" type="text" class="form-control">
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea id="content" name="content" class="form-control">{{old('content')}}</textarea>

                </div>
            </div>
            <div class="text-center">
                <h3>
                    Pricing Rules
                </h3>
                <h5 class="mt-2">
                    Defualt Prices
                </h5>
            </div>

            <h5>
                Daily Time
            </h5>
            <hr>
            <div class="row">
                <div class="col-4">
                    <label for="">From</label>
                    <input id="daily_from" class="form-control" type="time" name="daily_from">
                </div>
                <div class="col-4">
                    <label for="">To</label>
                    <input id="daily_to" class="form-control" type="time" name="daily_to">
                </div>
                <div class="col-4">
                    <label for="">Price</label>
                    <input id="daily_price" min="1" name="daily_price" type="number" min="0"
                        class="form-control" aria-label="Amount (to the nearest dollar)">
                </div>
            </div>



            <h5 class="mt-3">
                Reserving Time
            </h5>
            <hr>
            <div class="row mb-5">

                <div class="col-3">
                    24 - 18:01 Hours
                    <select name="periodTwentyfour" id="period-24" data-hours="24" data-type="period"
                        class="form-control mb-2">
                        <option value="15">15 min</option>
                        <option value="30">30 min</option>
                        <option value="45">45 min</option>
                        <option value="60">60 min</option>
                    </select>
                    <input name="chargeTwentyfour" required id="charge-24" data-hours="24" min="0"
                        max="100" data-type="charge" type="number" placeholder="Charge %" class="form-control">
                </div>
                <div class="col-3">
                    18 - 12:01 Hours
                    <select name="periodNineteen" id="period-19" data-hours="18" data-type="period"
                        class="form-control mb-2">
                        <option value="15">15 min</option>
                        <option value="30">30 min</option>
                        <option value="45">45 min</option>
                        <option value="60">60 min</option>
                    </select>
                    <input required name="chargeNineteen" id="charge-19" data-hours="18" min="0" max="100"
                        data-type="charge" type="number" placeholder="Charge %" class="form-control">
                </div>
                <div class="col-3">
                    12 - 6:01 Hours
                    <select id="periodTwelve" name="periodTwelve" data-hours="24" data-type="period"
                        class="form-control mb-2">
                        <option value="15">15 min</option>
                        <option value="30">30 min</option>
                        <option value="45">45 min</option>
                        <option value="60">60 min</option>
                    </select>
                    <input required id="chargeTwelve" name="chargeTwelve" data-hours="24" min="0" max="100"
                        data-type="charge" type="number" placeholder="Charge %" class="form-control">
                </div>
                <div class="col-3">
                    6 - 1 Hours
                    <select name="periodSix" id="period-6" data-type="period" class="form-control mb-2">
                        <option value="15">15 min</option>
                        <option value="30">30 min</option>
                        <option value="45">45 min</option>
                        <option value="60">60 min</option>
                    </select>
                    <input required name="chargeSix" min="0" max="100" data-type="charge" type="number"
                        placeholder="Charge %" class="form-control">
                </div>

            </div>


            <h5>
                Hourly
            </h5>
            <hr>
            <div class="row">
                <div class="mb-3 col-3">
                    <label for="minimum_hour" class="form-label">Minmum Hours</label>
                    <input type="number" required class="form-control" name="minimum_hour" id="minimum_hour"
                        placeholder="add minmum hours">
                </div>
                <div class="mb-3 col-3">
                    <label for="mile_per_hour" class="form-label">Mile Per Hour</label>
                    <input type="number" required class="form-control" name="mile_per_hour" id="mile_per_hour"
                        placeholder="add mile per hour">
                </div>
                <div class="mb-3 col-3">
                    <label for="price_per_hour" class="form-label">Price Per Hour</label>
                    <input type="number" required class="form-control" name="price_per_hour" id="price_per_hour"
                        placeholder="add price per hour">
                </div>
                <div class="mb-3 col-3">
                    <label for="extra_price_per_mile_hourly" class="form-label">Price Per Extra Mile</label>
                    <input type="number" required class="form-control" name="extra_price_per_mile_hourly"
                        id="extra_price_per_mile_hourly" placeholder="add price per extra mile">
                </div>
            </div>

            <h5 class="mt-2">
                Point to point
            </h5>
            <hr>
            <div class="row">
                <div class="col-4">
                    <div class="mb-3">
                        <label for="initial_fee" class="form-label">Initial Fee</label>
                        <input type="number" required class="form-control" name="initial_fee" id="initial_fee"
                            placeholder="add initial fee">
                    </div>
                </div>
                <div class="col-4">
                    <div class="mb-3">
                        <label for="minimum_mile" class="form-label">Minimum mile</label>
                        <input type="number" required class="form-control" name="minimum_mile" id="minimum_mile"
                            placeholder="add minimum mile">
                    </div>
                </div>
                <div class="col-4">
                    <div class="mb-3">
                        <label for="point_to_point_extra_price_per_mile" class="form-label">Extra price per mile</label>
                        <input type="number" required class="form-control" name="point_to_point_extra_price_per_mile"
                            id="point_to_point_extra_price_per_mile" placeholder="add extra price per mile">
                    </div>
                </div>
            </div>



            <div class="text-right mt-2">
                <a href="{{ route('dashboard.pages.index', 'Fleet Category') }}" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>


        </form>
    </div>

@endsection

@section('scripts')
    <script>
        $("#AddForm").validate({
            rules: {
                image_alt: {
                    required: true
                },
                title: {
                    required: true
                },
                slug: {
                    required: true
                },
                category_description: {
                    required: true
                },
                passengers: {
                    required: true
                },
                luggage: {
                    required: true
                },
                daily_from: {
                    required: function(element) {
                        return $("#daily_to").val().length > 0 || $("#daily_price").val().length > 0;
                    }
                },
                daily_to: {
                    required: function(element) {
                        return $("#daily_from").val().length > 0 || $("#daily_price").val().length > 0;
                    }
                },
                daily_price: {
                    required: function(element) {
                        return $("#daily_from").val().length > 0 || $("#daily_to").val().length > 0;
                    }
                }
            },
            submitHandler: function(form) {
                checkSlug()
                return false;
            }
        });

        function handeImageInputChange(event) {
            const image_url = URL.createObjectURL(event.target.files[0]);
            document.getElementById('previewImage').src = image_url;
        }

        $("#previewImage").click(function() {
            $('#uploadImage').trigger('click')
        });

        function checkSlug(form) {
            let request = '{{ route('dashboard.fleetCategories.check_slug', ':slug') }}';
            let slug = document.getElementById('slug') ? document.getElementById('slug').value : Math.random();
            request = request.replace(":slug", slug)
            $.ajax({
                url: request,
                type: 'GET',
                success: (res) => {
                    let is_available = res.data.is_available;

                    if (is_available) {
                        document.getElementById('AddForm').submit();
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Please check and make sure the slug is unique.'
                        })
                    }
                    return is_available;
                },
                error: (err) => {
                    return false
                }
            });
        }
    </script>
@endsection
