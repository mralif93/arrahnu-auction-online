<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - ArRahnu Auction</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            margin: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        .content {
            padding: 40px 30px;
        }
        .content h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .content p {
            margin-bottom: 15px;
            color: #666;
            font-size: 16px;
        }
        .verify-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            transition: transform 0.2s;
        }
        .verify-button:hover {
            transform: translateY(-2px);
        }
        .info-box {
            background-color: #e8f4f8;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .warning-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #e9ecef;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .token-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            font-family: monospace;
            font-size: 14px;
            word-break: break-all;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
            }
            .header, .content {
                padding: 20px;
            }
            .content h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏛️ ArRahnu Auction</h1>
            <p>Email Verification Required</p>
        </div>
        
        <div class="content">
            <h2>Hello {{ $user->full_name ?? $user->username }}!</h2>
            
            <p>Welcome to ArRahnu Auction Online! To complete your registration and start participating in auctions, please verify your email address.</p>
            
            <div class="info-box">
                <strong>📧 Email:</strong> {{ $user->email }}<br>
                <strong>🕒 Registration Date:</strong> {{ $user->created_at->format('F j, Y \a\t g:i A') }}<br>
                <strong>📱 Registration Source:</strong> {{ ucfirst($user->registration_source ?? 'web') }}
            </div>
            
            <p><strong>Click the button below to verify your email address:</strong></p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $verificationUrl }}" class="verify-button">
                    ✅ Verify Email Address
                </a>
            </div>
            
            <div class="warning-box">
                <strong>⚠️ Important:</strong> This verification link will expire in <strong>{{ $expiresInHours }} hours</strong> ({{ $expiresAt->format('F j, Y \a\t g:i A') }}).
            </div>
            
            <p>If the button doesn't work, you can copy and paste this link into your browser:</p>
            <div class="token-info">
                {{ $verificationUrl }}
            </div>
            
            <h3>What happens next?</h3>
            <ol>
                <li><strong>Email Verification:</strong> Click the verification link above</li>
                <li><strong>Admin Approval:</strong> Our team will review and approve your account</li>
                <li><strong>Start Bidding:</strong> Once approved, you can participate in auctions</li>
            </ol>
            
            <div class="info-box">
                <strong>🔒 Security Note:</strong> If you didn't create this account, please ignore this email. Your email address will not be added to our system.
            </div>
            
            <p>If you're having trouble with the verification process or have any questions, please contact our support team.</p>
        </div>
        
        <div class="footer">
            <p>
                <strong>ArRahnu Auction Online</strong><br>
                Islamic Auction Platform<br>
                <a href="mailto:support@arrahnu-auction.com">support@arrahnu-auction.com</a>
            </p>
            <p style="margin-top: 15px; font-size: 12px; color: #999;">
                This email was sent to {{ $user->email }}. If you received this email by mistake, please ignore it.
            </p>
        </div>
    </div>
</body>
</html> 