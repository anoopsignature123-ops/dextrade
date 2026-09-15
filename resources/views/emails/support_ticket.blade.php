<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Support Ticket Notification</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #0c1017; color: #e2e8f0; margin: 0; padding: 20px; }
        .email-card { max-width: 600px; margin: 0 auto; background: #141923; border: 1px solid rgba(59, 130, 246, 0.4); border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .header { background: linear-gradient(135deg, #081510 0%, #1e3a8a 100%); padding: 30px 20px; text-align: center; border-bottom: 2px solid #60a5fa; }
        .logo-title { font-size: 26px; font-weight: 900; color: #60a5fa; letter-spacing: 2px; text-transform: uppercase; margin: 0; }
        .sub-logo { color: #93c5fd; font-size: 12px; margin-top: 5px; text-transform: uppercase; letter-spacing: 1px; }
        .content { padding: 30px 25px; }
        .greeting { font-size: 20px; font-weight: 700; color: #ffffff; margin-bottom: 15px; }
        .message { font-size: 14px; color: #cbd5e1; line-height: 1.6; margin-bottom: 25px; }
        .details-box { background: #0b0f17; border: 1px solid rgba(96, 165, 250, 0.2); border-radius: 12px; padding: 20px; margin-bottom: 25px; }
        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 13px; }
        .detail-row:last-child { border-bottom: none; }
        .label { color: #94a3b8; font-weight: 600; }
        .value { color: #60a5fa; font-weight: 700; font-family: monospace; }
        .msg-text-box { background: #182232; border-left: 4px solid #60a5fa; padding: 15px; border-radius: 8px; font-size: 13px; color: #e2e8f0; margin-top: 15px; font-style: italic; }
        .btn-container { text-align: center; margin: 30px 0 10px 0; }
        .btn { background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 30px; font-weight: 900; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; display: inline-block; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3); }
        .footer { background: #0b0f17; padding: 20px; text-align: center; font-size: 11px; color: #64748b; border-top: 1px solid rgba(255,255,255,0.05); }
    </style>
</head>
<body>
    <div class="email-card">
        <div class="header">
            <h1 class="logo-title">⚡ DEX TRADE</h1>
            <div class="sub-logo">Helpdesk Support Notification</div>
        </div>

        <div class="content">
            <div class="greeting">Hello, {{ $user->name }}!</div>
            <div class="message">
                @if($event === 'admin_reply')
                    Our support team has posted a reply to your Support Ticket <strong>#{{ $ticket->ticket_number }}</strong>.
                @else
                    Your Support Ticket <strong>#{{ $ticket->ticket_number }}</strong> has been created successfully. Our team will review your inquiry shortly.
                @endif
            </div>

            <div class="details-box">
                <div class="detail-row">
                    <span class="label">Ticket Number:</span>
                    <span class="value">#{{ $ticket->ticket_number }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Subject:</span>
                    <span class="value" style="color:#ffffff;">{{ $ticket->subject }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Category:</span>
                    <span class="value" style="text-transform:uppercase;">{{ $ticket->category }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Priority:</span>
                    <span class="value" style="text-transform:uppercase; color:#f5b91b;">{{ $ticket->priority }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Status:</span>
                    <span class="value" style="text-transform:uppercase;">{{ $ticket->status }}</span>
                </div>

                @if($messageText)
                    <div class="msg-text-box">
                        "{{ $messageText }}"
                    </div>
                @endif
            </div>

            <div class="btn-container">
                <a href="{{ url('/user/tickets/' . $ticket->id) }}" class="btn">View Support Ticket &rarr;</a>
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} DexTrade. All Rights Reserved.<br>
            This is an automated system email notification. Please do not reply directly.
        </div>
    </div>
</body>
</html>
