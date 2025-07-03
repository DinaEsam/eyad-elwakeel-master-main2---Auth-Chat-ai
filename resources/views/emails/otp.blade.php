@component('mail::message')
# التحقق من هويتك

@component('mail::panel')
رمز التحقق الخاص بك هو:  
**{{ $code }}**
@endcomponent

من فضلك لا تشارك هذا الرمز مع أي شخص.

شكرًا لك،  
{{ config('app.name') }}
@endcomponent
