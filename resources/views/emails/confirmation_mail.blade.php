<!DOCTYPE html>
<html>
<head>
  <title>New Password Generated</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f7f7f7;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      background-color: #ffffff;
      border: 1px solid #e6e6e6;
      border-radius: 4px;
    }
    .logo {
      text-align: center;
      margin-bottom: 20px;
    }
    .logo img {
      max-height: 100px;
    }
    .content {
      font-size: 18px;
      line-height: 1.6;
      padding: 0 20px;
    }
    .password {
      font-weight: bold;
      font-size: 20px;
      text-align:center;
      margin-top: 20px;
      color: #333;
    }
    .btn {
      display: inline-block;
      background-color: #e74c3c;
      color: #ffffff;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 4px;
      font-weight: bold;
      margin-top: 20px;
      text-align: center;
      margin-left:41%
    }
    .btn:hover {
      background-color: #421611;
    }
    .footer {
      text-align: center;
      font-size: 14px;
      color: #999999;
      margin-top: 30px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo">
      <img src="{{ asset('images/logo-white.png') }}" alt="Lavashride Logo">
    </div>
    <div class="content">
      <h2 style="text-align:center">Welcome {{$customer->first_name}}</h2>
      <p>We have generated a new password for your account. To log in, please use the following password:</p>
      <p class="password">{{ $password }}</p>
      <p>We recommend changing this password after logging in.</p>
      <p>To proceed with signing up, please use the following psassword and agree to the <a href="https://lavishride.com/terms-and-conditions">terms and conditions</a> of LavishRide
        please agree to the  of Lavish Ride.</p>
      <a href="{{ route('confirm',['token' => $token]) }}" class="btn">Agree</a>
    </div>
    <div class="footer">
      <p>&copy; 2023 LavishRide. All rights reserved.</p>
    </div>
  </div>
</body>
</html>
