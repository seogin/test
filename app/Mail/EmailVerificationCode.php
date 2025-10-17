<?php

namespace App\Mail;

use App\Models\EmailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerificationCode extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly string $verificationCode) {}

    public function build(): self
    {
        $template = EmailNotification::where('name', 'Email Verification')->first();

        $vars = [
            'app_name' => config('app.name'),
            'verification_code' => $this->verificationCode,
        ];

        $render = static function (string $text) use ($vars) {
            return preg_replace_callback(
                '/\{\{\s*([a-zA-Z0-9_]+)\s*\}\}/',
                fn($matches) => $vars[$matches[1]] ?? $matches[0],
                $text
            );
        };

        $subject = $template ? $render($template->subject) : 'Verify your email';
        $html = $template
            ? $render($template->body)
            : '<p>Your verification code is: <strong>' . $this->verificationCode . '</strong></p>';

        return $this->subject($subject)->html($html);
    }
}