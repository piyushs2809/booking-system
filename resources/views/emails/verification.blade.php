<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email Verification - Booking System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #0d6efd;
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 30px;
        }
        .btn {
            display: inline-block;
            background-color: #0d6efd;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Booking System!</h1>
        </div>
        <div class="content">
            <h2>Hello {{ $user->first_name }},</h2>
            <p>Thank you for registering with our Booking System. To complete your registration, please verify your email address by clicking the button below:</p>
            
            <a href="{{ url('/verify-email/' . $user->email_verification_token) }}" class="btn">
                Verify Email Address
            </a>
            
            <p>If you're unable to click the button, copy and paste the following link into your browser:</p>
            <p style="word-break: break-all; color: #0d6efd;">
                {{ url('/verify-email/' . $user->email_verification_token) }}
            </p>
            
            <p>If you did not create an account, no further action is required.</p>
            
            <p>Best regards,<br>The Booking System Team</p>
        </div>
        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
