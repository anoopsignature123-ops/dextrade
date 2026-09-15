<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Welcome User - DexTrade',
                'key' => 'welcome-user',
                'subject' => 'Welcome to {{ site_name }} - Registration Successful',
                'body' => <<<'HTML'
<div style="background:#0c1017;padding:25px 10px;font-family:'Segoe UI',Arial,sans-serif;color:#e2e8f0;">
    <div style="max-width:600px;margin:auto;background:#141923;border:1px solid rgba(245,185,27,0.3);border-radius:16px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.5);">
        <div style="background:linear-gradient(135deg,#081510,#142a20);padding:30px 20px;text-align:center;border-bottom:2px solid #f3ca52;">
            <h1 style="margin:0;color:#f3ca52;font-size:26px;font-weight:900;letter-spacing:2px;">⚡ {{ site_name }}</h1>
            <p style="margin:5px 0 0 0;color:#94a3b8;font-size:12px;text-transform:uppercase;letter-spacing:1px;">Official Member Registration</p>
        </div>
        <div style="padding:30px 25px;">
            <h2 style="color:#ffffff;margin-top:0;font-size:20px;">Welcome aboard, {{ name }}! 🎉</h2>
            <p style="font-size:14px;color:#cbd5e1;line-height:1.6;">Thank you for registering on <strong style="color:#f3ca52;">{{ site_name }}</strong>. Your member account has been created successfully.</p>
            <div style="background:#0b0f17;border:1px solid rgba(243,202,82,0.2);border-radius:12px;padding:20px;margin:20px 0;">
                <h3 style="margin-top:0;margin-bottom:12px;color:#f3ca52;font-size:15px;">🔐 Account Credentials</h3>
                <table width="100%" cellpadding="6" cellspacing="0" border="0" style="font-size:13px;">
                    <tr><td width="130" style="color:#94a3b8;"><strong>Name:</strong></td><td style="color:#ffffff;">{{ name }}</td></tr>
                    <tr><td style="color:#94a3b8;"><strong>Email:</strong></td><td style="color:#ffffff;">{{ email }}</td></tr>
                    <tr><td style="color:#94a3b8;"><strong>Referral Code:</strong></td><td style="color:#f3ca52;font-family:monospace;font-weight:bold;">{{ referral_code }}</td></tr>
                    <tr><td style="color:#94a3b8;"><strong>Sponsor ID:</strong></td><td style="color:#f3ca52;font-family:monospace;font-weight:bold;">{{ sponsor_code }}</td></tr>
                    <tr><td style="color:#94a3b8;"><strong>Leg Position:</strong></td><td style="color:#ffffff;text-transform:uppercase;">{{ position }}</td></tr>
                </table>
            </div>
            <div style="text-align:center;margin:30px 0 10px 0;">
                <a href="{{ login_url }}" style="background:linear-gradient(135deg,#f5b91b,#f3ca52);color:#000000;text-decoration:none;padding:14px 32px;border-radius:30px;font-weight:900;font-size:14px;text-transform:uppercase;display:inline-block;">Login to Member Portal &rarr;</a>
            </div>
        </div>
        <div style="background:#0b0f17;padding:20px;text-align:center;font-size:11px;color:#64748b;">
            &copy; {{ site_name }}. All Rights Reserved.
        </div>
    </div>
</div>
HTML
                ,
                'variables' => ['name', 'email', 'referral_code', 'sponsor_code', 'position', 'login_url', 'site_name'],
                'is_active' => true,
            ],
            [
                'name' => 'Package Investment Confirmed - DexTrade',
                'key' => 'package-purchased-user',
                'subject' => 'Investment Confirmed - ${{ amount }} Package Activated on {{ site_name }}',
                'body' => <<<'HTML'
<div style="background:#0c1017;padding:25px 10px;font-family:'Segoe UI',Arial,sans-serif;color:#e2e8f0;">
    <div style="max-width:600px;margin:auto;background:#141923;border:1px solid rgba(52,211,153,0.4);border-radius:16px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.5);">
        <div style="background:linear-gradient(135deg,#081510,#064e3b);padding:30px 20px;text-align:center;border-bottom:2px solid #34d399;">
            <h1 style="margin:0;color:#34d399;font-size:26px;font-weight:900;letter-spacing:2px;">⚡ {{ site_name }}</h1>
            <p style="margin:5px 0 0 0;color:#a7f3d0;font-size:12px;text-transform:uppercase;letter-spacing:1px;">Package Activation Confirmed</p>
        </div>
        <div style="padding:30px 25px;">
            <h2 style="color:#ffffff;margin-top:0;font-size:20px;">Congratulations, {{ name }}! 🚀</h2>
            <p style="font-size:14px;color:#cbd5e1;line-height:1.6;">Your package investment of <strong style="color:#34d399;">${{ amount }} USD</strong> has been successfully activated. Your account is now fully active for 0.5% Daily ROI yield.</p>
            <div style="background:#0b0f17;border:1px solid rgba(52,211,153,0.2);border-radius:12px;padding:20px;margin:20px 0;">
                <h3 style="margin-top:0;margin-bottom:12px;color:#34d399;font-size:15px;">📊 Investment Contract Details</h3>
                <table width="100%" cellpadding="6" cellspacing="0" border="0" style="font-size:13px;">
                    <tr><td width="140" style="color:#94a3b8;"><strong>Invested Amount:</strong></td><td style="color:#34d399;font-weight:bold;font-size:16px;">${{ amount }} USD</td></tr>
                    <tr><td style="color:#94a3b8;"><strong>Daily ROI Yield:</strong></td><td style="color:#34d399;">0.50% Daily (${{ daily_roi_amount }}/day)</td></tr>
                    <tr><td style="color:#94a3b8;"><strong>Duration & Return:</strong></td><td style="color:#ffffff;">400 Days (200% Total Return)</td></tr>
                    <tr><td style="color:#94a3b8;"><strong>Account Status:</strong></td><td style="color:#34d399;font-weight:bold;">ACTIVE MEMBER</td></tr>
                </table>
            </div>
            <div style="text-align:center;margin:30px 0 10px 0;">
                <a href="{{ dashboard_url }}" style="background:linear-gradient(135deg,#10b981,#34d399);color:#000000;text-decoration:none;padding:14px 32px;border-radius:30px;font-weight:900;font-size:14px;text-transform:uppercase;display:inline-block;">View Earnings Dashboard &rarr;</a>
            </div>
        </div>
        <div style="background:#0b0f17;padding:20px;text-align:center;font-size:11px;color:#64748b;">
            &copy; {{ site_name }}. All Rights Reserved.
        </div>
    </div>
</div>
HTML
                ,
                'variables' => ['name', 'amount', 'daily_roi_amount', 'dashboard_url', 'site_name'],
                'is_active' => true,
            ],
            [
                'name' => 'Add Fund / Deposit Created - DexTrade',
                'key' => 'deposit-created-user',
                'subject' => 'Deposit Request Received - ${{ amount }} USDT on {{ site_name }}',
                'body' => <<<'HTML'
<div style="background:#0c1017;padding:25px 10px;font-family:'Segoe UI',Arial,sans-serif;color:#e2e8f0;">
    <div style="max-width:600px;margin:auto;background:#141923;border:1px solid rgba(245,185,27,0.3);border-radius:16px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.5);">
        <div style="background:linear-gradient(135deg,#081510,#1a2332);padding:30px 20px;text-align:center;border-bottom:2px solid #f3ca52;">
            <h1 style="margin:0;color:#f3ca52;font-size:26px;font-weight:900;letter-spacing:2px;">⚡ {{ site_name }}</h1>
            <p style="margin:5px 0 0 0;color:#94a3b8;font-size:12px;text-transform:uppercase;letter-spacing:1px;">Add Fund Request Created</p>
        </div>
        <div style="padding:30px 25px;">
            <h2 style="color:#ffffff;margin-top:0;font-size:20px;">Hello, {{ name }}!</h2>
            <p style="font-size:14px;color:#cbd5e1;line-height:1.6;">Your deposit request of <strong style="color:#f3ca52;">${{ amount }} USDT</strong> has been received and is being processed.</p>
            <div style="background:#0b0f17;border:1px solid rgba(243,202,82,0.2);border-radius:12px;padding:20px;margin:20px 0;">
                <table width="100%" cellpadding="6" cellspacing="0" border="0" style="font-size:13px;">
                    <tr><td width="130" style="color:#94a3b8;"><strong>Deposit Ref:</strong></td><td style="color:#f3ca52;font-family:monospace;">{{ deposit_ref }}</td></tr>
                    <tr><td style="color:#94a3b8;"><strong>Amount:</strong></td><td style="color:#f3ca52;font-weight:bold;font-size:16px;">${{ amount }} USDT</td></tr>
                    <tr><td style="color:#94a3b8;"><strong>Gateway:</strong></td><td style="color:#ffffff;">{{ gateway }}</td></tr>
                    <tr><td style="color:#94a3b8;"><strong>Status:</strong></td><td style="color:#f59e0b;font-weight:bold;">PENDING VERIFICATION</td></tr>
                </table>
            </div>
        </div>
        <div style="background:#0b0f17;padding:20px;text-align:center;font-size:11px;color:#64748b;">
            &copy; {{ site_name }}. All Rights Reserved.
        </div>
    </div>
</div>
HTML
                ,
                'variables' => ['name', 'amount', 'deposit_ref', 'gateway', 'site_name'],
                'is_active' => true,
            ],
            [
                'name' => 'Deposit Approved - DexTrade',
                'key' => 'deposit-approved-user',
                'subject' => 'Deposit Approved - ${{ amount }} USDT Credited on {{ site_name }}',
                'body' => <<<'HTML'
<div style="background:#0c1017;padding:25px 10px;font-family:'Segoe UI',Arial,sans-serif;color:#e2e8f0;">
    <div style="max-width:600px;margin:auto;background:#141923;border:1px solid rgba(52,211,153,0.4);border-radius:16px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.5);">
        <div style="background:linear-gradient(135deg,#081510,#064e3b);padding:30px 20px;text-align:center;border-bottom:2px solid #34d399;">
            <h1 style="margin:0;color:#34d399;font-size:26px;font-weight:900;letter-spacing:2px;">⚡ {{ site_name }}</h1>
            <p style="margin:5px 0 0 0;color:#a7f3d0;font-size:12px;text-transform:uppercase;letter-spacing:1px;">Deposit Approved & Credited</p>
        </div>
        <div style="padding:30px 25px;">
            <h2 style="color:#ffffff;margin-top:0;font-size:20px;">Great News, {{ name }}! 💰</h2>
            <p style="font-size:14px;color:#cbd5e1;line-height:1.6;">Your deposit of <strong style="color:#34d399;">${{ amount }} USDT</strong> has been APPROVED and credited to your Deposit Wallet balance!</p>
            <div style="background:#0b0f17;border:1px solid rgba(52,211,153,0.2);border-radius:12px;padding:20px;margin:20px 0;">
                <table width="100%" cellpadding="6" cellspacing="0" border="0" style="font-size:13px;">
                    <tr><td width="130" style="color:#94a3b8;"><strong>Deposit Ref:</strong></td><td style="color:#34d399;font-family:monospace;">{{ deposit_ref }}</td></tr>
                    <tr><td style="color:#94a3b8;"><strong>Amount Credited:</strong></td><td style="color:#34d399;font-weight:bold;font-size:16px;">${{ amount }} USDT</td></tr>
                    <tr><td style="color:#94a3b8;"><strong>Status:</strong></td><td style="color:#34d399;font-weight:bold;">APPROVED & CREDITED</td></tr>
                </table>
            </div>
            <div style="text-align:center;margin:30px 0 10px 0;">
                <a href="{{ packages_url }}" style="background:linear-gradient(135deg,#10b981,#34d399);color:#000000;text-decoration:none;padding:14px 32px;border-radius:30px;font-weight:900;font-size:14px;text-transform:uppercase;display:inline-block;">Invest in Packages Now &rarr;</a>
            </div>
        </div>
        <div style="background:#0b0f17;padding:20px;text-align:center;font-size:11px;color:#64748b;">
            &copy; {{ site_name }}. All Rights Reserved.
        </div>
    </div>
</div>
HTML
                ,
                'variables' => ['name', 'amount', 'deposit_ref', 'packages_url', 'site_name'],
                'is_active' => true,
            ],
            [
                'name' => 'Support Ticket Created - DexTrade',
                'key' => 'support-ticket-created-user',
                'subject' => 'Support Ticket #{{ ticket_number }} Created on {{ site_name }}',
                'body' => <<<'HTML'
<div style="background:#0c1017;padding:25px 10px;font-family:'Segoe UI',Arial,sans-serif;color:#e2e8f0;">
    <div style="max-width:600px;margin:auto;background:#141923;border:1px solid rgba(59,130,246,0.4);border-radius:16px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.5);">
        <div style="background:linear-gradient(135deg,#081510,#1e3a8a);padding:30px 20px;text-align:center;border-bottom:2px solid #60a5fa;">
            <h1 style="margin:0;color:#60a5fa;font-size:26px;font-weight:900;letter-spacing:2px;">⚡ {{ site_name }}</h1>
            <p style="margin:5px 0 0 0;color:#93c5fd;font-size:12px;text-transform:uppercase;letter-spacing:1px;">Helpdesk Support Ticket</p>
        </div>
        <div style="padding:30px 25px;">
            <h2 style="color:#ffffff;margin-top:0;font-size:20px;">Hello, {{ name }}!</h2>
            <p style="font-size:14px;color:#cbd5e1;line-height:1.6;">Your Support Ticket <strong style="color:#60a5fa;">#{{ ticket_number }}</strong> has been created successfully. Our support team will review your inquiry shortly.</p>
            <div style="background:#0b0f17;border:1px solid rgba(96,165,250,0.2);border-radius:12px;padding:20px;margin:20px 0;">
                <table width="100%" cellpadding="6" cellspacing="0" border="0" style="font-size:13px;">
                    <tr><td width="130" style="color:#94a3b8;"><strong>Ticket Number:</strong></td><td style="color:#60a5fa;font-family:monospace;font-weight:bold;">#{{ ticket_number }}</td></tr>
                    <tr><td style="color:#94a3b8;"><strong>Subject:</strong></td><td style="color:#ffffff;">{{ subject }}</td></tr>
                    <tr><td style="color:#94a3b8;"><strong>Category:</strong></td><td style="color:#ffffff;text-transform:uppercase;">{{ category }}</td></tr>
                    <tr><td style="color:#94a3b8;"><strong>Priority:</strong></td><td style="color:#f3ca52;text-transform:uppercase;">{{ priority }}</td></tr>
                </table>
            </div>
            <div style="text-align:center;margin:30px 0 10px 0;">
                <a href="{{ ticket_url }}" style="background:linear-gradient(135deg,#3b82f6,#60a5fa);color:#ffffff;text-decoration:none;padding:14px 32px;border-radius:30px;font-weight:900;font-size:14px;text-transform:uppercase;display:inline-block;">View Support Ticket &rarr;</a>
            </div>
        </div>
        <div style="background:#0b0f17;padding:20px;text-align:center;font-size:11px;color:#64748b;">
            &copy; {{ site_name }}. All Rights Reserved.
        </div>
    </div>
</div>
HTML
                ,
                'variables' => ['name', 'ticket_number', 'subject', 'category', 'priority', 'ticket_url', 'site_name'],
                'is_active' => true,
            ],
            [
                'name' => 'Support Ticket Reply - DexTrade',
                'key' => 'support-ticket-reply-user',
                'subject' => 'New Admin Reply on Support Ticket #{{ ticket_number }} - {{ site_name }}',
                'body' => <<<'HTML'
<div style="background:#0c1017;padding:25px 10px;font-family:'Segoe UI',Arial,sans-serif;color:#e2e8f0;">
    <div style="max-width:600px;margin:auto;background:#141923;border:1px solid rgba(59,130,246,0.4);border-radius:16px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.5);">
        <div style="background:linear-gradient(135deg,#081510,#1e3a8a);padding:30px 20px;text-align:center;border-bottom:2px solid #60a5fa;">
            <h1 style="margin:0;color:#60a5fa;font-size:26px;font-weight:900;letter-spacing:2px;">⚡ {{ site_name }}</h1>
            <p style="margin:5px 0 0 0;color:#93c5fd;font-size:12px;text-transform:uppercase;letter-spacing:1px;">New Support Response</p>
        </div>
        <div style="padding:30px 25px;">
            <h2 style="color:#ffffff;margin-top:0;font-size:20px;">Hello, {{ name }}!</h2>
            <p style="font-size:14px;color:#cbd5e1;line-height:1.6;">Our support team has posted a reply to your Support Ticket <strong style="color:#60a5fa;">#{{ ticket_number }}</strong>.</p>
            <div style="background:#0b0f17;border:1px solid rgba(96,165,250,0.2);border-radius:12px;padding:20px;margin:20px 0;">
                <table width="100%" cellpadding="6" cellspacing="0" border="0" style="font-size:13px;">
                    <tr><td width="130" style="color:#94a3b8;"><strong>Ticket Number:</strong></td><td style="color:#60a5fa;font-family:monospace;font-weight:bold;">#{{ ticket_number }}</td></tr>
                    <tr><td style="color:#94a3b8;"><strong>Subject:</strong></td><td style="color:#ffffff;">{{ subject }}</td></tr>
                </table>
                <div style="background:#182232;border-left:4px solid #60a5fa;padding:15px;border-radius:8px;margin-top:15px;font-size:13px;color:#e2e8f0;font-style:italic;">
                    "{{ message_snippet }}"
                </div>
            </div>
            <div style="text-align:center;margin:30px 0 10px 0;">
                <a href="{{ ticket_url }}" style="background:linear-gradient(135deg,#3b82f6,#60a5fa);color:#ffffff;text-decoration:none;padding:14px 32px;border-radius:30px;font-weight:900;font-size:14px;text-transform:uppercase;display:inline-block;">Read Reply & Respond &rarr;</a>
            </div>
        </div>
        <div style="background:#0b0f17;padding:20px;text-align:center;font-size:11px;color:#64748b;">
            &copy; {{ site_name }}. All Rights Reserved.
        </div>
    </div>
</div>
HTML
                ,
                'variables' => ['name', 'ticket_number', 'subject', 'message_snippet', 'ticket_url', 'site_name'],
                'is_active' => true,
            ],
        ];

        foreach ($templates as $data) {
            EmailTemplate::updateOrCreate(
                ['key' => $data['key']],
                $data
            );
        }
    }
}
