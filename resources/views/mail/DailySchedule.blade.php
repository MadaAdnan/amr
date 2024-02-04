<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations Daily Schedule</title>
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
        
        .info h3 {
            line-height: 20px;
        }

        .daily-schedule {
            max-width: 680px;
            width: 680px;
        }

        .daily-schedule table {
            width: 100%;
            background-color: #fff;
            border: 1px solid #000;
            border-radius: 5px;
            padding: 0;
            margin: 0;
        }
        
        .daily-schedule table tr {
            max-width: 680px;
            width: 680px;
            padding: 0;
            margin: 0;
        }
        
        .daily-schedule table td {
            padding: 8px 5.7px;
            margin: 5px 0 0 0;
        }

        .daily-schedule .trip-block {
            display: inline-block;
        }

        .daily-schedule .trip-block span {
            font-size: 10px;
            line-height: 16px;
        }

        .daily-schedule table tr:first-child {
            background-color: #000;
        }

        .daily-schedule table tr:first-child td {
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{env('APP_URL')}}/images/logo-white.png" alt="">
        <div class="body">
            <h1>Reservations Daily Schedule</h1>
            <div class="blocks">
                <table border="0">
                    <tr>
                        <td>
                            <div class="block">
                                <p>Schedule Date:</p>
                                <span>{{ $schedule_date }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="block">
                                <p>Number of Trips:</p>
                                <span>#{{ $trips_count }}</span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="info">
        <h3>Trip Details</h3>
        <div class="daily-schedule">
            <table border="0">
                <tr>
                    <td style="width: 20px;"><div class="trip-block"><span>#</span></div></td>
                    <td style="width: 50px;"><div class="trip-block"><span>Status</span></div></td>
                    <td style="width: 50px;"><div class="trip-block"><span>Time</span></div></td>
                    <td style="width: 60px;"><div class="trip-block"><span>Service</span></div></td>
                    <td style="width: 60px;"><div class="trip-block"><span>Vehicle</span></div></td>
                    <td style="width: 108px;"><div class="trip-block"><span>Pick-up Location</span></div></td>
                    <td style="width: 108px;"><div class="trip-block"><span>Drop-off Location</span></div></td>
                    <td style="width: 60px;"><div class="trip-block"><span>Customer</span></div></td>
                    <td style="width: 60px;"><div class="trip-block"><span>Captain</span></div></td>
                </tr>
                @foreach( $trips as $trip )
                <tr>
                    <td style="width: 20px;"><div class="trip-block"><span>{{ $trip->id }}</span></div></td>
                    <td style="width: 50px;"><div class="trip-block"><span>{{ $trip->status }}</span></div></td>
                    <td style="width: 50px;"><div class="trip-block"><span>{{ \Carbon\Carbon::parse($trip->pick_up_time)->format("h:i A") }}</span></div></td>
                    <td style="width: 60px;"><div class="trip-block"><span>{{ $trip->serviceType->service_name }}</span></div></td>
                    <td style="width: 60px;"><div class="trip-block"><span>{{ $trip->vehicle->vehicle_type }}</span></div></td>
                    <td style="width: 108px;"><div class="trip-block"><span>{{ $trip->pick_up_location }}</span></div></td>
                    <td style="width: 108px;"><div class="trip-block"><span>{{ $trip->drop_off_location }}</span></div></td>
                    <td style="width: 60px;" class="capitalize"><div class="trip-block"><span>{{ $trip->customer->fullname }}</span></div></td>
                    <td style="width: 60px;" class="capitalize"><div class="trip-block"><span>{{ $trip->driver->user->fullname }}</span></div></td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</body>

</html>