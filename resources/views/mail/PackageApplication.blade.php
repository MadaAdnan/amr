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
                    <table style="background-color: #f2f3f8; max-width:670px;  margin:0 auto;" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
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
                                                Package Application
                                            </h1>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="col">
                                            <h1 style="color:#363636; font-weight:600; margin:0;font-size:16px;text-align: center;text-transform: capitalize;">
                                                {{ $mailData->package_type }}
                                            </h1>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding: 7px 20px;"> <hr> </td>
                                    </tr>
                                    <tr>
                                        <td class="col"><b>Name:</b></td>
                                        <td class="col">{{ $mailData->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="col"><b>Phone:</b></td>
                                        <td class="col">{{ $mailData->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td class="col"><b>Email:</b></td>
                                        <td class="col">{{ $mailData->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="col"><b>City:</b></td>
                                        <td class="col">{{ $mailData->city }}</td>
                                    </tr>
                                    <tr>
                                        <td class="col"><b>State:</b></td>
                                        <td class="col">{{ $mailData->state }}</td>
                                    </tr>
                                    <tr>
                                        <td class="col"><b>Zip-code:</b></td>
                                        <td class="col">{{ $mailData->zip_code }}</td>
                                    </tr>
                                    <tr>
                                        <td class="col"><b>Pick-Up Address:</b></td>
                                        <td class="col">{{ $mailData->pick_up_address }}</td>
                                    </tr>
                                    <tr>
                                        <td class="col"><b>Pick-Up Date-Time:</b></td>
                                        <td class="col">{{ $mailData->pick_up_date }} - {{ $mailData->pick_up_time }}</td>
                                    </tr>
                                    <tr>
                                        <td class="col"><b>Vehicle Type:</b></td>
                                        <td class="col">{{ $mailData->vehicle }}</td>
                                    </tr>
                                    @if( isset($mailData->comment) )
                                    <tr>
                                        <td class="col"><b>Comment:</b></td>
                                        <td class="col">{{ $mailData->comment }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="2" style="height:20px;">&nbsp;</td>
                                    </tr>
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