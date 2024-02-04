<!doctype html>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
        <title>{{ $mailData['subject'] }}</title>
        <meta name="description" content="{{ $mailData['title'] }}">
            <style>
                body {
                    margin: 0px;
                    background-color: #f2f3f8;
                }
                table {
                    width: 100%;
                    border: 0;
                    cellpadding: 0;
                    cellspacing: 0;
                    background-color: #f2f3f8;
                    font-family: 'Open Sans', sans-serif;
                }
                td {
                    text-align: center;
                }
                .container {
                    background-color: #f2f3f8;
                    max-width: 670px;
                    margin: 0 auto;
                }
                .logo-container {
                    padding: 40px 0;
                }
              
                img {
                    width: 183px;
                    height: 125px;
                    object-fit: contain;
                }
                .logo {
                    background: url("{{ asset('images/LR Official Logo.png') }}");
                    width: 100px; 
                    height:70px;
                    margin:auto;
                    background-size: contain;
                    background-position: center;
                    background-repeat: no-repeat; 
                    text-align: center;
                }
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
                            <td class="container">
                                <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                                    style="max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                                    <tr>
                                        <td style="height:40px;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        
                                        <td >
                                            <img src="{{ asset('images/lavishLogo.png') }}"alt='LR logo' width="150" height="150">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height:10px;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:0 20px;">
                                            <h1 style="color:#363636; font-weight:500; margin:0;font-size:26px;font-family:'Rubik',sans-serif;">
                                                {{ $mailData['title'] }}
                                            </h1>
                                            <p style="color:#455056; font-size:15px;line-height:24px; margin:10px;">
                                                {{ $mailData['message'] }};
                                            </p>
                                            <div style="text-align: -webkit-center;">
                                              <a href="{{ route('user.login') }}" style="width: 30%;padding: 5px;border-radius: 5px;border: navajowhite;background: #ae2228;color: #ffff;display: block;cursor: pointer;font-weight:bold;text-decoration: none;margin-top:2%;">Check</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height:40px;">&nbsp;</td>
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