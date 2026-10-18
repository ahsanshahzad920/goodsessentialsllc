
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Good Essentials</title>
</head>
<body style="margin:0; padding:0; background:#f5f6fa; font-family:Arial,sans-serif; color:#333;">

    <div style="max-width:600px; margin:30px auto; background:#ffffff; border-radius:12px; overflow:hidden;">

        <div style="background:#4c49e3; padding:28px 20px; text-align:center; color:#ffffff;">
            <h1 style="margin:0; font-size:28px;">Good Essentials</h1>
            <p style="margin:8px 0 0;">For happy little ones</p>
        </div>

        <div style="padding:35px 28px;">
            <h2 style="color:#333; margin-top:0;">
                Welcome, {{ $user->name }}!
            </h2>

            <p style="font-size:16px; line-height:1.7;">
                Thank you for creating an account with Good Essentials.
                We're delighted to have you join our community.
            </p>

            <p style="font-size:16px; line-height:1.7;">
                Explore our collection of essentials and find something
                special for your little ones and your family.
            </p>

            <div style="text-align:center; margin:30px 0;">
                <a href="{{ config('app.url') }}"
                   style="display:inline-block; background:#4c49e3; color:#ffffff; text-decoration:none; padding:14px 28px; border-radius:6px; font-weight:bold;">
                    Visit Our Store
                </a>
            </div>

            <p style="font-size:14px; line-height:1.7; color:#666;">
                If you have any questions, you can contact our team at
                <a href="mailto:info@goodsessentialsllc.com">
                    info@goodsessentialsllc.com
                </a>.
            </p>

            <p style="margin-bottom:0; line-height:1.7;">
                Thank you,<br>
                <strong>Good Essentials Team</strong>
            </p>
        </div>

        <div style="background:#f0f0f5; text-align:center; padding:18px; font-size:12px; color:#777;">
            &copy; {{ date('Y') }} Good Essentials. All rights reserved.
        </div>

    </div>

</body>
</html>
