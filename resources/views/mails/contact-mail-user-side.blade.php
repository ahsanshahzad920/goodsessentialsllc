<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            font-size: 16px;
            margin-bottom: 10px;
            color: #555;
        }

        ul li strong {
            color: #333;
        }

        .email-footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #888;
        }

        .button {
            display: inline-block;
            background-color: #007BFF;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            margin-top: 20px;
        }

        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <h1>Thank You for Your Message, {{ $data['first_name'] }}!</h1>

        <p>We appreciate you reaching out to us. Below are the details you submitted:</p>

        <ul>
            <li><strong>Name:</strong> {{ $data['first_name'] }} {{ $data['last_name'] }}</li>
            <li><strong>Email:</strong> {{ $data['email'] }}</li>
            <li><strong>Phone:</strong> {{ $data['phone'] }}</li>
            <li><strong>Address:</strong> {{ $data['address'] }}</li>
            <li><strong>Note:</strong> {{ $data['note'] }}</li>
        </ul>

        <p>We will review your message and get back to you as soon as possible. If you have any urgent queries, feel
            free to contact us at our support email.</p>

        <p class="email-footer">
            Thank you for choosing our service!<br>
            <a href="{{ route('index') }}" class="button">Visit Our Website</a>
        </p>
    </div>
</body>

</html>
