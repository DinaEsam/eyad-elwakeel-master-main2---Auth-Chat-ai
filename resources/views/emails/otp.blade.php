@component('mail::message')
# Reset Password OTP

@component('mail::panel')
Your OTP code is: **{{ $code }}**
@endcomponent

Thanks,  
{{ config('app.name') }}
@endcomponent
