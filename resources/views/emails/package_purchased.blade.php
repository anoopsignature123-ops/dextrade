<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Package Investment Confirmed</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #0c1017; color: #e2e8f0; margin: 0; padding: 20px; }
        .email-card { max-width: 600px; margin: 0 auto; background: #141923; border: 1px solid rgba(52, 211, 153, 0.4); border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .header { background: linear-gradient(135deg, #081510 0%, #064e3b 100%); padding: 30px 20px; text-align: center; border-bottom: 2px solid #34d399; }
        .logo-title { font-size: 26px; font-weight: 900; color: #34d399; letter-spacing: 2px; text-transform: uppercase; margin: 0; }
        .sub-logo { color: #a7f3d0; font-size: 12px; margin-top: 5px; text-transform: uppercase; letter-spacing: 1px; }
        .content { padding: 30px 25px; }
        .greeting { font-size: 20px; font-weight: 700; color: #ffffff; margin-bottom: 15px; }
        .message { font-size: 14px; color: #cbd5e1; line-height: 1.6; margin-bottom: 25px; }
        .details-box { background: #0b0f17; border: 1px solid rgba(52, 211, 153, 0.2); border-radius: 12px; padding: 20px; margin-bottom: 25px; }
        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 13px; }
        .detail-row:last-child { border-bottom: none; }
        .label { color: #94a3b8; font-weight: 600; }
        .value { color: #34d399; font-weight: 700; font-family: monospace; }
        .btn-container { text-align: center; margin: 30px 0 10px 0; }
        .btn { background: linear-gradient(135deg, #10b981 0%, #34d399 100%); color: #000000; text-decoration: none; padding: 14px 32px; border-radius: 30px; font-weight: 900; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; display: inline-block; box-shadow: 0 4px 15px rgba(52, 211, 153, 0.3); }
        .footer { background: #0b0f17; padding: 20px; text-align: center; font-size: 11px; color: #64748b; border-top: 1px solid rgba(255,255,255,0.05); }
    </style>
</head>
<body>
    <div class="email-card">
        <div class="header">
            <h1 class="logo-title">⚡ DEX TRADE</h1>
            <div class="sub-logo">Package Activation Confirmed</div>
        </div>

        <div class="content">
            <div class="greeting">Congratulations, {{ $user->name }}! 🚀</div>
            <div class="message">
                Your package investment of <strong>${{ number_format($userPackage->invested_amount, 2) }} USD</strong> has been successfully activated! Your account is now fully <strong>ACTIVE</strong> and eligible for 0.5% Daily ROI Yield.
            </div>

            <div class="details-box">
                <div class="detail-row">
                    <span class="label">Investment Amount:</span>
                    <span class="value" style="font-size:16px;">${{ number_format($userPackage->invested_amount, 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Daily ROI Rate:</span>
                    <span class="value">0.50% Daily</span>
                </div>
                <div class="detail-row">
                    <span class="label">Daily ROI Amount:</span>
                    <span class="value">${{ number_format($userPackage->daily_roi_amount, 2) }} / day</span>
                </div>
                <div class="detail-row">
                    <span class="label">Contract Duration:</span>
                    <span class="value">400 Days (200% Total Return)</span>
                </div>
                <div class="detail-row">
                    <span class="label">Activation Date:</span>
                    <span class="value" style="color:#ffffff;">{{ $userPackage->purchased_at ? $userPackage->purchased_at->format('M d, Y h:i A') : date('M d, Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Account Status:</span>
                    <span class="value" style="color:#34d399;">ACTIVE MEMBER</span>
                </div>
            </div>

            <div class="btn-container">
                <a href="{{ url('/user/dashboard') }}" class="btn">View Earnings Dashboard &rarr;</a>
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} DexTrade. All Rights Reserved.<br>
            This is an automated system email notification. Please do not reply directly.
        </div>
    </div>
</body>
</html>
