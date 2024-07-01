<x-mail::message>
<!-- # Introduction -->
# Friend Request Received

<!-- The body of your message. -->
**You have received a new friend request from:**

**Name:** {{$user->name}}
**Userame:** {{$user->username}}

<x-mail::button :url="route('profiles.show', $user->username)">
Visit their profile
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
