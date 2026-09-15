<?php

namespace App\Services;

use App\Models\EmailTemplate;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Send an active database template by its unique key.
     *
     * Available options: cc, bcc, reply_to, from, from_name, queue.
     */
    public function send(string $templateKey, $to, array $data = [], array $options = []): bool
    {
        $template = EmailTemplate::active()->where('key', $templateKey)->first();

        if (! $template) {
            Log::warning('EmailService: active email template was not found', ['template' => $templateKey]);

            return false;
        }

        $data = array_merge([
            'site_name' => config('app.name', 'DexTrade'),
            'site_url' => config('app.url', 'http://localhost'),
            'support_email' => config('mail.from.address', 'support@dextrade.com'),
            'logo' => asset('assets/images/logo.png'),
        ], $data);

        $subject = $this->render($template->subject, $data, $templateKey);
        $body = $this->render($template->body, $data, $templateKey);

        $recipients = [];
        if (is_string($to)) {
            $recipients[] = ['email' => $to];
        } elseif (is_array($to)) {
            foreach ($to as $k => $v) {
                if (is_int($k)) {
                    $recipients[] = ['email' => $v];
                } else {
                    $recipients[] = ['email' => $k, 'name' => $v];
                }
            }
        }

        $recipients = array_values(array_filter($recipients, fn (array $recipient) => filter_var($recipient['email'], FILTER_VALIDATE_EMAIL)));

        if (empty($recipients)) {
            Log::warning('EmailService: no valid recipient provided', ['template' => $templateKey]);

            return false;
        }

        // 1. Try ZeptoMail if API Key is configured
        $apiKey = config('services.zeptomail.api_key') ?: env('ZEPTO_MAIL_API_KEY');
        if (! empty($apiKey)) {
            $apiBase = rtrim(config('services.zeptomail.api_base', 'https://api.zeptomail.in/v1.1/email'), '/');
            $verify = config('services.zeptomail.verify', true);

            $payload = [
                'from' => [
                    'address' => $options['from'] ?? config('mail.from.address', 'noreply@dextrade.world'),
                    'name' => $options['from_name'] ?? config('mail.from.name', 'Dex Trade'),
                ],
                'to' => array_map(function ($recipient) {
                    return [
                        'email_address' => [
                            'address' => $recipient['email'],
                            'name' => $recipient['name'] ?? '',
                        ],
                    ];
                }, $recipients),
                'subject' => $subject,
                'htmlbody' => $body,
            ];

            try {
                Log::info('EmailService: attempting to send email via ZeptoMail', [
                    'template' => $templateKey,
                    'from' => $payload['from']['address'],
                    'to' => array_column($recipients, 'email'),
                    'subject' => $subject,
                ]);

                $res = Http::withHeaders([
                    'Authorization' => 'Zoho-enczapikey '.$apiKey,
                    'Content-Type' => 'application/json',
                ])->withOptions(['verify' => $verify])->post($apiBase, $payload);

                if ($res->successful()) {
                    Log::info('EmailService: email sent successfully via ZeptoMail', [
                        'template' => $templateKey,
                        'to' => array_column($recipients, 'email'),
                        'subject' => $subject,
                    ]);

                    return true;
                }

                Log::error('EmailService: failed sending email via ZeptoMail', [
                    'template' => $templateKey,
                    'from' => $payload['from']['address'],
                    'to' => array_column($recipients, 'email'),
                    'subject' => $subject,
                    'status' => $res->status(),
                    'response_body' => $res->json() ?? $res->body(),
                ]);
            } catch (\Throwable $e) {
                Log::error('EmailService: exception when sending email via ZeptoMail', [
                    'template' => $templateKey,
                    'from' => $payload['from']['address'] ?? null,
                    'to' => array_column($recipients, 'email'),
                    'exception' => $e->getMessage(),
                ]);
            }
        }

        // 2. Fallback to standard Laravel Mailer
        try {
            foreach ($recipients as $recipient) {
                Mail::html($body, function ($message) use ($recipient, $subject, $options) {
                    $message->to($recipient['email'], $recipient['name'] ?? '')
                        ->subject($subject);

                    if (isset($options['from'])) {
                        $message->from($options['from'], $options['from_name'] ?? config('mail.from.name'));
                    }
                });
            }

            Log::info('EmailService: email dispatched via Laravel Mailer', [
                'template' => $templateKey,
                'to' => array_column($recipients, 'email'),
                'subject' => $subject,
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('EmailService: exception when sending email via Laravel Mailer', [
                'template' => $templateKey,
                'to' => array_column($recipients, 'email'),
                'exception' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /** Render {{ variable }} placeholders with escaped values. */
    public function render(string $content, array $data, string $templateKey = 'email'): string
    {
        return (string) preg_replace_callback('/\{\{\s*([a-zA-Z0-9_.-]+)\s*\}\}/', function (array $match) use ($data) {
            $key = $match[1];

            if (! Arr::has($data, $key)) {
                return $match[0]; // Retain placeholder if missing instead of throwing exception
            }

            $value = Arr::get($data, $key);
            if (! is_scalar($value) && $value !== null) {
                return (string) json_encode($value);
            }

            return e((string) $value);
        }, $content);
    }
}
