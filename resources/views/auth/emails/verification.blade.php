@if(config('app.locale', 'en') === 'en') 
Click the following link to activate your {{ config('app.name', 'Laravel') }} account:
<a href="{{ $link = url('auth/verify', $token).'?email='.urlencode($user->email) }}"> {{ $link }} </a>
@else 
Klik link berikut untuk melakukan aktivasi akun {{ config('app.name', 'Laravel') }}:
<a href="{{ $link = url('auth/verify', $token).'?email='.urlencode($user->email) }}"> {{ $link }} </a>
@endif

