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
        
        <form enctype="multipart/form-data" action="{{ route('dashboard.fleetCategories.update',$data->id) }}" id="AddForm" method="post">
            @csrf
            <div class="text-center">
                <img id="previewImage" onerror="this.onerror=null;this.src='{{ asset('dashboard/assets/images/upload_img.png') }}';" src="{{ $data->avatar }}" alt="" >
            </div>
            <div class="mb-3">
                <label for="imageAlt" class="form-label">Image Alt</label>
                <input value="{{ $data->image_alt }}" name="image_alt" type="text" class="form-control" id="imageAlt">
              </div>
              <input name="image" onchange="handeImageInputChange(event)" id="uploadImage" type="file" style="display:none;">
             <div class="mb-3">
                <label for="titleInput" class="form-label">Title</label>
                <input value="{{ $data->title }}" name="title" type="text" class="form-control" id="titleInput" />
              </div>
              <div class="mb-3">
                <label for="short_title" class="form-label">Short Title</label>
                <input value="{{ isset($data)?$data->short_title:''}}" name="short_title" type="text" class="form-control" id="short_title" required />
              </div>
             <div class="mb-3">
                <label for="slugTitle" class="form-label">Slug</label>
                <input value="{{ $data->slug }}" name="slug" type="text" class="form-control" id="slug" />
              </div>
              <div class="mb-3">
                <label for="category_description" class="form-label">Description</label>
                <textarea id="category_description" name="category_description" class="form-control">{{ $data->category_description }}</textarea>
              </div>
              <div class="row">
                <div class="col">
                    <div class="mb-3">
                        <label for="descriptionInput" class="form-label">Passengers</label>
                        <input value="{{ $data->passengers }}" id="passengers" name="passengers" type="number" class="form-control">
                      </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="descriptionInput" class="form-label">Luggage</label>
                        <input value="{{ $data->luggage }}" id="luggage" name="luggage" type="number" class="form-control">
                      </div>
                </div>
              </div>
            
              <div class="mb-3 form-check">
                <input {{ $data->flight_tracking?'checked':'' }} name="flight_tracking" type="checkbox" class="form-check-input" id="flight_tracking">
                <label class="form-check-label" for="exampleCheck1">Flight Tracking</label>
              </div>

              <h3 class="mt-5">Seo</h3>
              <hr class="mb-3">

              <div class="col">
                <div class="mb-3">
                    <label for="descriptionInput" class="form-label">Title</label>
                    <input value="{{ $data->seo_title }}" name="seo_title" type="text" class="form-control">
                  </div>
              </div>
              <div class="col">
                <div class="mb-3">
                    <label for="descriptionInput" class="form-label">Description</label>
                    <input value="{{ $data->seo_description }}" name="seo_description" type="text" class="form-control">
                  </div>
              </div>
              <div class="col">
                <div class="mb-3">
                    <label for="descriptionInput" class="form-label">Keyphrase</label>
                    <input value="{{ $data->seo_keyphrase }}" name="seo_keyphrase" type="text" class="form-control">
                  </div>
              </div>
              <div class="col">
                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea id="content" name="content" class="form-control">{{ $data->content??$data->content }}</textarea>

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
                    <input id="daily_from" class="form-control" value="{{ $data->daily_from }}" type="time" name="daily_from">
                </div>
                <div class="col-4">
                    <label for="">To</label>
                    <input id="daily_to" class="form-control" value="{{ $data->daily_to }}" type="time" name="daily_to">
                </div>
                <div class="col-4">
                    <label for="">Price</label>
                    <input id="daily_price" min="1" name="daily_price" value="{{ $data->daily_price }}" type="number" min="0" class="form-control" aria-label="Amount (to the nearest dollar)">
                </div>
             </div>


            <h5 class="mt-3 mb-3">
                Reserving Time
             </h5>
             <hr>
             <div class="row mb-3">

                <div class="col-3">
                    24 - 18:01 Hours
                    <select name="periodTwentyfour" id="period-24" data-hours="24" data-type="period" class="form-control mb-2">
                        <option {{ $periodTwentyfour&&$periodTwentyfour->period == 15 ?'selected':'' }} value="15">15 min</option>
                        <option {{ $periodTwentyfour&&$periodTwentyfour->period == 30 ?'selected':'' }} value="30">30 min</option>
                        <option {{ $periodTwentyfour&&$periodTwentyfour->period == 45 ?'selected':'' }} value="45">45 min</option>
                        <option {{ $periodTwentyfour&&$periodTwentyfour->period == 60 ?'selected':'' }} value="60">60 min</option>
                    </select>
                    <input value="{{ $periodTwentyfour&&$periodTwentyfour->charge?$periodTwentyfour->charge:1 }}" name="chargeTwentyfour" required id="charge-24" data-hours="24" min="0" max="100"  data-type="charge" type="number"  placeholder="Charge %" class="form-control">
                </div>
                <div class="col-3">
                    18 - 12:01 Hours
                    <select name="periodNineteen" id="period-19" data-hours="18" data-type="period" class="form-control mb-2">
                        <option {{ $periodNineteen&&$periodNineteen->period == 15 ?'selected':'' }} value="15">15 min</option>
                        <option {{ $periodNineteen&&$periodNineteen->period == 30 ?'selected':'' }} value="30">30 min</option>
                        <option {{ $periodNineteen&&$periodNineteen->period == 45 ?'selected':'' }} value="45">45 min</option>
                        <option {{ $periodNineteen&&$periodNineteen->period == 60 ?'selected':'' }} value="60">60 min</option>
                    </select>
                    <input value="{{ $periodNineteen&&$periodNineteen->charge?$periodNineteen->charge:1 }}" required name="chargeNineteen" id="charge-19" data-hours="18" min="0" max="100"  data-type="charge" type="number"  placeholder="Charge %" class="form-control">
                </div>
                <div class="col-3">
                    12 - 6:01 Hours
                    <select id="periodTwelve" name="periodTwelve" data-hours="24" data-type="period" class="form-control mb-2">
                        <option {{ $periodTwelve&&$periodTwelve->period == 15 ?'selected':'' }} value="15">15 min</option>
                        <option {{ $periodTwelve&&$periodTwelve->period == 30 ?'selected':'' }} value="30">30 min</option>
                        <option {{ $periodTwelve&&$periodTwelve->period == 45 ?'selected':'' }} value="45">45 min</option>
                        <option {{ $periodTwelve&&$periodTwelve->period == 60 ?'selected':'' }} value="60">60 min</option>
                    </select>
                    <input value="{{ $periodTwelve&&$periodTwelve->charge?$periodTwelve->charge:1 }}" required id="chargeTwelve" name="chargeTwelve" data-hours="24" min="0" max="100"  data-type="charge" type="number"  placeholder="Charge %" class="form-control">
                </div>
                <div class="col-3">
                    6 - 1 Hours
                    <select name="periodSix" id="period-6" data-type="period" class="form-control mb-2">
                        <option {{ $periodSix&&$periodSix->period == 15 ?'selected':'' }} value="15">15 min</option>
                        <option {{ $periodSix&&$periodSix->period == 30 ?'selected':'' }} value="30">30 min</option>
                        <option {{ $periodSix&&$periodSix->period == 45 ?'selected':'' }} value="45">45 min</option>
                        <option {{ $periodSix&&$periodSix->period == 60 ?'selected':'' }} value="60">60 min</option>
                    </select>
                    <input value="{{ $periodSix&&$periodSix->charge?$periodSix->charge:1 }}" required name="chargeSix" min="0" max="100"  data-type="charge" type="number"  placeholder="Charge %" class="form-control">
                </div>

             </div>


            <h5>
                Hourly
            </h5>
            <hr>
            @php
                $pricing = json_decode($data->pricing_rules);
               // dd($pricing);
            @endphp
            <div class="row">
                <div class="mb-3 col-3">
                    <label for="minimum_hour" class="form-label">Minmum Hours</label>
                    <input type="number" value="{{ $pricing->minimum_hour }}" required class="form-control" name="minimum_hour" id="minimum_hour" placeholder="add minmum hours">
                </div>
                <div class="mb-3 col-3">
                    <label for="mile_per_hour" class="form-label">Mile Per Hour</label>
                    <input type="number" value="{{ $pricing->minimum_mile_hour }}" required class="form-control" name="mile_per_hour" id="mile_per_hour" placeholder="add mile per hour">
                </div>
                <div class="mb-3 col-3">
                    <label for="price_per_hour" class="form-label">Price Per Hour</label>
                    <input type="number" value="{{ $pricing->price_per_hour }}" required class="form-control" name="price_per_hour" id="price_per_hour" placeholder="add price per hour">
                </div>
                <div class="mb-3 col-3">
                    <label for="extra_price_per_mile_hourly" class="form-label">Price Per Extra Mile</label>
                    <input type="number" value="{{ $pricing->extra_price_per_mile_hourly ?? 0 }}" required class="form-control" name="extra_price_per_mile_hourly" id="extra_price_per_mile_hourly" placeholder="add price per extra mile">
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
                        <input value="{{ isset($pricing->initial_fee)?$pricing->initial_fee:'' }}" type="number" required class="form-control" name="initial_fee" id="initial_fee" placeholder="add initial fee">
                    </div>
                </div>
                <div class="col-4">
                    <div class="mb-3">
                        <label for="minimum_mile" class="form-label">Minimum mile</label>
                        <input value="{{ isset($pricing->minimum_mile)?$pricing->minimum_mile:'' }}" type="number" required class="form-control" name="minimum_mile" id="minimum_mile" placeholder="add minimum mile">
                    </div>
                </div>
                <div class="col-4">
                    <div class="mb-3">
                        <label for="point_to_point_extra_price_per_mile" class="form-label">Extra price per mile</label>
                        <input value="{{ isset($pricing->extra_price_per_mile)?$pricing->extra_price_per_mile:'' }}" type="number" required class="form-control" name="point_to_point_extra_price_per_mile" id="extra_price_per_mile" placeholder="add extra price per mile">
                    </div>
                </div>
            </div>

              <div class="text-right">
                  <a href="{{ route("dashboard.pages.index",'Fleet Category') }}" class="btn btn-secondary">Back</a>
                  <button type="submit" class="btn btn-primary">Update</button>
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
                    category_description:{
                        required: true
                    },
                    passengers:{
                        required: true
                    },
                    luggage:{
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
                   
                submitHandler:function(form) {
                   
                   return true;
                }
            });
            
        function handeImageInputChange(event)
        {
            const image_url = URL.createObjectURL(event.target.files[0]);
           document.getElementById('previewImage').src = image_url;
        }
        
        $("#previewImage").click(function() {
           $('#uploadImage').trigger('click')
        });


    </script>
@endsection