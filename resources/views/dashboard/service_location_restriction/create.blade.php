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
        <form enctype="multipart/form-data" action="{{ route('dashboard.serviceLocationRestrictions.store') }}" id="AddForm" method="post">
                @csrf
            <h3>
                Create 
            </h3>
            <hr class="mb-5" />
            
            <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <select onchange="selectCity(event)" name="city_id" class="form-control" id="city_id">
                                <option value="">Select</option>
                                @foreach ($cities as $item)
                                    <option data-cityName="{{ $item->title }}" value="{{ $item->id }}">{{ $item->title }}</option>                                   
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <label for="title" class="form-label">Address</label>
                        <div class="row">
                            <div class="col-10">
                                <input disabled required name="address" type="text" class="form-control" id="address" placeholder="Enter place name">
                            </div>
                            <div class="col-2">
                                <button id="search_button" disabled onclick="performSearch()" type="button" class="btn btn-primary btn-sm">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <label for="title" class="form-label">Radius MM</label>
                        <div class="row">
                            <div class="col">
                                <input min="0" onkeyup="updateRadius()" id="radius" disabled required name="radius" type="number" class="form-control" id="radius" placeholder="Restriction radius">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="city" class="form-label">Service</label>
                            <select name="service" class="form-control" id="service">
                                <option value="both">Both</option>
                                <option value="point_to_point">Point to point</option>
                                <option value="hourly">Hourly</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="city" class="form-label">Service Limitation</label>
                            <select name="service_limitation" class="form-control" id="service_limitation">
                                <option value="both">Both</option>
                                <option value="pick_up">Pick Up</option>
                                <option value="drop_off">Drop off</option>
                            </select>
                        </div>
                    </div>
                    
            </div>

                <div class="row mt-5">
                    <h4 class="mb-4">
                        Map Information
                    </h4>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="lat" class="form-label">Lat</label>
                            <input required disabled name="latitude" type="text" class="form-control" id="latitude">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="long" class="form-label">Long</label>
                            <input required disabled name="longitude" type="text" class="form-control" id="longitude">
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <div id="map" style="height: 400px; width: 100%;"></div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">
                        Create
                    </button>
                </div>
        </form>
    </div>

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

    const searchButton = document.getElementById('search_button');
        const searchText = document.getElementById('address');
        const radiusInput = document.getElementById('radius');
        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');
        const placesGrid = document.getElementById('modalPlacesGrid');
        const cityInput = document.getElementById('city');
        // const placeNameInput = document.getElementById('address');

        let map;
        let mainMarker = null;
        let circle = null;
        let radius_path = null;
        let cityName = null;


        $("#AddForm").validate({
            rules: {
                address: {
                    required: true
                },
                city_id: {
                    required: true
                },
                radius: {
                    required: true
                },
                service: {
                    required: true
                },
                service_limitation: {
                    required: true
                },
            },

            submitHandler:function(form) {
                latitudeInput.disabled = false;
                longitudeInput.disabled = false;
                let request =  "{{ route('dashboard.serviceLocationRestrictions.check_if_place_available',[':lat',':long',':radius']) }}";
                request = request.replace(':lat',latitudeInput.value);
                request = request.replace(':long',longitudeInput.value);
                request = request.replace(':radius',radiusInput.value);
                $.ajax({
                        url:request,
                        type: 'get',
                        success: function (res) {
                            // if the place is not available return an error message indecate that
                            if(!res.data.is_available)
                            {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'There is another restrication with the address "'+ res.data.item.name +'" in this area.'
                                })
                                return false;
                            }
                            else
                            {
                                // Now, proceed with the form submission
                                form.submit();
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('error: ', xhr, status, error);
                        }
                    });

                    // Prevent the default form submission
                    return false;
            }
        });

        function initMap() {
                    map = new google.maps.Map(document.getElementById('map'), {
                        center: { lat: 29.7723729, lng: -95.4566837},
                        zoom: 12
            });
        }

        initMap();

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
                        searchText.value = '';
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
                         
                    //    if(place.formatted_address.includes(cityName))
                    //    {
                           
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
                           radiusInput.disabled = false;

                        }
                        
                   // }
                 
                    if(!placeAvailable)
                    {
                        Toast.fire({
                            icon: 'info',
                            title: 'Address not found'
                        })
                        return;
                    }

                    map.fitBounds(bounds);
                   $("#placesModal").toggle();
                }
            });

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
            searchText.value = address ?? searchText.value;
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
            }

            
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
            }
        }







      
     
    </script>
@endsection
