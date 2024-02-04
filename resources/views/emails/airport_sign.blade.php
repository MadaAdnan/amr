<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            direction: ltr;
        }
        body {
            font-family: sans-serif;
            width: 100%;
            height: 100%;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }
        p {
            margin: 0;
        }
        .container {
            position: relative;
            height: 672px;
            padding: 10px;
            box-sizing: border-box;
            overflow: hidden;
            margin: 35px;
        }
        .container .header, .container .content {
            width: 100%;
            display: block;
        }
        .container .header {
            position: absolute;
            height: 120px;
            left: 0;
            top: 0;
            margin: 0px 20px;
            display: inline-block;
        }
        .container .header .logo {
            /* position: absolute; */
            display: inline-block;
            /* top: 25px;
            right: 40px; */
            width: 300px;
            height: 300px;
            padding-top:100px;
            padding-left:320px;


        }
        /* .container .header .title {
            display: inline-block;
            position: absolute;
            top: -135px;
            left: -40;
            color: #262221;
            font-size: 50px;
            font-weight: 600;
            padding-left:65%;

        } */
        .container .content {
            position: absolute;
            top: 250px;
        }
        .container .content div {
            text-transform: capitalize;
            width: 100%;
        }
        .container .content .customer {
            padding: 67px 30px;
            font-size: 100px;
            color: #262221;
            letter-spacing: 3px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div style="display: inline-block;">
            <img class="logo" src="{{ asset('images/LR Official Logo.png') }}" alt="">
            </div>
            {{-- <img class="logo" src="{{env('APP_URL')}}/images/lavishLogo.png" > --}}
            <div style="display: inline-block;">
            {{-- <span class="title"> LAVISH RIDE<hr style=" border: 4px solid #ae2226; "> </span> --}}
            {{-- <span class="title"> LAVISH RIDE</span> --}}
            </div>
            
        </div>
        <div class="content">
            <div>
                {{-- <h2 class="customer">
                    Welcome!                </h2> --}}
                <h2 class="customer">
                    {{ $trip->pickup_sign??$customer->full_name }}
                </h2>
            </div>

        </div>
        
    </div>
    <div><hr style=" border: 12px solid #ae2226; margin-top: 30px"></div>
    {{-- <hr style=" border: 12px solid #ae2226; margin-top: 30px"> --}}
</body>

</html>