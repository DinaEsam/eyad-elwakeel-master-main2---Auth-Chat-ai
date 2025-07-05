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
<html lang="ar" style="margin: 0; padding: 0;">
  <head>
    <meta charset="UTF-8" />
    <title>التحقق من الكود</title>
    <!-- Use Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <style>
      body {
        background-color: #E0F2FE;
        padding: 40px 20px;
        margin: 0;
        font-family: 'Inter', Arial, sans-serif;
        direction: rtl;
      }

      .container {
        max-width: 520px;
        margin: auto;
        background: #ffffff;
        padding: 32px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 89, 141, 0.2);
      }

      h2 {
        color: #00598D;
        text-align: center;
        font-size: 24px;
        margin-bottom: 16px;
      }

      p {
        font-size: 15px;
        color: #515151;
        line-height: 1.6;
        text-align: center;
      }

      .otp-box {
        text-align: center;
        margin: 32px 0;
      }

      .otp-code {
        display: inline-block;
        background-color: #B9E6FE;
        padding: 16px 32px;
        font-size: 26px;
        letter-spacing: 6px;
        font-weight: 700;
        color: #00598D;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(2, 106, 162, 0.15);
      }

      .security-warning {
        background-color: #FDE8E8;
        color: #F05252;
        padding: 14px 20px;
        border-radius: 8px;
        font-size: 14px;
        line-height: 1.5;
        margin-bottom: 20px;
      }

      .footer {
        font-size: 14px;
        color: #282828;
        text-align: center;
        margin-top: 30px;
      }

      /* Responsive Design */
      @media (max-width: 480px) {
        .container {
          padding: 24px 16px;
        }

        .otp-code {
          font-size: 22px;
          padding: 12px 20px;
          letter-spacing: 4px;
        }

        h2 {
          font-size: 20px;
        }
      }
    </style>
  </head>
  <body>
    <div class="container">
      <h2>التحقق من هويتك</h2>

      <p>
        لتستكمل عملية التوثيق، يرجى إدخال كلمة المرور لمرة واحدة (OTP) التي أرسلناها إلى بريدك الإلكتروني:
      </p>

      <div class="otp-box">
       <span class="otp-code">
  {{ $code }}
</span>

      </div>

      <p>
        هذه الكود صالح لفترة قصيرة فقط. لا تشاركه مع أي شخص - حتى لو ادعى أنه من <strong style="color: #026AA2;">Kleitech</strong>.
      </p>

      <div class="security-warning">
        <strong>تنبيه أمني:</strong> شركة Kleitech لن تطلب منك أبداً رموز تسجيل الدخول أو التفاصيل الشخصية عبر البريد الإلكتروني أو الهاتف. كن حذرًا من محاولات الاحتيال.
      </div>

      <p class="footer">
        شكرًا لك على زيارة <strong style="color: #026AA2;">Kleitech</strong>!
      </p>
    </div>
  </body>
</html>