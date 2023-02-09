@component('mail::message')
# Welcome {{ $name }}!!

@component('mail::button', ['url' => 'https://google.com'])
Google
@endcomponent

@component('mail::panel')
Bu oddiy panel
@endcomponent

## Table component:
@component('mail::table')
| Laravel   | Table         | Example   |
| --------- |:-------------:| ---------:|
| Col 2 is  | Centered      | $10       |
| Col 3 is  | Right-Aligned | $20       |
@endcomponent

@component('mail::subcopy')
This is a subcopy component
@endcomponent

Thanks <br>
Livepost


@endcomponent

{{-- <x-mail::message>
# Xush kelibsiz!!

Saytimizga tashrif buyuring!

<x-mail::button :url="'https://laravel.com'">
Saytga o'tish
</x-mail::button>

<x-mail::panel>
O'z fikringizni bildiring
</x-mail::panel>

<x-mail::table>
| Xizmatlar     | Table         | Example  |
| ------------- |:-------------:| --------:|
| Col 2 is      | Centered      | $10      |
| Col 3 is      | Right-Aligned | $20      |
</x-mail::table>

Rahmat,<br>
{{ config('app.name') }} jamoasi!
</x-mail::message> --}}
