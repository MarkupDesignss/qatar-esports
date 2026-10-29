<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email Verification OTP</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px;">
    <div style="max-width: 500px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px;">
        <h2 style="color: #333;">Hello {{ $name }},</h2>

        <p style="color: #555; font-size: 15px;">
            Use the OTP below to verify your email:
        </p>

        <div style="text-align: center; margin: 30px 0;">
            <span style="font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #4CAF50;">
                {{ $otp }}
            </span>
        </div>

        <p style="color: #777; font-size: 13px;">
            This OTP is valid for <strong>10 minutes</strong>. If you did not request this, please ignore this email.
        </p>

        <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">

        <p style="color: #999; font-size: 12px; text-align: center;">
            © {{ date('Y') }} Your App. All rights reserved.
        </p>
    </div>
</body>
</html>