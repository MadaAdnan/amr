<div class="row">

    <div id="pick-up-location" class="col-6 mb-2">
        <div class="mt-2 justify-content-between d-flex">
            <label class="location-titles" for="">Pick up location <strong id="pickUpValidation" class="text-danger">*</strong></label>
            {{-- <button disabled id="returnAsPickUpButton" type="button" onclick="returnAsPickUp()"
                class="btn btn-primary">Return as pick up</button> --}}
                @if ($pageType == 'edit' && !(isset($reservation->parent) || isset($reservation->child)))
                
                <div class="form-check form-switch returnAsPickUpSection">
                    <input onclick="returnAsPickUp()" disabled class="form-check-input" type="checkbox" role="switch" id="returnAsPickUpButton" />
                    <label class="form-check-label" for="returnAsPickUpButton">Return as Pick up</label>
                </div> 
                @elseif($pageType == 'create')  
                <div class="form-check form-switch returnAsPickUpSection">
                    <input onclick="returnAsPickUp()" disabled class="form-check-input" type="checkbox" role="switch" id="returnAsPickUpButton" />
                    <label class="form-check-label" for="returnAsPickUpButton">Return as Pick up</label>
                </div>                            
                @endif
        </div>
        <div class="row">
            <div class="col-10">
                
                <div class="custom-dropdown">
                    <input name="pick_up_location" class="form-control" type="text" id="pickUploaction" placeholder="Enter the place address" value='{{isset($reservation)?$reservation->pick_up_location:''}}'/>
                
                    <ul class="dropdown-list" id="dropdownList">
    
                    </ul>
                   
                </div>
    
            </div>
            <div class="col-2 text-right">
                <button onclick="getLocation('pickUp')" type="button" class="btn btn-primary"
                    id="dropdownButton"><span class="loader" id="loader"></span>Get</button>
            </div>
        </div>
    </div>
    <div class="col-6 mb-2 drop-off-location">
        <label class="location-titles" for="">Drop Off location  <strong id="dropOffValidation" class="text-danger">*</strong>
        </label>
        <div class="row">
            <div class="col-10">
                <div class="custom-dropdown">
                    <input name="drop_off_location" class="form-control" type="text" id="drop-off-location" placeholder="Enter the place address" value='{{isset($reservation)?$reservation->drop_off_location	:''}}' />
                    <ul class="dropdown-list" id="dropdownList-dropoff-location">
    
                    </ul>
                </div>
            </div>
            <div class="col-2 text-right">
                <button onclick="getLocation('dropOff')" type="button" class="btn btn-primary"
                    id="dropdownButton-dropoff"><span class="loader" id="loader"></span>Get</button>
            </div>
        </div>
        <strong id="mileSpan" class="text-danger"></strong>
    </div>
    
    <div class="col-6">
        <label for="city">City</label>
       <select  onchange="getPriceAccordingToFleet()" name="city" id="city" class="form-control selectpicker" data-live-search="true">
        <option value="">Select City</option>
        @foreach ($cities as $item)
        <option title="{{ $item->title }}" value="{{$item->id}}" 
            @if (isset($pageType) && $pageType == 'edit')
            @if($item->id == $reservation->city_id) selected @endif
            @endif
            > {{$item->title}} </option>
        @endforeach
        </select>
    </div>
    <div class="col-6">
        <label for="companyBookingNumber">Company Booking Number</label>
        <input  id="companyBookingNumber" class="form-control" name="company_booking_number" 
        @if (isset($pageType) && $pageType == 'edit')
        value="{{$reservation->company_booking_number}}"
        @endif
            />
    </div>
    <div class="col-6">
        <label for="company_id">Company</label>
       <select  name="company_id" id="company_id" class="form-control selectpicker" data-live-search="true">
           <option value=""> Select </option>
        @foreach ($companies as $item)
        <option value="{{$item->id}}"
            @if (isset($pageType) && $pageType == 'edit')
            @if($item->id == $reservation->company_id) selected @endif
            @endif
            > {{$item->company_name}} </option>
        @endforeach
        </select>
    </div>
    <div class="col-6">
        <label for="getReservingTime">Get Reserving Time</label>
        <select onchange="getPriceAccordingToFleet()" class="form-control" name="get_reserving_time" id="getReservingTime" disabled>
            <option value="0" >No</option>
            <option value="1">Yes</option>
        </select>
    </div>
    {{-- <div class="col-6 mt-2">
        <label for="takeDefualtPricing">Take Defualt Pricing</label>
        <select onchange="getPriceAccordingToFleet()" class="form-control" name="defualt_price" id="defualtPrice">
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
    </div> --}}
</div>