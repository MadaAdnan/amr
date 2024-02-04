<div>
    <strong>Trip #{{$details['trip']->id}}</strong>
    <hr>
    <table class="table table-striped table-sm">
        <tr>
            <td scope="col">Status:</td>
            <td scope="col">{{ $details['trip']->status }}</td>
        </tr>
        <tr>
            <td scope="col">Service type:</td>
            <td scope="col">{{ \App\Models\ServiceType::find($details['trip']->service_type)->service_name }}</td>
        </tr>
        <tr>
            <td scope="col">Pickup date and time:</td>
            <td scope="col">{{ $details['trip']->pick_up_date . '  ' .  $details['trip']->pick_up_time }}</td>
        </tr>
        <tr>
            <td scope="col">Distance:</td>
            <td scope="col">{{ $details['trip']->distance }}</td>
        </tr>
        <tr>
            <td scope="col">Airline:</td>
            <td scope="col">{{ $details['trip']->airline }}</td>
        </tr>
        <tr>
            <td scope="col">Flight Number:</td>
            <td scope="col">{{ $details['trip']->flight_number }}</td>
        </tr>
    </table>
</div>

<div>
    <strong>ROUTE LOCATIONS
    </strong>
    <hr>
    <p>{{ $details['trip']->pick_up_location }}</p>
    <p>{{ $details['trip']->drop_off_location }}</p>
</div>

<div>
    <strong>VEHICLE</strong>
    <hr>
    <table class="table table-striped table-sm">
        <tr>
            <td scope="col">Vehicle name:</td>
            <td scope="col">{{ \App\Models\Vehicle::find($details['trip']->vehicle_id)->vehicle_type }}</td>
        </tr>
    </table>
</div>


<div>
    <strong>CLIENT DETAILS</strong>
    <hr>
    <table class="table table-striped table-sm">
        <tr>
            <td scope="col">First name:</td>
            <td scope="col">{{ $details['customer']->first_name }}</td>
        </tr>
        <tr>
            <td scope="col">Last name:</td>
            <td scope="col">{{ $details['customer']->last_name }}</td>
        </tr>
        <tr>
            <td scope="col">Phone number:</td>
            <td scope="col">{{ $details['customer']->phone }}</td>
        </tr>
    </table>
</div>

