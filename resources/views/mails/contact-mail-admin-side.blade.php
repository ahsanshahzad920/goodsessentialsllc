<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #007BFF;
            text-align: center;
        }

        .details {
            margin-top: 20px;
        }

        .details li {
            list-style: none;
            padding: 5px 0;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #aaa;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>New Contact Form Submission</h2>
        <p>You have received a new message through the contact form. Here are the details:</p>
        <div class="details">
            <ul>
                <li><strong>Name:</strong> {{ $data['first_name'] }} {{ $data['last_name'] }}</li>
                <li><strong>Email:</strong> {{ $data['email'] }}</li>
                <li><strong>Phone:</strong> {{ $data['phone'] }}</li>
                <li><strong>Address:</strong> {{ $data['address'] }}</li>
                <li><strong>Note:</strong> {{ $data['note'] }}</li>
            </ul>
        </div>
        <p>Please review and respond to this inquiry promptly.</p>

        <div class="footer">
            <p>This is an automated notification. Please do not reply directly to this email.</p>
        </div>
    </div>
</body>

</html>
