<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - Good Essentials</title>
</head>
<body style="margin:0;padding:0;background:#f5f6fa;font-family:Arial,sans-serif;color:#333;">

    <div style="max-width:600px;margin:30px auto;background:#fff;border-radius:12px;overflow:hidden;">

        <div style="background:#4c49e3;padding:28px 20px;text-align:center;color:#fff;">
            <h1 style="margin:0;">Good Essentials</h1>
            <p style="margin:8px 0 0;">For happy little ones</p>
        </div>

        <div style="padding:35px 28px;">
            <h2>Hello {{ $userName }},</h2>

            <p style="font-size:16px;line-height:1.7;">
                Thank you for registering with Good Essentials!
                Please verify your email address to confirm that it belongs to you.
            </p>

            <div style="text-align:center;margin:30px 0;">
                <a href="{{ $verificationUrl }}"
                   style="display:inline-block;background:#4c49e3;color:#fff;text-decoration:none;padding:14px 28px;border-radius:6px;font-weight:bold;">
                    Verify My Email
                </a>
            </div>

            <p style="font-size:14px;line-height:1.7;color:#666;">
                If you did not create this account, you can safely ignore this email.
            </p>

            <p style="font-size:14px;line-height:1.7;color:#666;">
                If the button doesn't work, copy and paste this link into your browser:
                <br>
                <a href="{{ $verificationUrl }}" style="overflow-wrap:anywhere;">
                    {{ $verificationUrl }}
                </a>
            </p>

            <p>Thank you,<br><strong>Good Essentials Team</strong></p>
        </div>

        <div style="background:#f0f0f5;text-align:center;padding:18px;font-size:12px;color:#777;">
            &copy; {{ date('Y') }} Good Essentials. All rights reserved.
        </div>

    </div>

</body>
</html>
