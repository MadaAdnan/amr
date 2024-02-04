<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Include Bootstrap CSS from CDN -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

    <!-- Include Bootstrap JavaScript from CDN (Popper.js is required for certain components) -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/js/bootstrap.bundle.min.js"></script> --}}

    <title>Reservation Confirmation</title>


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
            background-color: #ffffff;
            padding: 25px;
            box-sizing: border-box;
            color: #262221;
            overflow: hidden;
            position: relative;
        }

        .header h1 {
            margin: 22px 0 14px 0;
            display: block;
            overflow: hidden;
            line-height: 24px;
        }

        .header img {
            height: 130px;
            width: 130px;
            position: absolute;
            right: 35px;
            top: 15px;
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
            font-size: 14px;
        }

        .header .blocks .block p,
        .header .blocks .block span {
            /* line-height: 22px; */
            font-size: 14px;

        }

        .info {
            padding: 4px 40px;
            overflow: hidden;
            background-color: #ffffff;
            margin-bottom: 1px;
        }

        .capitalize {
            text-transform: capitalize;
        }

        .info h3 {
            font-size: 16px;
            color: #ae2227;
            border-bottom: 1px solid #ae2227;
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

        .footer {
            height: 105px;
            width: 100%;
            background-color: #ffffff;
            padding: 5px 40px;
            color: #262221;
            display: block;
        }

        .footer table {
            width: 100%;
        }

        .footer h2 {
            margin: 0 0 14px 0;
            line-height: 42px;
            font-size: 20px;
        }

        .footer img {
            width: 30px;
            height: 30px;
        }

        .footer a {
            display: inline-block;
            margin: 0 0px;
        }

        .footer span {
            display: inline-block;
            line-height: 24px;
            margin-right: 12px;
        }

        .footer .links {
            position: relative;
            width: 100%;
        }

        .footer .terms {
            position: absolute;
            right: 40px;
            top: 10px;
            height: 20px;
            width: 200px;
            display: inline-block;
            text-decoration: none;
            font-weight: bold;

        }

        .footer .terms span {
            font-size: 13px !important;
            color: #262221;
            /* text-align: center; */
            border-bottom: 1px solid #fff;
            /* display: block; */
        }

        span a {
            color: #ae2226;
            font-weight: bold;
            text-decoration: none;
        }

        .footer .info span {
            margin-right: 100px;
        }


        /* .footer .terms img {
            height: 20px;
            width: 180px;
            border-bottom: 1px solid #fff;
            display: block;
        } */
    </style>
</head>

<body>
    <div class="header">

        <img src="{{ asset('images/LR Official Logo.png') }}" alt="">
        <h1 style="line-height: 28px;">Reservation Confirmation #{{ $trip->id }}</h1>
        <div class="blocks" style="line-height: 25px;">

        </div>
    </div>


    <div class="info ">
        <div>
            <h3>Customer Details</h3>

            <div>
                <span style="display: inline-block; font-weight: bold;">Passenger Name:</span>
                <span style="display: inline-block;">{{ $customer->fullname }}</span>
            </div>
            <div>
                <span style="display: inline-block; font-weight: bold;">Phone:</span>
                <span style="display: inline-block;">{{ isset($customer->phone) ? $customer->phone : 'N/A' }}</span>
            </div>
            <div>
                <span style="display: inline-block; font-weight: bold;">E-mail:</span>
                <span style="display: inline-block;">{{ $customer->email ? $customer->email : 'N/A' }}</span>
            </div>
        </div>
    </div>
    <div class="info ">

        <h3>Trip Details</h3>
        @if (isset($trip->companies))
            <div>
                <span style="display: inline-block;font-weight: bold;">Company:</span>


                <span style="display: inline-block;">{{ $trip->companies->company_name }} </span>
            </div>
        @endif
        @if ($trip->company_booking_number)
            <div>
                <span style="display: inline-block; font-weight: bold;">Company Booking #:</span>


                <span style="display: inline-block;">{{ $trip->company_booking_number }} </span>
            </div>
        @endif

        <div>
            <div>
                <span style="display: inline-block;font-weight: bold;">Service Type:</span>
                <span style="display: inline-block;">
                    @if (isset($trip->service_type) && $trip->service_type == 1)
                        {{ ' Point To Point ' }}
                    @elseif(isset($trip->service_type) && $trip->service_type == 2)
                        {{ ' Hourly ' }}
                    @else
                        {{ ' Airport Transfer ' }}
                    @endif
                </span>
            </div>
            <div>
                <span style="border: 1px;display: inline-block; font-weight: bold;">Pickup Date & time:</span>
                <span style="border: 1px;display: inline-block; ">
                    {{ \Carbon\Carbon::parse($trip->pick_up_date)->format('D, m-d-Y') }}
                    - {{ \Carbon\Carbon::parse($trip->pick_up_time)->format('h:i A') }}</span>
            </div>

        </div>

        @if ($trip->distance)
            <div>
                <span style="display: inline-block;font-weight: bold;">Distance:</span>
                @if (isset($trip->transfer_type) && $trip->transfer_type == 'Round')
                    <span style="display: inline-block;">{{ $trip->mile }} Miles</span>
                @else
                    <span style="display: inline-block;">{{ $trip->distance }} Miles</span>
                @endif
            </div>
        @endif
        @if (isset($trip->service_type) && $trip->service_type == 2)
            <div>
                <span style="display: inline-block; font-weight: bold;">Duration:</span>
                <span style="display: inline-block;">{{ $trip->duration }}
                    {{ ' Hours ' }}

                </span>
            </div>
        @endif
        <div>
            <span style="display: inline-block; font-weight: bold;">Trip Status:</span>
            <span style="display: inline-block;">{{ $trip->status }}</span>

        </div>


        @if (isset($trip->card_brand))
            <div>
                <span style="display: inline-block; font-weight: bold;">Payment Method:</span>
                <span style="display: inline-block;">{{ $bill->card_brand }}</span>

            </div>
        @endif



        @if (isset($trip->airlines))
            <div>
                <span style="display: inline-block; font-weight: bold;">Airline @if (isset($trip->flight_number))
                        & Flight#:
                    @endif
                </span>
                @php
                    $parts = explode(' - ', $trip->airlines->name);

                @endphp
                <span style="display: inline-block;">{{ $parts[0] }}</span>
                @if (isset($trip->flight_number))
                    <span style="display: inline-block;">{{ $trip->flight_number }}</span>
                @endif
            </div>
        @endif
        @if (isset($trip->childSeats) && $trip->childSeats->count() > 0)

            <div>
                <div>
                    <span style="display: inline-block; font-weight: bold;">Child Seats:</span>
                    @foreach ($trip->childSeats as $key => $seat)
                        <span style="display: inline-block;">
                                {{-- out put the number of child seat according to the ammount --}}
                                @for ($i = 0; $i < $seat->pivot->amount; $i++)
                                    {{ $seat->title }} @if($i + 1 != $seat->pivot->amount && $i + 1 >! $seat->pivot->amount ) , @endif
                                @endfor
                        </span>
                    @endforeach
                </div>
            </div>
        @endif
        @if (isset($trip->tip) && $trip->tip != 0)
            <div>
                <span style="display: inline-block; font-weight: bold;">Tip:</span>
                <span style="display: inline-block;">${{ $trip->tip }}</span>

            </div>
        @endif
        <div>
            <span style="display: inline-block; font-weight: bold;">Price:</span>
            <span style="display: inline-block;">${{ $trip->price_with_tip }}</span>

        </div>

    </div>


    <div class="info">
        <div>
            <h3>Route</h3>
            <div>
                <span style="display: inline-block; font-weight: bold;">From:</span>
                <span style="display: inline-block;">{{ $trip->pick_up_location }}</span>
            </div>
            @if (isset($trip->drop_off_location))
                <div>
                    <span style="display: inline-block; font-weight: bold;">To:</span>
                    <span style="display: inline-block;">{{ $trip->drop_off_location }}</span>
                </div>
            @endif

        </div>
    </div>



    @if (isset($trip->fleets))
        <div class="info">
            <h3>Chauffeur & Vehicle</h3>
            @if (isset($driver->id))
                <div>
                    <div>
                        <span style="display: inline-block;font-weight: bold;">Name:</span>
                        <span class="capitalize" style="display: inline-block;">{{ $driver->fullname }}</span>

                    </div>
                </div>
                <div>
                    <div>
                        <span style="display: inline-block;font-weight: bold;">Phone Number::</span>
                        <span style="display: inline-block;">{{ $driver->phone }}</span>

                    </div>
                </div>
            @endif



            <div>
                <span style="display: inline-block;font-weight: bold;">Fleet Category:</span>
                <span style="display: inline-block;">{{ $vehicle->title }}</span>
            </div>
            @if (isset($trip->vehicle))
                <div>
                    <span style="display: inline-block;font-weight: bold;">Vehicle Type:</span>
                    <span style="display: inline-block;">{{ $trip->vehicle ? $trip->vehicle->title : 'N/A' }}</span>
                </div>
            @endif
            <div>
                <span style="display: inline-block;font-weight: bold;">Vehicle Capacity:</span>
                <span style="display: inline-block;">{{ isset($vehicle->passengers) ? $vehicle->passengers : 'N/A' }}
                    Passengers</span>
            </div>



        </div>
    @endif
    @if (isset($trip->comment))
        <div class="info">
            <h3>Comment</h3>
            <div>

                <p> {{ strlen($trip->comment) > 250 ? substr($trip->comment, 0, 223) . '...' : $trip->comment }}</p>

            </div>

        </div>
    @endif

    <div class="footer" style="position: fixed; bottom: 0; width: 100%;">
        <table border="0">
            <tr>
                <td>
                    <div class="info" style="top:5px; text-align: center">
                        <span
                            style="text-align: center; font-size:13px;  font-weight: bold;
                        ">Thank
                            You For Choosing Lavish Ride!</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="links">
                    <a href="https://lavishride.com/terms-and-conditions" class="terms"
                        style="font-size: 14px; padding-top:10px; color:#ae2226" target="_blank">Terms & Conditions.</a>


                    <a href="https://www.facebook.com/LavishrideUS">
                        <img src="{{ asset('images/facebook.png') }}" alt="Facebook">
                    </a>
                    <a href="https://www.instagram.com/lavish_ride">
                        <img src="{{ asset('images/instagram.png') }}" alt="Instagram">
                    </a>
                    <a href="https://www.linkedin.com/company/lavish-ride-houston">
                        <img src="{{ asset('images/linkedin.png') }}" alt="LinkedIn">
                    </a>
                    <a href="https://twitter.com/lavishride">
                        <img src="{{ asset('images/X.png') }}" alt="Twitter">
                    </a>
                    <a href="https://pinterest.com/lavishride">
                        <img src="{{ asset('images/pinterest.png') }}" alt="pinterest">
                    </a>


                </td>
            </tr>
        </table>

    </div>
</body>

</html>
