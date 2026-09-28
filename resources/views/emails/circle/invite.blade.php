<x-mail::message>
# You are Invited!

**{{ $ownerName }}** has invited you to join their circle: **{{ $circleName }}**.

To join this circle, please use the following invite code in the app:

<x-mail::panel>
**{{ $inviteCode }}**
</x-mail::panel>

*This code will expire in 24 hours.*

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
