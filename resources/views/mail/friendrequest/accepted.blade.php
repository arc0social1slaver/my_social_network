<x-mail::message>
<!-- # Introduction -->
# Friend Request Accepted

<!-- The body of your message. -->
**You're friend request has been accepted by:**

**Name:** {{$user->name}}
**Userame:** {{$user->username}}

<x-mail::button :url="route('profiles.show', $user->username)">
Visit their profile
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
