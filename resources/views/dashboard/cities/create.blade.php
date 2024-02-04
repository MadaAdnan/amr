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
@php
    $time_periods = [1,2,3,4]
@endphp
    <div class="card p-3">
        @if ($errors->any())
        <div class="alert alert-danger p-5" role="alert">
             @foreach ($errors->all() as $error)
                <li> {{$error}}</li>
             @endforeach
         </div>
         @endif
        <form enctype="multipart/form-data" action="{{ route('dashboard.cities.store') }}" id="AddForm" method="post">
            @csrf
            <h4>Create City</h4>
            <hr>
            <div class="row">
                <div class="col-6 mb-3">
                   <label for="titleInput" class="form-label">States</label>
                   <select required onchange="getCityAccordingState(event)" name="state_id" id="states" class="form-control">
                    <option value="">Select</option>
                    @foreach ($states as $item)
                        <option data-state-name="{{ $item }}" value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                   </select>
                 </div>
                <div class="col-6 mb-3">
                   <label for="titleInput" class="form-label">Cities</label>
                   <select required disabled class="form-control" name="title" id="citySelect2">

                   </select>
                 </div>
                 <div class="col-6 mb-3">
                    <label for="titleInput" class="form-label">Copy City</label>
                    <select onchange="getCityInfo()" class="form-control" name="city_id" id="city_id">
                        <option  value="">Select</option>
                        @foreach ($cities as $item)
                            <option value="{{ $item->id }}">{{ $item->title }}</option>
                        @endforeach
                    </select>
                  </div>
                 <div class="col-6 mb-3">
                    <label for="titleInput" class="form-label">Link City</label>
                    <select id="linkCity" required class="form-control" name="link_city" id="link_city">
                        <option selected disabled value="">Select</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                  </div>
                 <h5>
                    Fleets
                 </h5>
                 <hr>
                 <div class="col-12 mb-3">
                    <label for="titleInput" class="form-label">Select fleet</label>
                    <select onchange="showHideFleets(event)" required multiple name="fleet_category[]" id="fleet_category" class="form-control">
                     @foreach ($fleetCategory as $item)
                         <option data-state-name="{{ $item->title }}" value="{{ $item->id }}">{{ $item->title }}</option>
                     @endforeach
                    </select>
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
                        <input id="daily_price" name="daily_price" type="number" min="0" class="form-control" aria-label="Amount (to the nearest dollar)">
                    </div>
                 </div>
                 <h5 class="mt-3">
                    Reserving Time
                </h5>
                <span class="note"> Reserving trips within 24 hours from pickup time in ascending order fee. </span>
                 <hr>
                 <div class="row">

                    <div class="col-3">
                        24 - 18:01 Hours
                        <select name="periodTwentyfour" id="period-24" data-hours="24" data-type="period" class="form-control mb-2">
                            <option value="15">15 min</option>
                            <option value="30">30 min</option>
                            <option value="45">45 min</option>
                            <option value="60">60 min</option>
                        </select>
                        <input name="chargeTwentyfour" required id="charge-24" data-hours="24" min="0" max="100"  data-type="charge" type="number"  placeholder="Charge %" class="form-control">
                    </div>
                    <div class="col-3">
                        18 - 12:01 Hours
                        <select name="periodNineteen" id="period-19" data-hours="18" data-type="period" class="form-control mb-2">
                            <option value="15">15 min</option>
                            <option value="30">30 min</option>
                            <option value="45">45 min</option>
                            <option value="60">60 min</option>
                        </select>
                        <input required name="chargeNineteen" id="charge-19" data-hours="18" min="0" max="100"  data-type="charge" type="number"  placeholder="Charge %" class="form-control">
                    </div>
                    <div class="col-3">
                        12 - 6:01 Hours
                        <select id="periodTwelve" name="periodTwelve" data-hours="24" data-type="period" class="form-control mb-2">
                            <option value="15">15 min</option>
                            <option value="30">30 min</option>
                            <option value="45">45 min</option>
                            <option value="60">60 min</option>
                        </select>
                        <input required id="charge-12" name="chargeTwelve" data-hours="24" min="0" max="100"  data-type="charge" type="number"  placeholder="Charge %" class="form-control">
                    </div>
                    <div class="col-3">
                        6 - 1 Hours
                        <select name="periodSix" id="period-6" data-type="period" class="form-control mb-2">
                            <option value="15">15 min</option>
                            <option value="30">30 min</option>
                            <option value="45">45 min</option>
                            <option value="60">60 min</option>
                        </select>
                        <input id="charge-6" required name="chargeSix" min="0" max="100"  data-type="charge" type="number"  placeholder="Charge %" class="form-control">
                    </div>

                 </div>
                
                <h5 class="mt-3">Pricing Rules</h5>
                <hr>
                <label class="font-weight-bold mb-4" >Hourly</label>
                 <div id="hourly_fleets">
                     @foreach ($fleetCategory as $index => $item)
                         
                         <h5 style="display: none;" class="font-weight-bold hourley-{{ $item->id }}">{{ $item->title }}</h5>
     
     
                         <div style="display: none;" class="row mb-3 hourley-{{ $item->id }}">
                             <div class="col-3">
                                 Minmum Hours
                                 <input required value="{{ array_key_exists('minimum_hour',$item->hourly)?$item->hourly['minimum_hour']:0 }}"  min="0" max="12" name="hourly[{{ $index }}][minimum_hour]"  id="minmum_hour" class="form-control" />
                             </div>
                            
                             <div class="col-3">
                                 Mile Per Hour
                                 <input required value="{{ array_key_exists('minimum_mile_hour',$item->hourly)?$item->hourly['minimum_mile_hour']:0 }}"  min="0" name="hourly[{{ $index }}][minimum_mile_hour]" type="number" class="form-control" id="minimum_mile_hour" />
                             </div>
                             <div class="col-3">
                                 Price Per Hour
                                 <input required value="{{ array_key_exists('price_per_hour',$item->hourly)?$item->hourly['price_per_hour']:0 }}"  min="0" name="hourly[{{ $index }}][price_per_hour]" type="number" class="form-control" id="price_per_hour" />
                             </div>
                             
                             <div class="col-3">
                                 Price Per Extra Mile
                                 <input required value="{{ array_key_exists('extra_price_per_mile_hourly',$item->hourly)?$item->hourly['extra_price_per_mile_hourly']:0 }}"  min="0" name="hourly[{{ $index }}][extra_price_per_mile_hourly]" type="number" class="form-control" id="extra_price_per_mile_hourly" />
                             </div>
                             <input type="text" value="{{ $item->id }}" hidden name="hourly[{{ $index }}][category_id]">
                            
                         </div>
     
                     @endforeach
                 </div>

                <hr>
                <label class="font-weight-bold" >Point to point</label>
                <span class="mb-4">Note:This will include the round trip X2</span>
                <div id="pointTopoint_fleets">

                @foreach ($fleetCategory as $index => $item)

                {{-- @if($item->id == 4)@dd($item)@endif --}}
                <h5 style="display: none;" class="font-weight-bold pointTopoint-{{ $item->id }}">{{ $item->title }}</h5>

                    <div style="display: none;" class="row mb-3 pointTopoint-{{ $item->id }}">
                        <div class="col">
                            Initial Fee
                            <input required value='{{ array_key_exists('initial_fee', $item->pointToPoint)?$item->pointToPoint['initial_fee']:0 }}' min="0" name="pointToPoint[{{ $index }}][initial_fee]" type="number" class="form-control" id="initial_fee" />
                        </div>
                        <div class="col">
                            Minimum mile
                            <input required value='{{ array_key_exists('minimum_mile',$item->pointToPoint)?$item->pointToPoint['minimum_mile']:0 }}' min="0" name="pointToPoint[{{ $index }}][minimum_mile]" type="number" class="form-control" id="minimum_mile" />
                        </div>
                        <div class="col">
                            Extra price per mile
                            <input required value='{{ array_key_exists('extra_price_per_mile', $item->pointToPoint)?$item->pointToPoint['extra_price_per_mile']:0 }}' min="0" name="pointToPoint[{{ $index }}][point_to_point_extra_price_per_mile]" type="number" class="form-control" id="extra_price_per_mile" />
                        </div>
                        <input type="text" value="{{ $item->id }}" hidden name="pointToPoint[{{ $index }}][category_id]">
                        
                    </div>
                @endforeach
                </div>



            </div>
             
              <div class="text-right mt-2">
                <a href="{{ route("dashboard.cities.index") }}" class="btn btn-secondary">Back</a>
                  <button id="createButton" type="submit" class="btn btn-primary">Create</button>
              </div>
            
    
        </form>        
    </div>    
   
@endsection


@section('scripts')
    <script>
            const selectStateSelect2 = $('#states').select2();
            const selectCitySelect2 = $('#citySelect2').select2();
            $('#fleet_category').select2();
            $('#city_id').select2();
            $('#linkCity').select2();
            const citySelect = document.getElementById('citySelect2');
            const createButton = document.getElementById('createButton');
            const stateSelect = document.getElementById('states');
            const getSelectedIds = [];
            const allFleets = @json($fleetCategory);
            createButton.disabled = true;

          $("#AddForm").validate({
                rules: {
                    question: {
                        required: true
                    },
                    answer: {
                        required: true
                    },
                    type: {
                        required: true
                    },
                    daily_to:{
                        required:{
                            depends: function(element) {
                                let is_required = false;
                                if($("#daily_from").val() != '') is_required = true;
                                if($("#daily_price").val() != '') is_required = true;
                                return is_required
                            }
                        }
                    },
                    daily_from:{
                        required:{
                            depends:function(e){
                               let is_required = false;
                                if($("#daily_to").val() != '') is_required = true;
                                if($("#daily_price").val() != '') is_required = true;
                                return is_required

                            }
                        }
                    },
                    daily_price:{
                        required:{
                            depends:function(e){
                               let is_required = false;
                                if($("#daily_to").val() != '') is_required = true;
                                if($("#daily_from").val() != '') is_required = true;
                                return is_required

                            }
                        }
                    },
                    split_hour_mechanism:{
                        required:{
                            depends:function(e){
                               let is_required = false;
                               console.log($("#split_hour_mechanism_price").val())
                                if($("#split_hour_mechanism_price").val() != '') is_required = true;
                                return is_required

                            }
                        }
                    },
                    split_hour_mechanism_price:{
                        required:{
                            depends:function(e){
                               let is_required = false;
                                if($("#split_hour_mechanism").val() != '') is_required = true;
                                return is_required

                            }
                        }
                    }
                },
                submitHandler:(form)=>{
                    var formData = new FormData(form);
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "{{ route('dashboard.cities.store') }}");
                    xhr.onreadystatechange = function() {
                            if (xhr.status === 200) {
                                var jsonData = JSON.parse(xhr.response);
                                const redireact_url = jsonData.data.url;
                                window.location.href = redireact_url;
                            } else {
                                console.error("Form submission failed: " + xhr.statusText);
                            }
                        
                    };

                    xhr.send(formData);


                    return false;
                }
            });


            function getCityAccordingState(event)
            {
                const slectedCity = event.target.value;
                const stateOptions = stateSelect.options[stateSelect.selectedIndex];
                const countrName = stateOptions.getAttribute('data-state-name');

                let request = '{{ route("dashboard.cities.getCityAccordingToState",":name") }}';
                request = request.replace(":name",countrName);
                $.ajax({
                    url:request,
                    type:'GET',
                    success:(res)=>{
                        console.log(res)
                        selectCitySelect2.empty();
                        if(res.data.length == 0)
                        {
                            citySelect.disabled = true;
                            createButton.disabled = true;
                        }
                        else
                        {
                            citySelect.disabled = false;
                            createButton.disabled = false;
                        }
                        const option = new Option('Choose', '');
                        selectCitySelect2.append(option);
                        res.data.forEach((value,index) =>{
                                const option = new Option(value, value);
                                selectCitySelect2.append(option);
                        });
                        selectCitySelect2.trigger('change');

                    },
                    error:(err)=>{

                    }
                });

            }

            function getCityAccordingState(event)
            {
                const slectedCity = event.target.value;
                const stateOptions = stateSelect.options[stateSelect.selectedIndex];
                const countrName = stateOptions.getAttribute('data-state-name');

                let request = '{{ route("dashboard.cities.getCityAccordingToState",":name") }}';
                request = request.replace(":name",countrName);
                $.ajax({
                    url:request,
                    type:'GET',
                    success:(res)=>{
                        console.log(res)
                        selectCitySelect2.empty();
                        if(res.data.length == 0)
                        {
                            citySelect.disabled = true;
                            createButton.disabled = true;
                        }
                        else
                        {
                            citySelect.disabled = false;
                            createButton.disabled = false;
                        }
                        const option = new Option('Choose', '');
                        selectCitySelect2.append(option);
                        res.data.forEach((value,index) =>{
                                const option = new Option(value, value);
                                selectCitySelect2.append(option);
                        });
                        selectCitySelect2.trigger('change');

                    },
                    error:(err)=>{

                    }
                });

            }

            function showHideFleets(event)
            {
                const selectValues = getSelectedValues(event.target.id)
                allFleets.forEach(item => {
                if(selectValues.includes(item.id.toString()))
                {
                    $('.pointTopoint-'+item.id).show();
                    $('.hourley-'+item.id).show();
                }
                else
                {
                    $('.pointTopoint-'+item.id).hide();
                    $('.hourley-'+item.id).hide();
                }
                })

            }

            function getSelectedValues(selectId) 
            {
                var selectElement = document.getElementById(selectId);

                var selectedValues = [];

                for (var i = 0; i < selectElement.options.length; i++) {
                    var option = selectElement.options[i];

                    if (option.selected) {
                    selectedValues.push(option.value);
                    }
                }

                return selectedValues;
            }

            function getCityInfo()
            {
                const city_id = document.getElementById('city_id').value;
                let request = '{{ route("dashboard.cities.getCityInfo",":id") }}';
                if(city_id == '') request = request.replace(":id",0);
                else
                {
                    request = request.replace(":id",city_id);
                }

                $.ajax({
                    url:request,
                    type:'GET',
                    success:(res)=>{
                       let data = res.data;
                       const defaultPrice = data.defaultPrice;
                       /** get the daily time info **/
                       $('#daily_from').val(data.daily_time.from);
                       $('#daily_to').val(data.daily_time.to);
                       $('#daily_price').val(data.daily_time.price);
                       /** get the reserving time **/
                       if(data.reserving_time.Twentyfour)
                       {
                           $('#period-24').val(data.reserving_time.Twentyfour.period);
                           $('#period-19').val(data.reserving_time.Nineteen.period);
                           $('#period-12').val(data.reserving_time.Twelve.period);
                           $('#period-6').val(data.reserving_time.Six.period);

                           $('#charge-24').val(data.reserving_time.Twentyfour.charge);
                           $('#charge-19').val(data.reserving_time.Nineteen.charge);

                           $('#charge-12').val(data.reserving_time.Twelve.charge);
                           $('#charge-6').val(data.reserving_time.Six.charge);
                       }

                       /** get the pricing data **/
                       /** Hourly */
                       $('#hourly_fleets').empty();
                       allFleets.forEach((item,index)=>{
                        const getRules = getPriceRules(item.id,2) ?? item.hourly;
                            const div = `
                            <h5 style="display: none;" class="font-weight-bold hourley-${item.id}">${item.title}</h5>
                            <div style="display: none;" class="row mb-3 hourley-${item.id}">
                                <div class="col-3">
                                    Minmum Hours
                                    <input required value="${getRules?getRules.minimum_hour:defaultPrice.minimum_hour}"  min="0" max="12" name="hourly[${index}][minimum_hour]"   class="form-control" />
                                </div>
                                
                                <div class="col-3">
                                    Mile Per Hour
                                    <input required value="${getRules?getRules.minimum_mile_hour:defaultPrice.minimum_mile_hour}"  min="0" name="hourly[${index}][minimum_mile_hour]" type="number" class="form-control"  />
                                </div>
                                <div class="col-3">
                                    Price Per Hour
                                    <input required value="${getRules?getRules.price_per_hour:defaultPrice.price_per_hour}"  min="0" name="hourly[${index}][price_per_hour]" type="number" class="form-control"  />
                                </div>
                                <div class="col-3">
                                    Price Per Extra Mile
                                    <input required value="${getRules?getRules.extra_price_per_mile_hourly:defaultPrice.extra_price_per_mile_hourly}"  min="0" name="hourly[${index}][extra_price_per_mile_hourly]" type="number" class="form-control"  />
                                </div>
                                <input type="text" value="${item.id}" hidden name="hourly[${index}][category_id]">
                            `;

                            $('#hourly_fleets').append(div);

                        })

                        /** point to point **/
                       $('#pointTopoint_fleets').empty();
                       
                       allFleets.forEach((item,index)=>{
                        const getRules = getPriceRules(item.id,1) ?? item.pointToPoint;
                        console.log(getRules.extra_price_per_mile);
                            const div = `
                        <h5 style="display: none;" class="font-weight-bold pointTopoint-${item.id}">${item.title}</h5>
                        <div style="display: none;" class="row mb-3 pointTopoint-${item.id}">
                        <div class="col">
                            Initial Fee
                            <input required value='${getRules?getRules.initial_fee:defaultPrice.initial_fee}' min="0" name="pointToPoint[${index}][initial_fee]" type="number" class="form-control"  />
                        </div>
                        <div class="col">
                            Minimum mile
                            <input required value='${getRules?getRules.minimum_mile:defaultPrice.minimum_mile}' min="0" name="pointToPoint[${index}][minimum_mile]" type="number" class="form-control"  />
                        </div>
                        <div class="col">
                            Extra price per mile
                            <input required value='${getRules?getRules.extra_price_per_mile:defaultPrice.extra_price_per_mile}' min="0" name="pointToPoint[${index}][point_to_point_extra_price_per_mile]" type="number" class="form-control"  />
                        </div>
                        <input type="text" value="${item.id}" hidden name="pointToPoint[${index}][category_id]">
                        
                         </div>
                            `;

                            $('#pointTopoint_fleets').append(div);

                         })

                        /** get selected fleets category **/
                       $('#fleet_category').val(data.selected_fleets_ids).trigger('change');



                       function getPriceRules(category_id,service_id)
                       {
                            let response = null;
                            if(service_id == 2)
                            {
                                data.pricing_rules.forEach((item)=>{
                                    console.log(item.hourly.extra_price_per_mile_hourly)
                                    if(item.category_id == category_id)
                                    {
                                        response = {
                                            'minimum_hour':item.hourly.minimum_hour,
                                            'minimum_mile_hour':item.hourly.minimum_mile_hour,
                                            'price_per_hour':item.hourly.price_per_hour,
                                            'extra_price_per_mile_hourly':item.hourly.extra_price_per_mile_hourly
                                        }
                                       
                                    }
                                })

                                return response;
                            }
                            else
                            {
                                data.pricing_rules.forEach((item)=>{
                                    if(item.category_id == category_id)
                                    {
                                        response = {
                                            'initial_fee':item.pointToPoint.initial_fee,
                                            'minimum_mile':item.pointToPoint.minimum_mile,
                                            'price_per_hour':item.pointToPoint.price_per_hour,
                                            'extra_price_per_mile':item.pointToPoint.extra_price_per_mile
                                        }
                                    }
                                })

                                return response;
                            }
                       }


                    
                    },
                    error:(err)=>{
                        console.log(err)
                    }
                });
            }

            
  
       



    </script>
@endsection