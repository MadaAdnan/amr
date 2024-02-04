@extends('dashboard.layouts.index')


@section('content')
@php
      $status = [
        'Active'=>[
            'name'=>'Active',
            'color'=>'#58f000'
        ],
        'Disabled'=>[
            'name'=>'Unactive',
            'color'=>"#fe3738"
        ]
];
    $isLinked = $data->city_id ? true : false;
@endphp
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
                <li> {{$error}}</li>
             @endforeach
         </div>
         @endif
        <form enctype="multipart/form-data" action="{{ route('dashboard.cities.update',$data->id) }}" id="AddForm" method="post">
            @csrf
            <h4>Edit City</h4>
            <hr>
            <div class="row">
                <div class="col-4 mb-3">
                   <label for="titleInput" class="form-label">States</label>
                    <input disabled type="text" class="form-control" value="{{ $data->states->name }}">
                 </div>
                <div class="col-4 mb-3">
                   <label for="titleInput" class="form-label">Cities</label>
                   <input disabled type="text" class="form-control" value="{{ $data->title }}">
                 </div>
                <div class="col-4 mb-3">
                   <label for="titleInput" class="form-label">Status</label>
                   <select class="form-control" name="status" id="status">
                    <option {{ $data->status == 'Active'?'selected':'' }} value="Active">Active</option>
                    <option {{ $data->status == 'Disabled'?'selected':'' }} value="Disabled">Disabled</option>
                   </select>
                 </div>
                 <div class="col-6 mb-3">
                    <label for="titleInput" class="form-label">Copy City</label>
                    <select {{ $isLinked ? 'disabled':'' }} onchange="getCityInfo()" class="form-control isLinked" name="city_id" id="city_id">
                        <option  value="">Select</option>
                        @foreach ($cities as $item)
                            <option  {{ $data->city_id == $item->id ? 'selected':''}} value="{{ $item->id }}">{{ $item->title }}</option>
                        @endforeach
                    </select>
                  </div>
                 <div class="col-6 mb-3">
                    <label for="titleInput" class="form-label">Link City</label>
                    <select  {{ !$data->city_id ? 'disabled':'' }} onchange="handeIsLinkedStatus(event)" id="linkCity" required class="form-control" name="link_city" id="link_city">
                        <option  disabled value="">Select</option>
                        <option {{ $isLinked ? 'selected':''}}  value="1">Yes</option>
                        <option {{ !$isLinked ? 'selected':''}}  value="0">No</option>
                    </select>
                  </div>

                 <h5>
                    Fleets
                 </h5>
                 <hr>
                 <div class="col-12 mb-3">
                    <label for="titleInput" class="form-label">Select fleet</label>
                    <select  {{ $isLinked ? 'disabled':'' }} onchange="showHideFleets(event)" multiple name="fleet_category[]" id="fleet_category" class="form-control isLinked">
                     @foreach ($fleetCategory as $item)
                         <option {{ in_array($item->id,$selected_fleets)?'selected':'' }} data-state-name="{{ $item->title }}" value="{{ $item->id }}">{{ $item->title }}</option>
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
                        <input  {{ $isLinked ? 'disabled' : '' }} value="{{ $data->daily_from }}" id="daily_from" class="form-control isLinked" type="time" name="daily_from">
                    </div>
                    <div class="col-4">
                        <label for="">To</label>
                        <input  {{ $isLinked ? 'disabled' : '' }} value="{{ $data->daily_to }}" id="daily_to" class="form-control isLinked" type="time" name="daily_to">
                    </div>
                    <div class="col-4">
                        <label for="">Price</label>
                        <input  {{ $isLinked ? 'disabled' : '' }} value="{{ $data->daily_price }}" id="daily_price" name="daily_price" type="number" min="0" class="form-control isLinked" aria-label="Amount (to the nearest dollar)">
                    </div>
                 </div>

                 <h5 class="mt-3">
                    Reserving Time 
                </h5>
                <br/>
                <span class="note mb-2"> Reserving trips within 24 hours from pickup time in ascending order fee. </span>
                 <hr>
                 <div class="row">

                    <div class="col-3">
                        24 - 18:01 Hours
                        <select  name="periodTwentyfour" id="period-24" data-hours="24" data-type="period" class="form-control mb-2 isLinked">
                            <option {{ $periodTwentyFour&&$periodTwentyFour->period == 15 ?'selected':'' }}  value="15">15 min</option>
                            <option {{ $periodTwentyFour&&$periodTwentyFour->period == 30 ?'selected':'' }}  value="30">30 min</option>
                            <option {{ $periodTwentyFour&&$periodTwentyFour->period == 45 ?'selected':'' }}  value="45">45 min</option>
                            <option {{ $periodTwentyFour&&$periodTwentyFour->period == 60 ?'selected':'' }}  value="60">60 min</option>
                        </select>
                        <input {{ $isLinked ? 'disabled' : '' }} value="{{ $periodTwentyFour&&$periodTwentyFour->charge?$periodTwentyFour->charge:1 }}" name="chargeTwentyfour" required id="charge-24" data-hours="24" min="0" max="100"  data-type="charge" type="number"  placeholder="Charge %" class="form-control isLinked">
                    </div>
                    <div class="col-3">
                        18 - 12:01 Hours
                        <select name="periodNineteen" id="period-19" data-hours="18" data-type="period" class="form-control mb-2 isLinked">
                            <option {{ $periodNineteen&&$periodNineteen->period == 15 ?'selected':'' }}  value="15">15 min</option>
                            <option {{ $periodNineteen&&$periodNineteen->period == 30 ?'selected':'' }}  value="30">30 min</option>
                            <option {{ $periodNineteen&&$periodNineteen->period == 45 ?'selected':'' }}  value="45">45 min</option>
                            <option {{ $periodNineteen&&$periodNineteen->period == 60 ?'selected':'' }}  value="60">60 min</option>
                        </select>
                        <input {{ $isLinked ? 'disabled' : '' }} value="{{ $periodNineteen&&$periodNineteen->charge?$periodNineteen->charge:1 }}" required name="chargeNineteen" id="charge-19" data-hours="18" min="0" max="100"  data-type="charge" type="number"  placeholder="Charge %" class="form-control isLinked">
                    </div>
                    <div class="col-3">
                        12 - 6:01 Hours
                        <select id="periodTwelve" name="periodTwelve" data-hours="24" data-type="period" class="form-control mb-2 isLinked">
                            <option {{ $periodTwelve&&$periodTwelve->period == 15 ?'selected':'' }}  value="15">15 min</option>
                            <option {{ $periodTwelve&&$periodTwelve->period == 30 ?'selected':'' }}  value="30">30 min</option>
                            <option {{ $periodTwelve&&$periodTwelve->period == 45 ?'selected':'' }}  value="45">45 min</option>
                            <option {{ $periodTwelve&&$periodTwelve->period == 60 ?'selected':'' }}  value="60">60 min</option>
                        </select>
                        <input {{ $isLinked ? 'disabled' : '' }} value="{{ $periodTwelve&&$periodTwelve->charge?$periodTwelve->charge:1 }}" required id="charge-12" name="chargeTwelve" data-hours="24" min="0" max="100"  data-type="charge" type="number"  placeholder="Charge %" class="form-control isLinked">
                    </div>
                    <div class="col-3">
                        6 - 1 Hours
                        <select name="periodSix" id="period-6" data-type="period" class="form-control mb-2 isLinked">
                            <option {{ $periodSix&&$periodSix->period == 15 ?'selected':'' }}  value="15">15 min</option>
                            <option {{ $periodSix&&$periodSix->period == 30 ?'selected':'' }}  value="30">30 min</option>
                            <option {{ $periodSix&&$periodSix->period == 45 ?'selected':'' }}  value="45">45 min</option>
                            <option {{ $periodSix&&$periodSix->period == 60 ?'selected':'' }}  value="60">60 min</option>
                        </select>
                        <input id="charge-6" {{ $isLinked ? 'disabled' : '' }} value="{{ $periodSix&&$periodSix->charge?$periodSix->charge:1 }}" required name="chargeSix" min="0" max="100"  data-type="charge" type="number"  placeholder="Charge %" class="form-control isLinked">
                    </div>

                 </div>
                


                <h5 class="mt-3">Pricing Rules</h5>
                <hr>
                <label class="font-weight-bold mb-4" >Hourly</label>
                <div id="hourly_fleets">

                @foreach ($fleetCategory as $index => $item)
                    
                    <h5 style="display: none;" class="font-weight-bold hourley-{{ $item->id }}">{{ $item->title }}</h5>


                    <div  id="hourly-content-container-{{ $item->id }}" style="display: none;" class="row mb-3 hourley-{{ $item->id }}">
                        <div class="col">
                            Minmum Hours
                            <input max="12" min="1" {{ $isLinked ? 'disabled' : '' }} value="{{ array_key_exists('minimum_hour',$item->hourly)?$item->hourly['minimum_hour']:'' }}"  name="hourly[{{ $index }}][minimum_hour]" type="number" class="form-control isLinked" id="minmum_hour-{{ $item->id }}" />
                        </div>
                       
                        <div class="col">
                            Mile Per Hour
                            <input {{ $isLinked ? 'disabled' : '' }} value="{{  array_key_exists('minimum_mile_hour',$item->hourly)?$item->hourly['minimum_mile_hour']:'' }}"  name="hourly[{{ $index }}][minimum_mile_hour]" type="number" class="form-control isLinked" id="minimum_mile_hour-{{ $item->id }}" />
                        </div>
                        <div class="col">
                            Price Per Hour
                            <input {{ $isLinked ? 'disabled' : '' }} value="{{ array_key_exists('price_per_hour',$item->hourly)?$item->hourly['price_per_hour']:'' }}"  name="hourly[{{ $index }}][price_per_hour]" type="number" class="form-control isLinked" id="price_per_hour-{{ $item->id }}" />
                        </div>
                        <div class="col">
                            Price Per Extra Mile
                            <input {{ $isLinked ? 'disabled' : '' }} value="{{ array_key_exists('extra_price_per_mile_hourly',$item->hourly)?$item->hourly['extra_price_per_mile_hourly']:'' }}"  name="hourly[{{ $index }}][extra_price_per_mile_hourly]" type="number" class="form-control isLinked" id="extra_price_per_mile_hourly-{{ $item->id }}" />
                        </div>
                        {{-- <div class="col">
                            Price Per Extra Min Ex
                            <input {{ $isLinked ? 'disabled' : '' }} value="{{ $getPrices?$getPrices?->extra_price_per_mile_hourly:$defaultPrice->extra_price_per_mile_hourly }}"  name="hourly[{{ $index }}][extra_price_per_mile_hourly]" type="number" class="form-control" id="extra_price_per_mile" />
                        </div> --}}
                        <input type="hidden" {{ $isLinked ? 'disabled' : '' }} value="{{ $item->id }}" name="hourly[{{ $index }}][category_id]" class="isLinked">
                        <input type="hidden" {{ $isLinked ? 'disabled' : '' }} value="2" name="hourly[{{ $index }}][service_id]" class="isLinked">
                        <input type="hidden" {{ $isLinked ? 'disabled' : '' }} value="{{ $item->hourly?$item->hourly['id']:0 }}" name="hourly[{{ $index }}][rules_id]" class="isLinked">
                    </div>

                @endforeach
                </div>
                <hr>
                <label class="font-weight-bold" >Point to point</label>
                <span class="mb-4">Note:This will include the round trip X2</span>
                <div id="pointTopoint_fleets">

                @foreach ($fleetCategory as $index => $item)
                <h5 style="display: none;" class="font-weight-bold pointTopoint-{{ $item->id }}">{{ $item->title }}</h5>

                    <div id="point-to-point-container-{{ $item->id }}" style="display: none;" class="row mb-3 pointTopoint-{{ $item->id }}">
                        <div class="col">
                            Initial Fee
                            <input id="initial_fee-{{ $item->id }}" {{ $isLinked ? 'disabled' : '' }} value='{{ array_key_exists('initial_fee',$item->pointToPoint)?$item->pointToPoint['initial_fee']:'' }}' name="pointToPoint[{{ $index }}][initial_fee]" type="number" class="form-control isLinked"  />
                        </div>
                        <div class="col">
                            Minimum mile
                            <input id="minimum-mile-{{ $item->id }}" {{ $isLinked ? 'disabled' : '' }} value='{{ array_key_exists('minimum_mile',$item->pointToPoint)?$item->pointToPoint['minimum_mile']:''}}' name="pointToPoint[{{ $index }}][minimum_mile]" type="number" class="form-control isLinked"  />
                        </div>
                        <div class="col">
                            Extra price per mile
                            <input id="extra-price-per-mile-{{ $item->id }}" {{ $isLinked ? 'disabled' : '' }} value='{{ array_key_exists('extra_price_per_mile',$item->pointToPoint)?$item->pointToPoint['extra_price_per_mile']:'' }}' name="pointToPoint[{{ $index }}][point_to_point_extra_price_per_mile]" type="number" class="form-control isLinked"  />
                        </div>
                        <input class="isLinked" type="hidden" {{ $isLinked ? 'disabled' : '' }} value="{{ $item->id }}" name="pointToPoint[{{ $index }}][category_id]">
                        <input class="isLinked" type="hidden" {{ $isLinked ? 'disabled' : '' }} value="1" name="pointToPoint[{{ $index }}][service_id]">
                        <input class="isLinked" type="hidden" {{ $isLinked ? 'disabled' : '' }} value="{{  $item->pointToPoint?$item->pointToPoint['id']:0 }}" name="pointToPoint[{{ $index }}][rules_id]">
                    </div>
                @endforeach
                </div>

            @include('dashboard.cities.includes.events')
            
            @include('dashboard.cities.includes.linked_cities')
             
              <div class="text-right">
                <a href="{{ route("dashboard.cities.index") }}" class="btn btn-secondary">Back</a>
                  <button id="createButton" type="submit" class="btn btn-primary">Update</button>
              </div>
            
    
        </form>        
    </div>    
   
@endsection

@section('scripts')
    <script>
            const selectStateSelect2 = $('#states').select2();
            const selectCitySelect2 = $('#citySelect2').select2();
            const citySelect = document.getElementById('citySelect2');
            const createButton = document.getElementById('createButton');
            const stateSelect = document.getElementById('states');
            const isLinkedSelect = document.getElementById('linkCity');

            const current_id = '{{ $data->id }}';
            let isLinked = '{{ $isLinked }}';

            $('#city_id').select2();
            $('#linkCity').select2();

            $('#fleet_category').select2();
            

            selectStateSelect2.val(5);

            const getSelectedIds = @json($selected_fleets);
            const allFleets = @json($fleetCategory);
            // console.log(getSelectedIds);

            getSelectedIds.forEach(item=>{
                $('.pointTopoint-'+item).show();
                $('.hourley-'+item).show();
            })

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
                    },
                    city_id:{
                        required:{
                            depends:function(e){
                                return $('#linkCity').val() == 1?true:false;
                            }
                        }
                    }
                },
                submitHandler:(form)=>{
                    /** Show loading pop-up **/
                    Swal.fire({
                        title: 'Submitting data...',
                        timerProgressBar: true,
                        allowOutsideClick:true,
                        showCancelButton:false,
                        didOpen: () => {
                            Swal.showLoading()
                           
                        },
                        willClose: () => {
                            // clearInterval(timerInterval)
                        }
                        }).then((result) => {
                       
                    })

                    //* Remove the disabled attribute from the inputs *//
                    var inputElements = document.querySelectorAll('input');
                    inputElements.forEach(function(inputElement) {
                        inputElement.removeAttribute('disabled');
                    });
                    var inputElements = document.querySelectorAll('select');
                    inputElements.forEach(function(inputElement) {
                        inputElement.removeAttribute('disabled');
                    });

                    var formData = new FormData(form);
                    var xhr = new XMLHttpRequest();
                    let request = "{{ route('dashboard.cities.update',':id') }}";
                    request = request.replace(":id",current_id)
                    xhr.open("POST", request);
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4) {
                            if (xhr.status === 200) {
                                var jsonData = JSON.parse(xhr.response);
                                const redireact_url = jsonData.data.url;
                                Swal.close();
                                window.location.href = redireact_url;
                            } else {
                                console.error("Form submission failed: " + xhr.statusText);
                            }
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
                        /** return to defualt price */
                        
                        var inputsInDiv = document.querySelectorAll('#hourly-content-container-'+item.id+' input');
                        inputsInDiv.forEach(function(input) {
                             let defualtPriceing = item.default_pricing;

                            if('minmum_hour-minmum_hour-'+item.id == 'minmum_hour-'+input.id)
                            {
                                 const inputId = '#'+input.id;
                                 $(inputId).val(defualtPriceing.minimum_hour);
                            }

                            if('minimum_mile_hour-'+item.id == input.id)
                            {
                                 const inputId = '#'+input.id;
                                 $(inputId).val(defualtPriceing.minimum_mile_hour);
                            }

                            if('price_per_hour-'+item.id == input.id)
                            {
                                 const inputId = '#'+input.id;
                                 $(inputId).val(defualtPriceing.price_per_hour);
                            }
                            if('extra_price_per_mile_hourly-'+item.id == input.id)
                            {
                                 const inputId = '#'+input.id;
                                 $(inputId).val(defualtPriceing.extra_price_per_mile_hourly);
                            }
                            
                        });

                        var inputsInDiv = document.querySelectorAll('#point-to-point-container-'+item.id+' input');


                        inputsInDiv.forEach(function(input) {
                             let defualtPriceingPointTopoint = item.default_pricing;

                            if('initial_fee-'+item.id == input.id)
                            {
                                 const inputId = '#'+input.id;
                                 $(inputId).val(defualtPriceingPointTopoint.initial_fee);
                            }

                            if('minimum-mile-'+item.id == input.id)
                            {
                                 const inputId = '#'+input.id;
                                 $(inputId).val(defualtPriceingPointTopoint.minimum_mile);
                            }

                            if('extra-price-per-mile-'+item.id == input.id)
                            {
                                 const inputId = '#'+input.id;
                                 $(inputId).val(defualtPriceingPointTopoint.extra_price_per_mile);
                            }
                            
                            
                        });
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
                isLinkedSelect.disabled = false;
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
                       const defaultPrice = {};
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
                            <div id="hourly-content-container-${item.id}" style="display: none;" class="row mb-3 hourley-${item.id}">
                                <div class="col-3">
                                    Minmum Hours
                                    <input required value="${getRules.minimum_hour}"  min="0" max="12" name="hourly[${index}][minimum_hour]"  id="minmum_hour-${item.id}" class="form-control" />
                                </div>
                                
                                <div class="col-3">
                                    Mile Per Hour
                                    <input required value="${getRules.minimum_mile_hour}"  min="0" name="hourly[${index}][minimum_mile_hour]" type="number" class="form-control" id="minimum_mile_hour-${item.id}" />
                                </div>
                                <div class="col-3">
                                    Price Per Hour
                                    <input required value="${getRules.price_per_hour}"  min="0" name="hourly[${index}][price_per_hour]" type="number" class="form-control" id="price_per_hour-${item.id}" />
                                </div>
                                <div class="col-3">
                                    Price Per Extra Mile ( ${getRules.extra_price_per_mile_hourly} )
                                    <input required value="${getRules.extra_price_per_mile_hourly}"  min="0" name="hourly[${index}][extra_price_per_mile_hourly]" type="number" class="form-control" id="2extra_price_per_mile_hourly-${item.id}" />
                                </div>
                                <input type="text" value="${item.id}" hidden name="hourly[${index}][category_id]">
                                <input type="text" value="${item.rule_id}" hidden name="hourly[${index}][rule_id]">
                            </div>
                            `;

                            $('#hourly_fleets').append(div);

                        })

                        /** point to point **/
                        $('#pointTopoint_fleets').empty();
                       allFleets.forEach((item,index)=>{
                        const getRules = getPriceRules(item.id,1) ?? item.pointToPoint;
                            const div = `
                        <h5 style="display: none;" class="font-weight-bold pointTopoint-${item.id}">${item.title}</h5>
                        <div id="point-to-point-container-${item.id}" style="display: none;" class="row mb-3 pointTopoint-${item.id}">
                        <div style="display: none;" class="row mb-3 pointTopoint-${item.id}">
                            <div class="col">
                                Initial Fee
                                <input required value='${getRules?getRules.initial_fee:defaultPrice.initial_fee}' min="0" name="pointToPoint[${index}][initial_fee]" type="number" class="form-control" id="initial_fee-${item.id}" />
                            </div>
                            <div class="col">
                                Minimum mile
                                <input required value='${getRules?getRules.minimum_mile:defaultPrice.minimum_mile}' min="0" name="pointToPoint[${index}][minimum_mile]" type="number" class="form-control" id="minimum-mile-${item.id}" />
                            </div>
                            <div class="col">
                                Extra price per mile ( ${getRules.extra_price_per_mile} )
                                <input required 
                                value='${getRules ? getRules.extra_price_per_mile : defaultPrice.extra_price_per_mile}'
                                  min="0"
                                  name="pointToPoint[${index}][point_to_point_extra_price_per_mile]"
                                  type="number"
                                  class="form-control"
                                  id="2extra-price-per-mile-${item.id}" 
                                  />
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
            
            function handeIsLinkedStatus(event)
            {
                const status = event.target.value;
                var elements = document.querySelectorAll('.isLinked');

                elements.forEach(function(element) {
                    element.disabled = status == 1 ? true : false;
                });


            }

            
    </script>
@endsection