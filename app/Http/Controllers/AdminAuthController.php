<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminLoginRequest;
use App\Mail\AdminAccountLocked;
use App\Models\Admin;
use App\Models\AdminStatus;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminAuthController extends Controller
{
    protected $logger;

    public function __construct(ActivityLogger $logger)
    {
        $this->logger = $logger;
    }

    private int $maxAttempts = 5;

    private int $lockMinutes = 15;

    public function login(AdminLoginRequest $request)
    {
        // Always return generic error for security; only act on existing admin
        $admin = Admin::where('email', $request->email)->first();

        if ($admin && $admin->status == AdminStatus::INACTIVE->value) {
            $this->logger->logAuthEvent('login_inactive', $admin, ['admin_id' => $admin->id]);

            return response()->json([
                'status' => false,
                'message' => 'Account inactive. Please contact site administrator.',

            ], 423);
        }

        // If the account exists, check lock status first
        if ($admin && $admin->locked_until && now()->lessThan($admin->locked_until)) {
            $minutesLeft = now()->diffInMinutes($admin->locked_until) + 1;
            $this->logger->logAuthEvent('login_blocked_locked', $admin, ['minutes_left' => $minutesLeft]);

            return response()->json([
                'success' => false,
                'message' => "Account locked. Try again in {$minutesLeft} minute(s).",
            ], 423);
        }

        // Normal credential check
        if (! $admin || ! Hash::check($request->password, $admin->password)) {
            // If admin exists, count failure and possibly lock
            if ($admin) {
                $admin->failed_attempts = ($admin->failed_attempts ?? 0) + 1;

                if ($admin->failed_attempts >= $this->maxAttempts) {
                    $admin->failed_attempts = 0;
                    $admin->locked_until = now()->addMinutes($this->lockMinutes);
                    $admin->save();

                    $this->logger->logAuthEvent('account_locked', $admin, [
                        'locked_until' => $admin->locked_until,
                        'window_minutes' => $this->lockMinutes,
                    ]);

                    // Alert Karan
                    Mail::to('karan@thewebgeeks.ca')->send(new AdminAccountLocked($admin, $this->lockMinutes));

                    return response()->json([
                        'success' => false,
                        'message' => "Too many failed attempts. Account locked for {$this->lockMinutes} minutes.",
                    ], 423);
                }

                $admin->save();

                $attemptsLeft = $this->maxAttempts - $admin->failed_attempts;
                $this->logger->logAuthEvent('failed_login', $admin, [
                    'email' => $request->email,
                    'attempts_left' => $attemptsLeft,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => "Invalid credentials. {$attemptsLeft} attempt(s) remaining.",
                ], 401);
            }

            // Unknown user: generic message, no counters (avoid user enumeration)
            $this->logger->logAuthEvent('failed_login_unknown_user', null, [
                'email_attempted' => $request->email]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        // Success: reset counters and lock
        $admin->failed_attempts = 0;
        $admin->locked_until = null;
        $admin->save();

        Auth::login($admin);
        $request->session()->regenerate();

        $this->logger->logAuthEvent('login', $admin);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'admin' => [
                'name' => $admin->name,
                'email' => $admin->email,
                'can_create' => $admin->can_create,
                'can_delete' => $admin->can_delete,
                'can_update' => $admin->can_update,
                'can_read' => $admin->can_read,
            ],
        ]);
    }

    /**
     * Handle admin login using session & CSRF protection.
     */
    // public function login(AdminLoginRequest $request)
    // {
    //     $admin = Admin::where('email', $request->email)->first();

    //     if (! $admin || ! Hash::check($request->password, $admin->password)) {
    //         // log failed attempt
    //         $this->logger->logAuthEvent('failed_login', $admin, [
    //             'email' => $request->email,
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid credentials',
    //         ], 401);
    //     }

    //     Auth::login($admin);

    //     $this->logger->logAuthEvent('login', $admin);

    //     $request->session()->regenerate();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Login successful',
    //         'admin' => [
    //             'name' => $admin->name,
    //             'email' => $admin->email,
    //         ],
    //     ]);
    // }

    public function logout(Request $request)
    {
        $admin = Auth::user();

        if ($admin) {
            $this->logger->logAuthEvent('logout', $admin);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Logged out successfully',
        // ]);
        return redirect('/');
    }
}
