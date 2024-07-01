<x-mail::message>
# Someone Posted

<!-- The body of your message. -->
**{{$user->username}}** has posted on your timeline

<x-mail::button :url="route('dashboard.index')">
Go see what they wrote
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
