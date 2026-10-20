<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trial Ending Soon</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .trial-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
        }
        .cta-button {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .cta-button:hover {
            background: #0056b3;
            color: white;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .urgent {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>⏰ Your Trial is Ending Soon</h1>
        <p>{{ config('app.name') }}</p>
    </div>

    <div class="content">
        <h2>Hello {{ $user->name }},</h2>

        <p>We wanted to remind you that your free trial is ending soon.</p>

        <div class="trial-info">
            <h3>📊 Trial Details</h3>
            <ul>
                <li><strong>Status:</strong> {{ $trialDetails['description'] }}</li>
                <li><strong>Days Remaining:</strong> <span class="urgent">{{ $daysRemaining }} days</span></li>
                <li><strong>Hours Remaining:</strong> <span class="urgent">{{ $hoursRemaining }} hours</span></li>
                <li><strong>Trial Ends:</strong> {{ $user->trial_ends_at->format('M j, Y \a\t g:i A') }}</li>
            </ul>
        </div>

        @if($daysRemaining <= 1)
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>⚠️ Urgent:</strong> Your trial expires in less than 24 hours!
        </div>
        @endif

        <p>Don't lose access to your VPN service! Upgrade to a paid plan today to continue enjoying:</p>

        <ul>
            <li>✅ Unlimited bandwidth and speed</li>
            <li>✅ Access to all server locations</li>
            <li>✅ 24/7 customer support</li>
            <li>✅ No interruptions to your service</li>
        </ul>

        <div style="text-align: center;">
            <a href="{{ config('app.url') }}/plans" class="cta-button">
                🚀 Upgrade Now
            </a>
        </div>

        <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>

        <p>Thank you for choosing {{ config('app.name') }}!</p>

        <p>Best regards,<br>
        The {{ config('app.name') }} Team</p>
    </div>

    <div class="footer">
        <p>This is an automated notification. Please do not reply to this email.</p>
        <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
