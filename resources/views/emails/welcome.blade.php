@component('mail::message')
# We miss you, You haven't logged in for a month

Checkout the latest in our website

@component('mail::button', ['url' => ''])
Hamada 3eb.
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
