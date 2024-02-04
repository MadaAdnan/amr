<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            line-height: 0;
            padding: 0;
            margin: 0;
            direction: ltr;
        }

        body {
            font-family: sans-serif;
            padding: 14px;
        }

        .pl-2 {
            padding-left: 20px;    
        }

        .header {
            font-size: 14px;
            background-color: #000;
            padding: 0 40px;
            box-sizing: border-box;
            color: #fff;
            overflow: hidden;
            position: relative;
            margin-bottom: 5px;
        }

        .header h1 {
            margin: 22px 0 14px 0;
            display: block;
            overflow: hidden;
            line-height: 24px;
        }

        .header img {
            height: 45px;
            width: 90px;
            position: absolute;
            right: 20px;
            top: 30px;
        }

        .header .blocks {
            display: inline-block;
            max-width: 550px;
            width: 550px;
            overflow: hidden;
        }

        .header .blocks .block {
            display: inline-block;
            width: 180px;
            margin-bottom: 14px;
        }

        .header .blocks .block p {
            font-size: 12px;
        }

        .header .blocks .block p,
        .header .blocks .block span {
            line-height: 22px;
        }

        .info {
            padding: 18px 40px;
            overflow: hidden;
            background-color: #f1eff7;
            margin-bottom: 5px;
        }

        .capitalize {
            text-transform: capitalize;
        }

        .info h3 {
            font-size: 16px;
            color: #ae2025;
            border-bottom: 1px solid #ae2025;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }
        
        .info h3,
        .info p,
        .info span {
            line-height: 20px;
        }

        .info div {
            margin-bottom: 6px;
        }

        .info div span:first-child {
            min-width: 150px;
            width: 150px;
            display: inline-block;
        }

        .info span {
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{env('APP_URL')}}/images/lavishLogo.png" alt="">
        <div class="body">
            <h1>Ride assignment</h1>
            <div class="blocks">
                <table border="0">
                    <tr>
                        <td>
                            <div class="block">
                                <p>Passenger Name:</p>
                                <span class="capitalize">{{ $customer->fullname }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="block">
                                <p>Booking Number:</p>
                                <span>#{{ $trip->id }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="block">
                                <p>No. of Passengers:</p>
                                <span>{{ $trip->number_of_passengers }}</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="block">
                                <p>Status:</p>
                                <span>{{ $trip->status }}</span>
                            </div>
                        </td>
                        <td colspan="2">
                            <div class="block">
                                <p>Phone:</p>
                                <span>{{ isset($customer->phone) ? $customer->phone : "No Phone Number" }}</span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="info">
        <h3>Trip Details</h3>
        <div>
            <span>Pickup Date-Time:</span>
            <span>{{ \Carbon\Carbon::parse($trip->pick_up_date)->format("m/d/Y") . ' - ' . \Carbon\Carbon::parse($trip->pick_up_time)->format("h:i A") }}</span>
        </div>
        <div>
            <span>Service Type:</span>
            <span>
                @if( $trip->service_type == 1 ) {{'Point To Point'}}
                @elseif( $trip->service_type == 2 ) {{'Hourly'}}
                @else {{'Airport Transfer'}}
                @endif
            </span>
        </div>
        @if( $trip->distance )
        <div>
            <span>Distance:</span>
            <span>{{ $trip->distance }} Mile</span>
        </div>
        @endif
        <div>
            <span>Duration:</span>
            <span>{{ $trip->duration }} @if( $trip->service_type == 2 ) {{'Hours'}} @endif</span>
        </div>
        @if( $trip->airline )
        <div>
            <span>Airline Name:</span>
            <span>{{ $trip->airline }}</span>
        </div>
        @endif
        @if( $trip->flight_number )
        <div>
            <span>Flight Number:</span>
            <span>{{ $trip->flight_number }}</span>
        </div>
        @endif
    </div>
    <div class="info">
        <h3>Route</h3>
        <div>
            <span>From:</span>
            <span>{{ $trip->pick_up_location }}</span>
        </div>
        @if( $trip->drop_off_location )
        <div>
            <span>To:</span>
            <span>{{ $trip->drop_off_location }}</span>
        </div>
        @endif
        @if( count($stop_locations) > 0 )
        <div>
            <span>Stop Locations</span>
            <span></span>
        </div>
        @foreach( $stop_locations as $location )
        <div class="pl-2">
            <span>Location:</span>
            <span>{{ $location->location }} 
                @if( $location->time )
                    - Time: {{ \Carbon\Carbon::parse($location->time)->format("h:i A") }}
                @endif
            </span>
        </div>
        @endforeach
        @endif
    </div>
    <div class="info">
        <h3>Vehicle</h3>
        <div>
            <span>Category:</span>
            <span>{{ $vehicle->vehicle_type }}</span>
        </div>
        <div>
            <span>No. Of Passengers:</span>
            <span>{{ $vehicle->number_of_passengers }}</span>
        </div>
        <div>
            <span>Extra:</span>
            <span>{{ $extra }}</span>
        </div>
    </div>
    <div class="info">
        <p>
            Allow the customer to modify the itinerary: <b>{{ $trip->modify_itinerary == 1 ? "YES" : "NO" }}</b>
        </p>
    </div>
</body>

</html>