{{-- @component('mail::message')
# التحقق من هويتك

@component('mail::panel')
رمز التحقق الخاص بك هو:  
**{{ $code }}**
@endcomponent

من فضلك لا تشارك هذا الرمز مع أي شخص.

شكرًا لك،  
{{ config('app.name') }}
@endcomponent --}}

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>رمز التحقق</title>
  <style>
    body {
      direction: rtl;
      font-family: Arial, sans-serif;
      background: #f2f8fc;
      padding: 30px;
    }
    .container {
      background: white;
      max-width: 500px;
      margin: auto;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .otp-code {
      background-color: #D8F3FE;
      padding: 20px;
      font-size: 26px;
      font-weight: bold;
      text-align: center;
      color: #0077b6;
      border-radius: 8px;
      letter-spacing: 6px;
      margin: 30px 0;
    }
    .footer {
      margin-top: 20px;
      font-size: 14px;
      color: #555;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2 style="text-align:center;">التحقق من هويتك</h2>
    <p style="text-align:center;">رمز التحقق الخاص بك هو:</p>

    <div class="otp-code">
      {{ $code }}
    </div>

    <p style="text-align:center;">من فضلك لا تشارك هذا الرمز مع أي شخص.</p>

    <div class="footer">
      شكرًا لك،<br>
      {{ config('app.name') }}
    </div>
  </div>
</body>
</html>
