@extends('dashboard.layouts.index')


@section('content')
<style>
    input#flexSwitchCheckDefault {
    width: 50px;
    height: 25px;
}
img.card-img-top {
    height: 200px !important;
    object-fit: fill;
}
</style>
    <form enctype="multipart/form-data" action="{{ route('dashboard.events.update',$data->id) }}" id="AddForm" method="post">
        @csrf
         <div class="card p-3">
            <div class="container">
                <h3 class="text-bold">Edit Event</h3>
            <hr/>
                  <div class="row mb-4">
                    <div class="col-4">
                        <div>
                            <label for="radius" class="form-label">Event Name</label>
                            <input value="{{ $data->name }}" required name="event_name" type="text" class="form-control" id="event_name">
                          </div>
                    </div>
                    <div class="col-4">
                        <label for="radius" class="form-label">Cities</label>
                        <select onchange="selectCity(event)" required id="city" name="city" class="form-control">
                            <option value="" disabled selected>Select</option>
                            @foreach ($cities as $item)
                                <option {{ $item->id == $data->city_id ? 'selected' : ''}} data-cityName="{{ $item->title }}" value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4">
                        <div>
                            <label for="radius" class="form-label">Status</label>
                            <select class="form-control" name="status" id="statusInput">
                                <option {{ $data->status == 'Active'?'selected':'' }} value="Active">Active</option>
                                <option {{ $data->status == 'Disabled'?'selected':'' }} value="Disabled">Unactive</option>
                            </select>
                          </div>
                    </div>
                    
                  </div>
                  <div class="row">
                    <div class="col-10">
                            <label for="title" class="form-label">Place Name</label>
                            <div class="row">
                                <div class="col-10">
                                    <input required value="{{ $data->address }}" name="place_name" type="text" class="form-control" id="place_name" placeholder="Enter place name">
                                </div>
                                <div class="col-2">
                                    <button id="search_button" onclick="performSearch()" type="button" class="btn btn-primary btn-sm">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                    </div>
                    <div class="col-2 justify-content-end">
                        <label for="radius" class="form-label">Radius MM</label>
                        <input required onkeyup="updateRadius()" value="{{ $data->radius }}" name="radius" type="number" class="form-control" id="radius">
                        
                    </div>
                  </div>
               
                
                  

                  <div class="row mt-5">
                    <h4 class="mb-4">
                        Map Information
                    </h4>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="lat" class="form-label">Lat</label>
                            <input  value="{{ $data->latitude }}" required disabled name="lat" type="text" class="form-control" id="lat">
                          </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="long" class="form-label">Long</label>
                            <input value="{{ $data->longitude }}" required disabled name="long" type="text" class="form-control" id="long">
                          </div>
                    </div>
                  </div>

                  
                

                  <div class="mb-5">
                      <div id="map" style="height: 400px; width: 100%;"></div>
                  </div>

                  <div class="row mb-5">
                    <div class="col-6">
                        <label for="radius" class="form-label">Start Date</label>
                        <input value="{{ $data->start_date->format('Y-m-d') }}" required id="startDate" onchange="setEndDate()" name="start_date" type="date" class="form-control">
                    </div>
                    <div class="col-6">
                        <label for="radius" class="form-label">End Date</label>
                        <input value="{{ $data->endless == 0? $data->end_date->format('Y-m-d'):null }}" required disabled id="endDate" type="date" name="end_date" class="form-control" >
                    </div>
                  </div>
                  <div class="row mb-5">
                    <div class="col-6">
                        <label for="radius" class="form-label">Start Time</label>
                        <input value="{{ $data->start_time?$data->start_time->format('H:i'):null }}" id="startTime" type="time" class="form-control">
                    </div>
                    <div class="col-6">
                        <label for="radius" class="form-label">End Time</label>
                        <input value="{{ $data->end_time?$data->end_time->format('H:i'):null }}" id="endTime" type="time" class="form-control">
                    </div>
                  </div>
                  <div class="row mb-5">
                    {{-- <div class="col-6">
                        <label for="radius" class="form-label">Cities</label>
                        <select required id="city" name="city" class="form-control">
                            @foreach ($cities as $item)
                                <option data-cityName="{{ $item->title }}" {{ $data->city_id == $item->id?'selected':'' }} value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="col-6 mb-3">
                        <label for="radius" class="form-label">Price Increase Type</label>
                        <select id="discountType" onchange="changeTypeOfDiscount(event.target.value)" class="form-control" name="discount_type">
                            <option {{ $data->discount_type == 'Price'?'selected':'' }} value="price">Price</option>
                            <option {{ $data->discount_type == 'Percentage'?'selected':'' }} value="percentage">Percentage</option>
                        </select>
                    </div>
                    <div id="priceDiv" class="col-6" style={{ $data->discount_type == 'Percentage'?"display:none;":"" }}>
                        <label for="radius" class="form-label">Price</label>
                        <input id="price" value="{{ $data->discount_type == 'Price'?$data->price:'' }}" required min="1" type="number" class="form-control">
                    </div>
                    <div id="percentageDiv" class="col-6" style={{ $data->discount_type == 'Price'?"display:none;":"" }}>
                        <label for="radius" class="form-label">Percentage</label>
                        <input value="{{ $data->discount_type == 'Percentage'?$data->price:'' }}" name="percentage" id="percentage" required min="1" max="100" type="number" class="form-control">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="radius" class="form-label">Service Type</label>
                        <select id="service_type" required class="form-control" name="service_type">
                            <option  {{ $data->service_type == "0"?'selected':''  }} value="0">Both</option>
                            <option  {{ $data->service_type == "1"?'selected':'' }} value="1">Point to point</option>
                            <option  {{ $data->service_type == "2"?'selected':'' }} value="2">Hourly</option>
                        </select>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="radius" class="form-label">Apply for</label>
                        <select id="apply_for"class="form-control" name="apply_for">
                            <option {{ $data->apply_for == 'Both'?'selected':'' }} value="Both">Both</option>
                            <option {{ $data->apply_for == 'Pickup'?'selected':'' }} value="Pickup">PickUp</option>
                            <option {{ $data->apply_for == 'DropOff'?'selected':'' }} value="DropOff">Drop off</option>
                        </select>
                    </div>

                    <div class="col-6 mb-3">
                        <label for="radius" class="form-label">Endless</label>
                        <select onchange="checkEndlessStatus(event)" id="endless"class="form-control" name="endless">
                            <option  {{ $data->endless == 0 ? 'selected':'' }} value="0">No</option>
                            <option  {{ $data->endless == 1 ? 'selected':'' }} value="1">Yes</option>
                        </select>
                    </div>
                  </div>
                  



                  <div class="text-right">
                      <button type="submit" class="btn btn-primary">Update</button>
                  </div>
              
              
         </div>
    </form>


    <div class="modal fade" id="placesModal" tabindex="-1" role="dialog" aria-labelledby="placesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="placesModalLabel">Nearby Places</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" id="modalPlacesGrid"></div>
                </div>
            </div>
        </div>
    </div>
      


   
   

@endsection

@section('scripts')
        
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCY7UP_ICqDk1BwzqxxOnEVAw4Mt-uk_ik&libraries=places"></script>

    <script>
        const searchText = document.getElementById('place_name');
        const latitudeInput = document.getElementById('lat');
        const longitudeInput = document.getElementById('long');
        const radiusInput = document.getElementById('radius');
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        const startTimeInput = document.getElementById('startTime');
        const endTimeInput = document.getElementById('endTime');
        const eventNameInput = document.getElementById('event_name');
        const placeNameInput = document.getElementById('place_name');
        const placesGrid = document.getElementById('modalPlacesGrid');
        const cityInput = document.getElementById('city');
        const priceInput = document.getElementById('price');
        const statusInput = document.getElementById('statusInput');
        const percentageInput = document.getElementById('percentage');
        const discountTypeInput = document.getElementById('discountType');
        const serviceTypeInput = document.getElementById('service_type');
        const applyForInput = document.getElementById('apply_for');
        const endlessInput = document.getElementById('endless');
        const searchButton = document.getElementById('search_button');

        let map;
        let mainMarker = null;
        let circle = null;
        let radius_path = JSON.parse('{!! $data->radius_area !!}');
        let cityName = '{{ $data->cities->title }}';
        let locationCordaniate  = @json($data);
        console.log(locationCordaniate);
        function initMap() {
                    map = new google.maps.Map(document.getElementById('map'), {
                        center: { lat: locationCordaniate.latitude, lng: locationCordaniate.longitude},
                        zoom: 12
            });
        }

        initMap();


        function checkEndlessStatus(event)
        {
            if(event.target.value == 1)
            {
                endDateInput.disabled = true;
                endDateInput.value = '';
            }
            else
            {
                endDateInput.disabled = false;
            }
        }

        function performSearch() 
        {
            const service = new google.maps.places.PlacesService(document.createElement('div'));
            
            service.textSearch({
                query: searchText.value
            }, (results, status) => {
                if(results.length == 0)
                    {
                        Toast.fire({
                            icon: 'info',
                            title: 'Address not found'
                        })
                        latitudeInput.value = '';
                        longitudeInput.value = '';
                        placeNameInput.value = '';
                        if(mainMarker) mainMarker.setMap(null)
                        if(circle) circle.setMap(null);


                        return;
                    }
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    const resultsDiv = document.getElementById('results');
                    let bounds = new google.maps.LatLngBounds();
                    let placeAvailable = false;
                    $('#modalPlacesGrid').empty()
                   
                    for (let i = 0; i < results.length; i++) 
                    {
                        const place = results[i];
                        const latitude = place.geometry.location.lat();
                        const longitude = place.geometry.location.lng();

                        latitudeInput.value = latitude;
                        longitudeInput.value = longitude;
                        const mapCenter = map.getCenter();

                        console.log(cityName)
                        if(place.formatted_address.includes(cityName))
                       {
                           let searchRadius = parseInt(!radiusInput.value?1000:radiusInput.value);
    
                           const photoUrl = place.photos ? place.photos[0].getUrl() : '';
                           const col = document.createElement('div');
                           col.className = 'col-6 d-flex align-items-stretch';
    
                           const card = document.createElement('div');
                           card.className = 'card w-100';
                           card.innerHTML = `
                               <img src="${photoUrl}" onerror="handleImageError(this)" class="card-img-top" alt="${name}" height="100px" data-toggle="modal" data-target="#placesModal" data-place-index="${i}">
                               <div class="card-body">
                                   <h5 class="card-title">${name}</h5>
                                   <p class="card-text">Rating: ${place.rating || 'N/A'}</p>
                                   <p class="card-text">Address: ${place.formatted_address}</p>
                                   <div class="text-center">
                                       <button type="button" onclick="selectPlace(${latitude},${longitude},'${place.formatted_address}')" class="btn btn-primary w-50">
                                           Select
                                       </button>
                                   </div>
                               </div>
                           `;
    
                           col.appendChild(card);
                           placesGrid.appendChild(col);
                           $('#placesModal').modal('show');
                           placeAvailable = true;
                       }
                        
                    }
                    if(!placeAvailable)
                    {
                        Toast.fire({
                            icon: 'info',
                            title: 'Address not found'
                        })
                        return;
                    }
                    $("#placesModal").toggle();
                    map.fitBounds(bounds);
                }
            });

        }


        function handleImageError(img) {
            img.src = 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg?20200913095930';
            img.onerror = null;
        }

        function selectPlace(lat,long,address)
        {
            if(mainMarker) mainMarker.setMap(null)
            if(circle) circle.setMap(null);
            latitudeInput.value = lat;
            longitudeInput.value = long;
            mainMarker = new google.maps.Marker({
                            position: { lat: lat, lng: long },
                            map: map,
                            zoom:12,
                            title: address
            });
            circle =  new google.maps.Circle({
                            strokeColor: '#4285F4',
                            strokeOpacity: 0.8,
                            strokeWeight: 2,
                            fillColor: '#4285F4',
                            fillOpacity: 0.2,
                            map: map,
                            center: mainMarker.getPosition(),
                            radius: radiusInput.value?parseInt(radiusInput.value):1000
            });
            let bounds = new google.maps.LatLngBounds();
            bounds.extend(mainMarker.getPosition());
            map.fitBounds(bounds);
            console.log(address)
            placeNameInput.value = address ?? placeNameInput.value;
            $("#placesModal").modal('hide');
        }

       function updateRadius()
        {
            if(mainMarker)
            {
                circle.setMap(null);
                circle =  new google.maps.Circle({
                    strokeColor: '#4285F4',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#4285F4',
                    fillOpacity: 0.2,
                    map: map,
                    center: mainMarker.getPosition(),
                    radius: parseInt(radiusInput.value)
                });

                const circleCenter = circle.getCenter();
                const circleRadius = circle.getRadius();
                const pointsInCircle = calculatePointsInCircle(circleCenter, circleRadius, 100);
                radius_path = pointsInCircle
            }

            
        }

        function calculatePointsInCircle(center, radius, numPoints) {
            const points = [];
            for (let i = 0; i < numPoints; i++) {
                const angle = (i / numPoints) * 360;
                const lat = center.lat() + radius * Math.cos(angle * Math.PI / 180);
                const lng = center.lng() + radius * Math.sin(angle * Math.PI / 180);
                points.push({ lat: lat, lng: lng });
            }
            return points;
        }

        function calculateDistance(point1, point2) {
            const lat1 = point1.lat();
            const lng1 = point1.lng();
            const lat2 = point2.lat();
            const lng2 = point2.lng();
            const earthRadius = 6371000; // Radius of the Earth in meters

            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLng / 2) * Math.sin(dLng / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            const distance = earthRadius * c;

            return distance;
        }

        function setEndDate()
        {
            if(!startDateInput.value)
            {
                endDateInput.value = "";
                endDateInput.disabled = true;
            }
            else if(startDateInput.value && endlessInput.value == 0)
            {
                endDateInput.disabled = false;
                endDateInput.min = startDateInput.value;
                
            }
        }


        $("#AddForm").validate({
                rules: {
                    image: {
                        required: true
                    },
                    title: {
                        required: true
                    },
                    link: {
                        required: true,
                        validLink: true
                    },
                    end_date:{
                        required:{
                            depends:()=>{
                               return endlessInput.value == 0 ? true : false;
                            }
                        }
                    },
                    lat:{
                        required:true
                    },
                    long:{
                        required:true
                    },
                    apply_for:{
                        required:true
                    },
                    service_type:{
                        required:true
                    },
                    price:{
                        required:{
                            depends:()=>{
                                return discountTypeInput.value == 'price'?true:false
                            }
                        }
                    },
                    percentage:{
                        required:{
                            depends:()=>{
                                return discountTypeInput.value == 'percentage'?true:false
                            }
                        }
                    },

                },
                    messages: {
                    slug: {
                        required: 'Please enter a slug'
                    }
                },
                submitHandler:function(form) {
                    collectAndSendData(form);
                   return false;
                }
            });



        function collectAndSendData(form)
        {
            const data = {
                name: eventNameInput.value,
                address: placeNameInput.value,
                radius: radiusInput.value,
                radius_area:radius_path,
                latitude: latitudeInput.value,
                longitude: longitudeInput.value,
                start_date:startDateInput.value,
                end_date:endDateInput.value,
                start_time:startTimeInput.value,
                end_time:endTimeInput.value,
                price:discountTypeInput.value == 'price'?priceInput.value:percentageInput.value,
                city_id:cityInput.value,
                service_type:serviceTypeInput.value,
                apply_for:applyForInput.value,
                status:statusInput.value,
                endless:endlessInput.value,
                discount_type:discountTypeInput.value,
                _token:'{{ csrf_token() }}'
            }
            let request = '{{ route("dashboard.events.update",":id") }}';
            request = request.replace(":id",'{{ $data->id }}');

            $.ajax({
                url:request,
                type:'POST',
                data,
                success:(res)=>{
                    const redireact_url = '{{ route("dashboard.events.index") }}';
                    if(res.status == 201) window.location.href = redireact_url;
                    else
                    {
                        Toast.fire({
                            icon: 'info',
                            title: "Something went wrong,Please make sure the data you are entering it's correct"
                        })
                        return;
                    }
                },
                error:(err)=>{
                    Toast.fire({
                        icon: 'error',
                        title: "Server error!"
                    })
                }
            })
        }


        function getItemRadiusPath()
        {
         let lat = '{{ $data->latitude }}';
         let long = '{{ $data->longitude }}';
            mainMarker = new google.maps.Marker({
                            position: { lat: Number(lat), lng:Number(long) },
                            map: map,
                            zoom:12,
                            title: '{{ $data->address }}'
            });
            let bounds = new google.maps.LatLngBounds();
            bounds.extend(mainMarker.getPosition());
            map.fitBounds(bounds);
            circle =  new google.maps.Circle({
                    strokeColor: '#4285F4',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#4285F4',
                    fillOpacity: 0.2,
                    map: map,
                    center: mainMarker.getPosition(),
                    radius: parseInt(radiusInput.value)
                });
        }

        getItemRadiusPath();

        function changeTypeOfDiscount(value)
        {
            if(value == 'price')
            {
                $('#percentageDiv').hide();
                $('#priceDiv').show();
                percentageInput.value = '';
            }
            else
            {
                $('#percentageDiv').show();
                $('#priceDiv').hide();
                priceInput.value = '';
            }
        }

        function selectCity(event)
        {
            const selectedOption = $('#'+event.target.id+' option:selected');
            cityName = selectedOption.attr('data-cityName');
            searchButton.disabled = false;
            searchText.disabled = false;
            searchText.value = "";
            if(mainMarker) mainMarker.setMap(null)
            if(circle) circle.setMap(null);

            radiusInput.value = null;

        }


 

    </script>
@endsection