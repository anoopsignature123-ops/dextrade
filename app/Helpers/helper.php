<?php

use App\Services\EmailService;

if (! function_exists('send_template_email')) {
    /**
     * Send a reusable database email template by key.
     *
     * Example: send_template_email('welcome-user', $user->email, ['name' => $user->name, 'login_url' => route('login')]);
     */
    function send_template_email(string $templateKey, $to, array $data = [], array $options = []): bool
    {
        return app(EmailService::class)->send($templateKey, $to, $data, $options);
    }
}
