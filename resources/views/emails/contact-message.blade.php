<!DOCTYPE html>
<html>
<body style="font-family: -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; color: #13233f; line-height: 1.6;">
    <p>New message from the {{ config('app.name') }} contact form:</p>

    <p><strong>Name:</strong> {{ $senderName }}<br>
    <strong>Email:</strong> {{ $senderEmail }}</p>

    <p><strong>Message:</strong></p>
    <p>{{ $messageBody }}</p>
</body>
</html>
