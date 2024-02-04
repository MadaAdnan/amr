<!DOCTYPE html>
<html>
<head>
  <title>New Password Generated</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f7f7f7;
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
      max-height: 40px;
    }
    .content {
      font-size: 16px;
      line-height: 1.5;
      margin-bottom: 20px;
    }
    .password {
      font-weight: bold;
      font-size: 18px;
      margin-top: 10px;
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
      <img height="100" src="{{ asset('assets_new/img/logo_white.png') }}" alt="Lavashride Logo">
    </div>
    <div class="content">
      <h2>New Password Generated</h2>
      <p>We have generated a new password for your account. Please use the following password to log in:</p>
      <p class="password">New Password: <strong>{{ $password }}</strong></p>
      <p>We recommend changing this password after logging in.</p>
    </div>
    <div class="footer">
      <p>&copy; 2023 Lavashride. All rights reserved.</p>
    </div>
  </div>
</body>
</html>