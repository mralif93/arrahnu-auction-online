<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - Arrahnu Auction</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #1b1b18;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #f53003 0%, #d42a02 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
        }
        .content h2 {
            color: #1b1b18;
            margin: 0 0 20px 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content p {
            color: #706f6c;
            margin: 0 0 20px 0;
            font-size: 16px;
        }
        .button {
            display: inline-block;
            background-color: #f53003;
            color: #ffffff;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #d42a02;
        }
        .security-notice {
            background-color: #fff2f2;
            border: 1px solid #f8d7da;
            border-radius: 6px;
            padding: 20px;
            margin: 30px 0;
        }
        .security-notice h3 {
            color: #1b1b18;
            margin: 0 0 10px 0;
            font-size: 18px;
            font-weight: 600;
        }
        .security-notice ul {
            color: #706f6c;
            margin: 0;
            padding-left: 20px;
        }
        .security-notice li {
            margin: 5px 0;
        }
        .footer {
            background-color: #1b1b18;
            color: #A1A09A;
            padding: 30px;
            text-align: center;
            font-size: 14px;
        }
        .footer a {
            color: #f53003;
            text-decoration: none;
        }
        .divider {
            height: 1px;
            background-color: #e3e3e0;
            margin: 30px 0;
        }
        @media (max-width: 600px) {
            .container {
                margin: 0;
                border-radius: 0;
            }
            .header, .content, .footer {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Arrahnu Auction</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Reset Your Password</h2>
            
            <p>Hello!</p>
            
            <p>You are receiving this email because we received a password reset request for your account. Click the button below to reset your password:</p>
            
            <div style="text-align: center;">
                <a href="{{ $actionUrl }}" class="button">Reset Password</a>
            </div>
            
            <p>This password reset link will expire in {{ $count }} minutes.</p>
            
            <div class="divider"></div>
            
            <p>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:</p>
            
            <p style="word-break: break-all; color: #f53003; font-family: monospace; background-color: #f8f9fa; padding: 10px; border-radius: 4px;">
                {{ $actionUrl }}
            </p>

            <!-- Security Notice -->
            <div class="security-notice">
                <h3>🔒 Security Notice</h3>
                <ul>
                    <li>If you did not request a password reset, no further action is required.</li>
                    <li>Never share your password reset link with anyone.</li>
                    <li>This link will expire automatically for your security.</li>
                    <li>Always verify the sender's email address before clicking links.</li>
                </ul>
            </div>
            
            <p>If you did not request a password reset, please ignore this email or contact our support team if you have concerns about your account security.</p>
            
            <p>Best regards,<br>The Arrahnu Auction Team</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Arrahnu Auction. All rights reserved.</p>
            <p>
                <a href="{{ url('/') }}">Visit our website</a> | 
                <a href="#">Contact Support</a> | 
                <a href="#">Privacy Policy</a>
            </p>
        </div>
    </div>
</body>
</html>
