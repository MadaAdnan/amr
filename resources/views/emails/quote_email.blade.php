<div>
    <strong>General</strong>
    <hr>
    <table class="table table-striped table-sm">
        <tr>
            <td scope="col">Service type:</td>
            <td scope="col">{{ $details['service_type'] ?? ''}}</td>
        </tr>
        @if(isset($details['transfer_type']))
        <tr>
            <td scope="col">Transfer type:</td>
            <td scope="col">{{ $details['transfer_type'] }}</td>
        </tr>
        @endif
        <tr>
            <td scope="col">Pickup date and time:</td>
            <td scope="col">{{ $details['pick_up_date'] ?? ''}} {{ $details['pick_up_time'] ?? ''}}</td>
        </tr>
        @if(isset($details['return_date']))
        <tr>
            <td scope="col">Dropoff date and time:</td>
            <td scope="col">{{ $details['return_date'] . ' ' . $details['return_time'] }}</td>
        </tr>
        @endif
        <tr>
            <td scope="col">Duration:</td>
            <td scope="col">{{ $details['duration'] ?? ''}}</td>
        </tr>
        @if(isset($details['distance']))
        <tr>
            <td scope="col">Distance:</td>
            <td scope="col">{{ $details['distance'] }}</td>
        </tr>
        @endif

        <tr>
            <td scope="col">Selected Vehicle:</td>
            <td scope="col">{{ $details['selected_vehicle'] ?? '' }}</td>
        </tr>
        <tr>
            <td scope="col">Extra price:</td>
            <td scope="col">{{ $details['extraPrice']?? '' }}</td>
        </tr>
        <tr>
            <td scope="col">Order total amount:</td>
            <td scope="col">{{ $details['price']?? '' }}</td>
        </tr>
    </table>
</div>

<div>
    <strong>ROUTE LOCATIONS
    </strong>
    <hr>
    <p>{{ $details['pick_up_location']}}</p>
    @if(isset($details['drop_off_location']))<p>{{ $details['drop_off_location'] }}</p>@endif
</div>

<div>
    <strong>VEHICLE</strong>
    <hr>
    <table class="table table-striped table-sm">
        <tr>
            <td scope="col">Vehicle name:</td>
            <td scope="col">{{ $details['vehicle'] ?? ''}}</td>
        </tr>
    </table>
</div>

