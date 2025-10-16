<?php

namespace App\Mail;

use App\Models\User;
use App\Models\EmailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewMemberSignUp extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $member) {}

    public function build(): self
    {
        // 1) Load the template seeded/edited in admin
        $tpl = EmailNotification::where('name', 'New Member Sign Up')->first();

        // 2) Replace tokens like {{member_name}}, {{app_name}}, {{email}}
        $vars = [
            'app_name'    => config('app.name'),
            'member_name' => $this->member->name,
            'email'       => $this->member->email,
        ];
        $render = static function (string $text) use ($vars) {
            return preg_replace_callback(
                '/\{\{\s*([a-zA-Z0-9_]+)\s*\}\}/',
                fn($m) => $vars[$m[1]] ?? $m[0],
                $text
            );
        };

        // 3) Subject + HTML body (DB-driven only)
        $subject = $tpl ? $render($tpl->subject) : ('Welcome to '.config('app.name'));
        $html    = $tpl ? $render($tpl->body)    : '<p>Welcome!</p>';

        // Send raw HTML (no Blade view)
        return $this->subject($subject)->html($html);
    }
}
