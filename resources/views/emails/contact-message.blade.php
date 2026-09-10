<x-mail::message>
# New contact form message

You've received a new message from the {{ config('app.name') }} contact form.

**Name:** {{ $senderName }}
**Email:** {{ $senderEmail }}

**Message:**

{{ $messageBody }}

<x-mail::button :url="'mailto:' . $senderEmail">
Reply to {{ $senderName }}
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
