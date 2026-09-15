<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Deposit Status Update</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #0c1017; color: #e2e8f0; margin: 0; padding: 20px; }
        .email-card { max-width: 600px; margin: 0 auto; background: #141923; border: 1px solid rgba(245, 185, 27, 0.3); border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .header { background: linear-gradient(135deg, #081510 0%, #1a2332 100%); padding: 30px 20px; text-align: center; border-bottom: 2px solid #f3ca52; }
        .logo-title { font-size: 26px; font-weight: 900; color: #f3ca52; letter-spacing: 2px; text-transform: uppercase; margin: 0; }
        .sub-logo { color: #94a3b8; font-size: 12px; margin-top: 5px; text-transform: uppercase; letter-spacing: 1px; }
        .content { padding: 30px 25px; }
        .greeting { font-size: 20px; font-weight: 700; color: #ffffff; margin-bottom: 15px; }
        .message { font-size: 14px; color: #cbd5e1; line-height: 1.6; margin-bottom: 25px; }
        .details-box { background: #0b0f17; border: 1px solid rgba(243, 202, 82, 0.2); border-radius: 12px; padding: 20px; margin-bottom: 25px; }
        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 13px; }
        .detail-row:last-child { border-bottom: none; }
        .label { color: #94a3b8; font-weight: 600; }
        .value { color: #f3ca52; font-weight: 700; font-family: monospace; }
        .btn-container { text-align: center; margin: 30px 0 10px 0; }
        .btn { background: linear-gradient(135deg, #f5b91b 0%, #f3ca52 100%); color: #000000; text-decoration: none; padding: 14px 32px; border-radius: 30px; font-weight: 900; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; display: inline-block; box-shadow: 0 4px 15px rgba(245, 185, 27, 0.3); }
        .footer { background: #0b0f17; padding: 20px; text-align: center; font-size: 11px; color: #64748b; border-top: 1px solid rgba(255,255,255,0.05); }
    </style>
</head>
<body>
    <div class="email-card">
        <div class="header">
            <h1 class="logo-title">⚡ DEX TRADE</h1>
            <div class="sub-logo">Add Fund / Deposit Notification</div>
        </div>

        <div class="content">
            <div class="greeting">Hello, {{ $user->name }}!</div>
            <div class="message">
                @if($event === 'approved')
                    Your deposit request of <strong>${{ number_format($deposit->amount, 2) }} USDT</strong> has been <strong>APPROVED</strong> and credited to your Deposit Wallet!
                @elseif($event === 'rejected')
                    Your deposit request of <strong>${{ number_format($deposit->amount, 2) }} USDT</strong> has been marked as <strong>REJECTED</strong>.
                @else
                    Your deposit request of <strong>${{ number_format($deposit->amount, 2) }} USDT</strong> has been received and is currently being processed.
                @endif
            </div>

            <div class="details-box">
                <div class="detail-row">
                    <span class="label">Deposit Ref:</span>
                    <span class="value">{{ $deposit->deposit_ref ?? 'DEP-'.$deposit->id }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Amount:</span>
                    <span class="value" style="font-size:16px;">${{ number_format($deposit->amount, 2) }} USDT</span>
                </div>
                <div class="detail-row">
                    <span class="label">Payment Method:</span>
                    <span class="value" style="color:#ffffff;">{{ $deposit->payment_gateway ?? 'USDT (BEP20)' }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Status:</span>
                    <span class="value" style="color: {{ $deposit->status === 'approved' ? '#34d399' : ($deposit->status === 'rejected' ? '#f43f5e' : '#f59e0b') }}; uppercase;">
                        {{ strtoupper($deposit->status) }}
                    </span>
                </div>
                <div class="detail-row">
                    <span class="label">Date & Time:</span>
                    <span class="value" style="color:#ffffff;">{{ $deposit->created_at ? $deposit->created_at->format('M d, Y h:i A') : date('M d, Y') }}</span>
                </div>
            </div>

            <div class="btn-container">
                <a href="{{ url('/user/deposits/history') }}" class="btn">View Deposit History &rarr;</a>
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} DexTrade. All Rights Reserved.<br>
            This is an automated system email notification. Please do not reply directly.
        </div>
    </div>
</body>
</html>
