<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Verification Code - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f53003;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #f53003;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #6c757d;
            font-size: 16px;
        }
        .content {
            margin-bottom: 30px;
        }
        .verification-code {
            background: linear-gradient(135deg, #f53003, #ff6b35);
            color: white;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            box-shadow: 0 4px 15px rgba(245, 48, 3, 0.3);
        }
        .info-box {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .warning-box {
            background-color: #fff3e0;
            border-left: 4px solid #ff9800;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .security-notice {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .security-notice h3 {
            color: #495057;
            margin-top: 0;
            font-size: 18px;
        }
        .security-notice ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .security-notice li {
            margin: 8px 0;
            color: #6c757d;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .divider {
            height: 1px;
            background-color: #dee2e6;
            margin: 25px 0;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .container {
                padding: 20px;
            }
            .verification-code {
                font-size: 24px;
                letter-spacing: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">{{ config('app.name') }}</div>
            <div class="subtitle">Secure Login Verification</div>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Login Verification Required</h2>
            
            <p>Hello {{ $user->full_name ?? $user->username }},</p>
            
            <p>We received a login attempt for your account. To complete the login process, please use the verification code below:</p>
            
            <div class="verification-code">
                {{ $code }}
            </div>
            
            <div class="info-box">
                <strong>⏰ Important:</strong> This verification code will expire in {{ $expiryMinutes }} minutes for your security.
            </div>
            
            <div class="warning-box">
                <strong>🔐 Security Reminder:</strong> Never share this code with anyone. Our team will never ask for your verification code.
            </div>
            
            <div class="divider"></div>
            
            <h3>What happens next?</h3>
            <ol>
                <li>Enter the 6-digit code above in the verification form</li>
                <li>Complete your login process</li>
                <li>Access your account securely</li>
            </ol>

            <!-- Security Notice -->
            <div class="security-notice">
                <h3>🛡️ Security Notice</h3>
                <ul>
                    <li>If you did not attempt to log in, please ignore this email and consider changing your password.</li>
                    <li>This code is valid for {{ $expiryMinutes }} minutes only.</li>
                    <li>You have a maximum of 3 attempts to enter the correct code.</li>
                    <li>Always verify you're on the official {{ config('app.name') }} website before entering codes.</li>
                </ul>
            </div>
            
            <p>If you did not request this login verification, please contact our support team immediately.</p>
            
            <p>Best regards,<br>The {{ config('app.name') }} Security Team</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is an automated security email from {{ config('app.name') }}.</p>
            <p>Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
