<div class="row">
    <div class="col-6">
        <label for="">Date
            <strong class="text-danger">
                *
            </strong>
        </label>
        <input onchange="getPriceAccordingToFleet()" type="text" name="start_date" id="start_date"
            class="form-control datepicker"
            value="{{ isset($reservation->pick_up_date) ? $reservation->pick_up_date->format('Y-m-d') : '' }}">
        <div id="start_date-error" class="error-container">
            <!-- Error message will be inserted here -->
        </div>
    </div>
    <div class=" col-md-6 mb-2 mt-2">
        <label for="time">Time
            <strong class="text-danger">
                *
            </strong>
        </label>

        <div class="input-group date" id="start_time">
            <input onchange="getPriceAccordingToFleet()" id="pickUpTime" name="start_time" type="time"
                class="form-control timeInput"
                value="{{ isset($reservation) && $reservation->pick_up_time
                    ? Carbon\Carbon::parse($reservation->pick_up_time)->format('H:i')
                    : '' }}">
            <span class="input-group-addon"><i class="fa fa-clock-o" aria-hidden="true"></i></span>
        </div>
        <div id="pickUpTime-error" class="error-container">
            <!-- Error message will be inserted here -->
        </div>
    </div>

    <div id="pointTpoint" class="mt-2">
        @include('dashboard.reservations.types.pointTopoint')
    </div>


    @if ($pageType == 'edit')
        <div class="col-6  mb-2 mt-2">
            <label for="">Status
                <strong class="text-danger">
                    *
                </strong>
            </label>
            <select name="status" id="status" class="form-control ">
                <option {{ $reservation->status == '' ? 'selected' : '' }} value="">All
                </option>
                <option {{ $reservation->status == 'pending' ? 'selected' : '' }} value="pending">
                    Pending
                </option>
                <option {{ $reservation->status == 'accepted' ? 'selected' : '' }} value="accepted">
                    Accepted
                </option>
                <option {{ $reservation->status == 'assigned' ? 'selected' : '' }} value="assigned">Assigned
                </option>
                <option {{ $reservation->status == 'on the way' ? 'selected' : '' }} value="on the way">On The
                    way
                </option>
                <option {{ $reservation->status == 'passenger on board' ? 'selected' : '' }} value="passenger on board">
                    Passenger On Board
                </option>
                <option {{ $reservation->status == 'completed' ? 'selected' : '' }} value="completed">
                    Completed</option>
                <option {{ $reservation->status == 'arrived at the pickup location' ? 'selected' : '' }}
                    value="arrived at the pickup location">arrived at the pickup location</option>
                <option {{ $reservation->status == 'canceled' ? 'selected' : '' }} value="canceled">Canceled
                </option>
                <option {{ $reservation->status == 'late canceled' ? 'selected' : '' }} value="late canceled">Late
                    Canceled
                </option>
                <option {{ $reservation->status == 'no show' ? 'selected' : '' }} value="no show">No Show

                </option>



            </select>
        </div>
    @endif

    <div class="col-6  mb-2 mt-2">
        <label for="">Airline Name</label>
        <select class="form-control select2" name="airline_id" id="airlines">
            <option value="">Defualt</option>
            @forelse($airlines as $item)
                <option
                    value="{{ $item->id }}"{{ isset($reservation) && $item->id == $reservation->airline_id ? 'selected' : '' }}>
                    {{ $item->name }}</option>
            @empty
                <option>No airlines</option>
            @endforelse
        </select>
    </div>
    <div class="col-6  mb-2 mt-2">
        <label for="">Flight Number</label>
        <input min="0" maxlength="4" type="number" class="form-control" name="flight_number"
            value="{{ isset($reservation->flight_number) ? $reservation->flight_number : '----' }}">
    </div>


    <div class="col-4  mb-2 mt-2">
        <label for="">Fleet Category
            <strong class="text-danger">
                *
            </strong>
        </label>
        <select name="fleet_category" id="fleet_category" onchange="getPriceAccordingToFleet()"
            class="form-control select2">
            <option value="">Select</option>
            @foreach ($fleetCategories as $item)
                <option value="{{ $item->id }}" @if (isset($reservation->category_id) && $item->id == $reservation->category_id) selected @endif>
                    {{ $item->title }}</option>
            @endforeach
        </select>

        <div id="fleet_category-error" class="error-container">
            <!-- Error message will be inserted here -->
        </div>
    </div>
    <div class="col-4  mb-2 mt-2">
        <label for="">Fleet</label>
        <select id="fleet" name="fleet" disabled class="form-control select2">
            <option>Select</option>
        </select>
    </div>



    <div class="col-4  mb-2 mt-2">
        <label for="">Chauffeur</label>
        <select id="chaffeur" name="chaffeur" class="form-control select2">
            <option value="">Select</option>
            @foreach ($drivers as $item)
                <option
                    value="{{ $item->id }}"{{ isset($reservation->driver_id) && $reservation->driver_id == $item->id ? 'selected' : '' }}>
                    {{ $item->full_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-6  mb-2 mt-2 ">
        <label for="">Live pricing</label>
        <input id="live_pricing" type="number" name="price" class="form-control" disabled min="1" />
        <div>
            <input type="checkbox" id="enablePrice" class="mt-2 ms-2" name="edit_price"
                @if (isset($reservation) && $reservation->edit_price) checked @endif>
            <small class="ms-2 " style="font-size: 9pt">Edit price</small>
        </div>

    </div>
    <div class="col-6 mb-2 mt-2">
        <label for="miles">Miles</label>
        <input readonly="readonly" min="0" type="number" id="miles" class="form-control"
            name="miles" inputmode="numeric"
            @if (isset($pageType) && $pageType == 'edit') value ={{ $reservation->mile }} @endif />
    </div>
    <div class="col-6">
        <label for="miles">Pickup Sign
        </label>
        <input id="pickup_sign" class="form-control" name="pickup_sign"
            @if (isset($pageType) && $pageType == 'edit') value ="{{ $reservation->pickup_sign }}" @endif />
    </div>

    <div class="col-6  mb-2 mt-2">
        <label for="">Coupon</label>
        <select onchange="getPriceAccordingToFleet()" id="coupon_code" name="coupon_code"
            class="form-control select2">
            <option value="">Select</option>
            @foreach ($coupons as $item)
                <option value="{{ $item->id }}"
                    {{ isset($reservation->coupon_id) && $item->id == $reservation->coupon_id ? 'selected' : '' }}>
                    {{ $item->coupon_code }} </option>
            @endforeach
        </select>
    </div>

    <div class="col-6  mb-2 mt-2">
        <label for="miles">Confirmation</label>

        <label for="emailConfirmation">Email Confirmation</label>
        <input type="text" id="emailConfirmation" class="form-control" name="email_for_confirmation"
            placeholder='Add an email to receive the confirmation message'
            value="{{ isset($reservation->email_for_confirmation) ? $reservation->email_for_confirmation : '' }}">
    </div>
    <div class="col-6 mb-2 mt-2" style="padding-top:30px">
        <div class="form-check">
            <input type="checkbox" id="sendEmailConfirmation" class="form-check-input" name="sendEmailConfirmation"
                value="1">
            <label class="form-check-label" for="sendEmailConfirmation">Send Email Confirmation</label>
        </div>
    </div>

    <div class="modal-body ">
        <label for="comment">Comment:</label>
        <textarea class="form-control" id="comment" maxlength="223" rows="5" name="comment">{!! isset($reservation->comment) ? $reservation->comment : '' !!}</textarea>
    </div>
  

</div>
