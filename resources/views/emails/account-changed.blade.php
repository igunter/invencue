<!DOCTYPE html>
<html>
<body style="font-family: -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; color: #13233f; line-height: 1.6;">
    <p>Hi {{ $user->name }},</p>

    @if ($type === 'email')
        <p>The email address on your {{ config('app.name') }} account was just changed from <strong>{{ $oldEmail }}</strong> to <strong>{{ $newEmail }}</strong>.</p>
    @else
        <p>The password on your {{ config('app.name') }} account (<strong>{{ $user->email }}</strong>) was just changed.</p>
    @endif

    <p>If you made this change, you don't need to do anything.</p>

    <p><strong>If you didn't make this change</strong>, please contact us straight away so we can help secure your account.</p>

    <p>Thanks,<br>{{ config('app.name') }}</p>
</body>
</html>
