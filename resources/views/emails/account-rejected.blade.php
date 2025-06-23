<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account Application Update - ArRahnu Auction</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8d7da;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #ffffff;
            padding: 30px;
            border: 1px solid #dee2e6;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            font-size: 14px;
            color: #6c757d;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ArRahnu Auction Online</h1>
        <h2>Account Application Update</h2>
    </div>

    <div class="content">
        <p>Hello {{ $user->full_name }},</p>

        <p>Thank you for your interest in joining ArRahnu Auction Online. After careful review of your account application, we regret to inform you that we are unable to approve your account at this time.</p>

        @if($notes)
        <div class="info">
            <strong>Review Notes:</strong><br>
            {{ $notes }}
        </div>
        @endif

        <p><strong>What does this mean?</strong></p>
        <ul>
            <li>Your account registration was not approved based on our current criteria</li>
            <li>You will not be able to participate in auctions with this account</li>
            <li>Your personal information will be kept secure and confidential</li>
        </ul>

        <div class="warning">
            <strong>Important:</strong> This decision is based on our internal review process and may be due to various factors including documentation requirements, verification issues, or policy compliance.
        </div>

        <p><strong>What are your options?</strong></p>
        <ol>
            <li><strong>Contact Support:</strong> If you believe this decision was made in error or if you have additional information to provide, please contact our support team.</li>
            <li><strong>Reapply Later:</strong> You may be eligible to reapply in the future if circumstances change.</li>
            <li><strong>Provide Additional Documentation:</strong> Our support team can guide you on what additional information might be required.</li>
        </ol>

        <p><strong>Need Assistance?</strong></p>
        <p>Our support team is available to help clarify this decision and discuss potential next steps:</p>
        <ul>
            <li>📧 Email: <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a></li>
            <li>📞 Phone: +60 3-XXXX XXXX (Business hours: 9 AM - 6 PM, Monday - Friday)</li>
            <li>💬 Live Chat: Available on our website during business hours</li>
        </ul>

        <div style="text-align: center;">
            <a href="mailto:{{ $supportEmail }}" class="btn">Contact Support</a>
        </div>

        <p><strong>Privacy and Data Security:</strong></p>
        <p>Please be assured that all personal information you provided during the registration process will be handled in accordance with our privacy policy. Your data remains secure and will not be shared with third parties.</p>

        <p>We appreciate your understanding and thank you for your interest in ArRahnu Auction Online.</p>

        <p>Best regards,<br>
        The ArRahnu Team</p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} ArRahnu Auction Online. All rights reserved.</p>
        <p>This is an automated email. Please do not reply to this message.</p>
        <p>For support inquiries, please use the contact information provided above.</p>
    </div>
</body>
</html> 