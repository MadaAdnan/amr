<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Redirect Page</title>
  <head>
    <meta http-equiv="refresh" content="0; URL='{{ $redirectUrl }}'" /> 
  </head>
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
    }
    
    .container {
      text-align: center;
    }
    
    .message {
      font-size: 24px;
      margin-bottom: 16px;
    }
    
    .redirect-link {
      font-size: 18px;
      color: #0066cc;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="message">
      <h1>This page has moved. You will be redirected soon. Please wait... </h1>
    </div>
  </div>
</body>
</html>
