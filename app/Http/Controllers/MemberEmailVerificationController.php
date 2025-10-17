<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerificationCode as EmailVerificationCodeMail;
use App\Models\EmailVerificationCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MemberEmailVerificationController extends Controller
{
    public function send(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $verificationCode = str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(10);

        EmailVerificationCode::updateOrCreate(
            ['email' => $data['email']],
            [
                'code' => $verificationCode,
                'expires_at' => $expiresAt,
            ]
        );

        Mail::to($data['email'])->send(new EmailVerificationCodeMail($verificationCode));

        return response()->json([
            'success' => true,
            'message' => 'Verification code sent to email.',
            'data' => [
                'expires_at' => $expiresAt->toIso8601String(),
            ],
        ]);
    }

        public function verify(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required','string','email','max:255'],
            'code'  => ['required','string','size:8'],
        ]);

        $record = EmailVerificationCode::where('email', $data['email'])->first();

        if (!$record) {
            return response()->json(['success' => false, 'message' => 'Invalid code.'], 422);
        }

        if ($record->expires_at && now()->greaterThan($record->expires_at)) {
            return response()->json(['success' => false, 'message' => 'Code expired.'], 422);
        }

        if ($record->code !== $data['code']) {
            return response()->json(['success' => false, 'message' => 'Invalid code.'], 422);
        }

        DB::transaction(function () use ($data, $record) {
            if ($user = User::where('email', $data['email'])->first()) {
                $user->email_verified_at = now();
                $user->save();
            }
            $record->delete();
        });

        return response()->json(['success' => true, 'message' => 'Email verified.']);
    }
}