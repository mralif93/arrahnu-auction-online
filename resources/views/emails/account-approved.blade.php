<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account Approved - Welcome to ArRahnu Auction</title>
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
            background-color: #d4edda;
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
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .btn:hover {
            background-color: #218838;
        }
        .success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .feature-list {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Congratulations!</h1>
        <h2>Your Account Has Been Approved</h2>
    </div>

    <div class="content">
        <p>Hello {{ $user->full_name }},</p>

        <div class="success">
            <strong>Great news!</strong> Your ArRahnu Auction Online account has been approved and is now active. You can start participating in auctions immediately!
        </div>

        <p>Your account details:</p>
        <ul>
            <li><strong>Username:</strong> {{ $user->username }}</li>
            <li><strong>Email:</strong> {{ $user->email }}</li>
            <li><strong>Role:</strong> {{ ucfirst($user->role) }}</li>
            <li><strong>Account Status:</strong> Active</li>
        </ul>

        <div style="text-align: center;">
            <a href="{{ $loginUrl }}" class="btn">Login to Your Account</a>
        </div>

        <div class="feature-list">
            <h3>What you can do now:</h3>
            <ul>
                <li>🏠 <strong>Browse Auctions:</strong> View all available properties and collateral items</li>
                <li>💰 <strong>Place Bids:</strong> Participate in live auctions and place competitive bids</li>
                <li>📱 <strong>Mobile Access:</strong> Use our mobile app for convenient bidding on the go</li>
                <li>📊 <strong>Track Activity:</strong> Monitor your bidding history and auction results</li>
                <li>🔔 <strong>Get Notifications:</strong> Receive updates about auctions you're interested in</li>
                <li>👤 <strong>Manage Profile:</strong> Update your information and preferences</li>
            </ul>
        </div>

        <p><strong>Getting Started Tips:</strong></p>
        <ol>
            <li>Complete your profile information for better auction experience</li>
            <li>Browse current auctions to familiarize yourself with the platform</li>
            <li>Set up your notification preferences</li>
            <li>Review auction terms and conditions</li>
        </ol>

        <p><strong>Need Help?</strong></p>
        <p>Our support team is here to help you get started:</p>
        <ul>
            <li>📧 Email: <a href="mailto:support@arrahnu.com">support@arrahnu.com</a></li>
            <li>📞 Phone: +60 3-XXXX XXXX (Business hours)</li>
            <li>💬 Live Chat: Available on our website</li>
            <li>📚 Help Center: Visit our FAQ section</li>
        </ul>

        <p>Welcome to the ArRahnu community! We're excited to have you on board and look forward to your participation in our auctions.</p>

        <p>Happy bidding!<br>
        The ArRahnu Team</p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} ArRahnu Auction Online. All rights reserved.</p>
        <p>This is an automated email. Please do not reply to this message.</p>
    </div>
</body>
</html> 