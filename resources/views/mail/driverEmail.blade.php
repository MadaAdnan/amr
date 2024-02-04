<!doctype html>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <title>Lavish Ride</title>
        <meta name="description" content="Lavish Ride">
        <style type="text/css">
            table{ width: 100%; }
            .tbl-content{ max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06); }
            .logo {background: url("https://lavishride.com/reservations/images/lavishLogo.png"); width: 100px; height:70px; margin:auto; background-size: contain; background-position: center; background-repeat: no-repeat; }
            .col { padding: 7px 20px; text-align: left; font-size: 13px;}
            .d-flex { display: flex; }
            .pl-2 { padding-left: 20px;}
        </style>
    </head>

    <body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
        <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
            style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;">
            <tr>
                <td>
                    <table style="background-color: #f2f3f8; max-width:670px;  margin:0 auto;" width="100%" border="0"
                        align="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="height:80px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="height:20px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="tbl-content">
                                    <tr>
                                        <td colspan="2" style="height:40px;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align:center;">
                                            <div class="logo"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="height:10px;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding:0 20px;">
                                            <h1 style="color:#363636; font-weight:500; margin:0;font-size:26px;">
                                                Ride assignment
                                            </h1>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="col">
                                            <h1 style="color:#363636; font-weight:600; margin:0;font-size:14px;">
                                                Trip Details
                                            </h1>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding: 7px 20px;"> <hr> </td>
                                    </tr>
                                    <tr>
                                        <td class="col"><b>Trip Number:</b></td>
                                        <td class="col">{{$details['trip']->id}}</td>
                                    </tr>
                                    <tr>
                                        <td class="col"><b>Status:</b></td>
                                        <td class="col">{{ $details['trip']->status }}</td>
                                    </tr>
                                    <tr>
                                        <td class="col"><b>Service type:</b></td>
                                        <td class="col">{{ \App\Models\ServiceType::find($details['trip']->service_type)->service_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="col"><b>Pickup date-time:</b></td>
                                        <td class="col">{{ $details['trip']->pick_up_date . ' - ' .  $details['trip']->pick_up_time }}</td>
                                    </tr>
                                    <tr>
                                        <td class="col"><b>Distance:</b></td>
                                        <td class="col">{{ $details['trip']->distance }}</td>
                                    </tr>
                                    @if( $details['trip']->service_type == 3 )
                                    <tr>
                                        <td class="col"><b>Airline:</b></td>
                                        <td class="col">{{ $details['trip']->airline }}</td>
                                    </tr>
                                    <tr>
                                        <td class="col"><b>Flight Number:</b></td>
                                        <td class="col">{{ $details['trip']->flight_number }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="2" style="height:20px;">&nbsp;</td>
                                    </tr>
                                </table>
                                <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="tbl-content">
                                    <tr>
                                        <td colspan="2" style="height:20px;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="col">
                                            <h1 style="color:#363636; font-weight:600; margin:0;font-size:14px;">
                                                Route Location
                                            </h1>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding: 7px 20px;"> <hr> </td>
                                    </tr>
                                    <tr>
                                        <td class="col d-flex"><b>Pick Up Location:</b></td>
                                        <td class="col d-flex">{{ $details['trip']->pick_up_location }}</td>
                                    </tr>
                                    @if( isset($details['trip']->drop_off_location) )
                                    <tr>
                                        <td class="col d-flex"><b>Drop Off Location:</b></td>
                                        <td class="col d-flex">{{ $details['trip']->drop_off_location }}</td>
                                    </tr>
                                    @endif
                                    @if( !!$details['stop_locations'] && count($details['stop_locations']) > 0 )
                                    <tr>
                                        <td colspan="2" class="col">
                                            <b>Stop Locations</b>
                                        </td>
                                    </tr>
                                    @foreach( $details['stop_locations'] as $location )
                                    <tr class="pl-2">
                                        <td class="col d-flex"><b>Location:</b></td>
                                        <td class="col d-flex">
                                            {{ $location->location }}
                                            @if( $location->time )
                                                - <b> Time: </b> {{ \Carbon\Carbon::parse($location->time)->format("h:i A") }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                    <tr>
                                        <td colspan="2" style="height:20px;">&nbsp;</td>
                                    </tr>
                                </table>
                                <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="tbl-content">
                                    <tr>
                                        <td colspan="2" style="height:20px;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="col">
                                            <h1 style="color:#363636; font-weight:600; margin:0;font-size:14px;">
                                                Vehicle
                                            </h1>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding: 7px 20px;"> <hr> </td>
                                    </tr>
                                    <tr>
                                        <td class="col d-flex"><b>Vehicle name:</b></td>
                                        <td class="col d-flex">{{ \App\Models\Vehicle::find($details['trip']->vehicle_id)->vehicle_name }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="height:20px;">&nbsp;</td>
                                    </tr>
                                </table>
                                <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="tbl-content">
                                    <tr>
                                        <td colspan="2" style="height:20px;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="col">
                                            <h1 style="color:#363636; font-weight:600; margin:0;font-size:14px;">
                                                Customer Details
                                            </h1>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding: 7px 20px;"> <hr> </td>
                                    </tr>
                                    <tr>
                                        <td class="col"><b>Customer Name:</b></td>
                                        <td class="col">{{ $details['customer']->first_name . ' ' . $details['customer']->last_name}} </td>
                                    </tr>
                                    <tr>
                                        <td class="col"><b>Phone Number:</b></td>
                                        <td class="col">{{ $details['customer']->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="col">
                                            Allow the customer to modify the itinerary: <b>{{ $details['trip']->modify_itinerary == 1 ? "YES" : "NO" }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="height:20px;">&nbsp;</td>
                                    </tr>
                                    @if( isset($details["call_to_action"]) && $details["call_to_action"] != "" )
                                    <tr>
                                        <td colspan="2" style="padding:0 20px;">
                                            <p style="font-size:15px;line-height:24px; margin:10px;">
                                                <a href="{{ $details['call_to_action'] }}" style="background:rgb(174, 32, 37);color:#fff;border-radius:5px;padding:8px 25px;text-decoration: none !important;">
                                                    Check Trips
                                                </a>
                                            </p>
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                            </td>
                        <tr>
                            <td style="height:20px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="height:80px;">&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>