<!DOCTYPE html>
<html>

<head>
    <title>Verification Code</title>
</head>

<body>
    <p>Hello,</p>
    <p>Your verification code is: <strong>{{ $code }}</strong></p>
    <p>This code will expire in {{ (int) env('VERIFICATION_EXPIRATION') / 60 }} min. Please use it promptly.</p>
</body>

</html>
