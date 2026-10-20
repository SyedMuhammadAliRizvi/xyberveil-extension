<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Issue - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }
        .alert {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 16px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .alert.warning {
            background: #fffbeb;
            border: 1px solid #fed7aa;
            color: #92400e;
        }
        .plan-details {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .cta-button {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .cta-button:hover {
            background: #1d4ed8;
        }
        .secondary-button {
            display: inline-block;
            background: #6b7280;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin: 10px 10px 10px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            color: #6b7280;
            text-align: center;
        }
        .countdown {
            font-size: 18px;
            font-weight: 600;
            color: #dc2626;
            text-align: center;
            margin: 15px 0;
        }
        .provider-specific {
            background: #f0f9ff;
            border-left: 4px solid #3b82f6;
            padding: 16px;
            margin: 20px 0;
        }
        @media (max-width: 600px) {
            body { padding: 10px; }
            .container { padding: 20px; }
            .cta-button { display: block; width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ config('app.name') }}</div>
            <h1 style="color: #dc2626; margin: 0;">{{ $hasGracePeriod ? 'Payment Issue' : 'Action Required' }}</h1>
        </div>

        <p>Hello {{ $userName }},</p>

        <div class="alert {{ $hasGracePeriod ? 'warning' : '' }}">
            <strong>{{ $hasGracePeriod ? 'We encountered a payment issue' : 'Your payment failed to process' }}</strong><br>
            {{ $hasGracePeriod ? 'Your service continues during the grace period, but action is needed.' : 'Please update your payment method to continue your service.' }}
        </div>

        <div class="plan-details">
            <h3 style="margin-top: 0;">Subscription Details</h3>
            <p><strong>Plan:</strong> {{ $planName }}</p>
            <p><strong>Amount:</strong> {{ $currency }} {{ $amount }}</p>
            <p><strong>Provider:</strong> {{ $provider }}</p>
            @if($hasGracePeriod && $gracePeriodEndsAt)
                <p><strong>Grace Period Ends:</strong> {{ $gracePeriodEndsAt->format('F j, Y \a\t g:i A T') }}</p>
            @else
                <p><strong>Service Ends:</strong> {{ $subscriptionEndsAt ? $subscriptionEndsAt->format('F j, Y \a\t g:i A T') : 'Soon' }}</p>
            @endif
        </div>

        @if($daysUntilExpiry >= 0)
            <div class="countdown">
                @if($hasGracePeriod)
                    {{ $daysUntilExpiry }} {{ Str::plural('day', $daysUntilExpiry) }} remaining in grace period
                @else
                    {{ $daysUntilExpiry }} {{ Str::plural('day', $daysUntilExpiry) }} until service ends
                @endif
            </div>
        @endif

        @if($isAppStore)
            <div class="provider-specific">
                <h4 style="margin-top: 0;">For App Store Subscriptions:</h4>
                <p>Since you subscribed through the Apple App Store, you'll need to update your payment method directly with Apple:</p>
                <ol>
                    <li>Open the <strong>Settings</strong> app on your iOS device</li>
                    <li>Tap your name at the top</li>
                    <li>Tap <strong>Media & Purchases</strong></li>
                    <li>Tap <strong>View Account</strong></li>
                    <li>Update your payment information</li>
                </ol>
                <p style="margin-bottom: 0;">Or visit your Apple ID account page on the web:</p>
            </div>
            <center>
                <a href="{{ $updatePaymentUrl }}" class="cta-button">Update Payment Method on Apple</a>
            </center>
        @elseif($isPaddle)
            <div class="provider-specific">
                <h4 style="margin-top: 0;">Update Your Payment Method</h4>
                <p>Click the button below to securely update your payment information:</p>
            </div>
            <center>
                <a href="{{ $updatePaymentUrl }}" class="cta-button">Update Payment Method</a>
            </center>
        @else
            <center>
                <a href="{{ $updatePaymentUrl }}" class="cta-button">Update Payment Method</a>
            </center>
        @endif

        <h3>What happens next?</h3>
        <ul>
            @if($hasGracePeriod)
                <li><strong>Your service continues</strong> during the grace period</li>
                <li>We'll automatically retry your payment</li>
                <li>Update your payment method to avoid service interruption</li>
                <li>If payment isn't resolved, access will end on {{ $gracePeriodEndsAt ? $gracePeriodEndsAt->format('F j') : 'the grace period end date' }}</li>
            @else
                <li>Your service continues until {{ $subscriptionEndsAt ? $subscriptionEndsAt->format('F j') : 'your current period ends' }}</li>
                <li>We'll continue trying to process payment for up to 60 days</li>
                <li>Update your payment method as soon as possible to maintain access</li>
                <li>No action needed if you want to cancel your subscription</li>
            @endif
        </ul>

        <h3>Common solutions:</h3>
        <ul>
            <li>Check if your card has expired or been replaced</li>
            <li>Ensure sufficient funds are available</li>
            <li>Contact your bank if payments are being declined</li>
            <li>Try a different payment method</li>
        </ul>

        <div style="text-align: center; margin: 30px 0;">
            <a href="mailto:{{ $supportEmail }}" class="secondary-button">Contact Support</a>
        </div>

        <div class="footer">
            <p>If you no longer wish to continue your subscription, no action is required and it will automatically expire.</p>
            <p>This email was sent regarding your {{ config('app.name') }} subscription. If you believe this is an error, please contact our support team.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>