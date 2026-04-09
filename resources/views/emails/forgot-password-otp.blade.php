<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Reset OTP</title>
</head>
<body>
    <p>Hi {{ $name }},</p>
    <p>We received a request to reset your password. Use the OTP below to continue:</p>
    <h2>{{ $otp }}</h2>
    <p>This OTP expires at {{ $expiresAt->format('Y-m-d H:i') }}.</p>
    <p>If you did not request this, please ignore this email.</p>
</body>
</html>
