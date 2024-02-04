
<div>
    <strong>General</strong>
    <hr>
    <table class="table table-striped table-sm">
        <tr>
            <td scope="col">Status:</td>
            <td scope="col">{{ $details['trip']->status }}</td>
        </tr>
        <tr>
            <td scope="col">Service type:</td>
            <td scope="col">{{ $details['trip']->service_type }}</td>
        </tr>
        <tr>
            <td scope="col">Pickup date and time:</td>
            <td scope="col">{{ $details['trip']->pick_up_date . '  ' .  $details['trip']->pick_up_time }}</td>
        </tr>
        <tr>
            <td scope="col">Order total amount:</td>
            <td scope="col">{{ $details['trip']->price }}</td>
        </tr>
        <tr>
            <td scope="col">Distance:</td>
            <td scope="col">{{ $details['trip']->distance }}</td>
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
            <td scope="col">{{ $details['trip']->vehicle_id }}</td>
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
            <td scope="col">E-mail address:</td>
            <td scope="col">{{ $details['customer']->email }}</td>
        </tr>
        <tr>
            <td scope="col">Phone number:</td>
            <td scope="col">{{ $details['customer']->phone }}</td>
        </tr>
        <tr>
            <td scope="col">Airline:</td>
            <td scope="col">{{ $details['customer']->airline }}</td>
        </tr>
        <tr>
            <td scope="col">Flight Number:</td>
            <td scope="col">{{ $details['customer']->flight_number }}</td>
        </tr>
    </table>
</div>

<div>
    <strong>BILLING ADDRESS:</strong>
    <hr>
    <table class="table table-striped table-sm">

        @if(isset($details['bill']->company_name))
        <tr>
            <td scope="col">Company name:</td>
            <td scope="col">{{ $details['bill']->company_name }}</td>
        </tr>
        @endif
        <tr>
            <td scope="col">Country:</td>
            <td scope="col">{{ $details['bill']->country }}</td>
        </tr>
        <tr>
            <td scope="col">State:</td>
            <td scope="col">{{ $details['bill']->state }}</td>
        </tr>
        <tr>
            <td scope="col">City:</td>
            <td scope="col">{{ $details['bill']->city }}</td>
        </tr>
        <tr>
            <td scope="col">Street:</td>
            <td scope="col">{{ $details['bill']->street }}</td>
        </tr>
        <tr>
            <td scope="col">Postal code:</td>
            <td scope="col">{{ $details['bill']->postel_code }}</td>
        </tr>
    </table>
</div>
