<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminAuthLogController;
use App\Http\Controllers\MemberRegistrationController;
use App\Http\Controllers\AdminMemberController;
use App\Http\Controllers\MemberEmailVerificationController;

Route::get('/', fn() => view('admin.login'))->name('login');
Route::get('/login', fn() => view('admin.login'));
Route::post('/login', [AdminAuthController::class, 'login']);
Route::post('/members/signup', [MemberRegistrationController::class, 'store'])->name('members.signup');
Route::post('/members/email/verification', [MemberEmailVerificationController::class, 'send'])
    ->name('members.email.verification.send');
Route::post('/members/email/verification/verify', [MemberEmailVerificationController::class, 'verify']
)->name('members.email.verification.verify');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});


// 🧱 Protected routes

// Put all admin functions together
Route::middleware(['auth:sanctum'])
    ->prefix('admin')
    ->group(function () {

        // Dashboard data 
        Route::middleware('admin.permission:can_read')
            ->get('/getDashboardData', [AdminDashboardController::class, 'dashboard']);

        // Dashboard view
        Route::middleware('admin.permission:can_read')
            ->get('/dashboard', function () {
                return view('admin.dashboard');
            })->name('admin.dashboard');

        // Users list - pass admins to the view
        Route::middleware('admin.permission:can_read')
            ->get('/users', function() {
                // Include soft-deleted admins so 'inactive' users (soft-deleted) are visible in the list.
                $admins = \App\Models\Admin::withTrashed()->get();
                return view('admin.users.index', ['admins' => $admins]);
            })
            ->name('admin.users');

        // Add user page 
        Route::middleware('admin.permission:can_create')
            ->get('/add', function () {
                return view('admin.add_user.index');
            })->name('admin.add_user');

        // Handle add user form submission
        Route::middleware('admin.permission:can_create')
            ->post('/add', [AdminDashboardController::class, 'addUser'])
            ->name('admin.add.submit');

        // View user details (Read Only)
        Route::middleware('admin.permission:can_read')
            ->get('/users/{id}/view', function ($id) {
                $admin = \App\Models\Admin::findOrFail($id);
                return view('admin.view_user.index', ['admin' => $admin]);
            })
            ->name('admin.view_user');

        // Edit user (Form Page)
        Route::middleware('admin.permission:can_update')
            ->get('/users/{id}/edit', function ($id) {
                $admin = \App\Models\Admin::findOrFail($id);
                return view('admin.edit_user.index', ['admin' => $admin]);
            })
            ->name('admin.edit_user');

        // Update user by ID
        Route::middleware('admin.permission:can_update')
            ->put('/update/{id}', [AdminDashboardController::class, 'updateAdminById'])
            ->name('admin.update_user');

        // Delete user (solft delete)
        Route::middleware('admin.permission:can_delete')
            ->delete('/users/{id}/delete', [AdminDashboardController::class, 'deleteAdminById'])
            ->name('admin.delete_user');

        // Reactivate user (restore soft-deleted / set status active)
        Route::post('/users/{id}/reactivate', [AdminDashboardController::class, 'reactivateAdminById'])
            ->name('admin.reactivate_user');

        // Email Notifications settings page
        // Route::middleware('admin.permission:can_update')
        //     ->get('/email-notifications', function () {
        //         return view('admin.email-notifications.index');
        //     })->name('admin.email_notifications');
        Route::middleware('admin.permission:can_update')
            ->get('/email-notifications', function () {
                $notifications = \App\Models\EmailNotification::all();
                return view('admin.email-notifications.index', ['notifications' => $notifications]);
            })
            ->name('admin.email_notifications');
        
        // Edit emails
        Route::middleware('admin.permission:can_update')
            ->get('/email-notifications/{id}/edit', [\App\Http\Controllers\AdminEmailNotificationController::class, 'edit'])
            ->name('admin.edit_email_notification');

        // Update email notification
        Route::middleware('admin.permission:can_update')
            ->put('/email-notifications/{id}', [\App\Http\Controllers\AdminEmailNotificationController::class, 'update'])
            ->name('admin.update_email_notification');
        
        Route::middleware('admin.permission:can_read_members')
            ->get('/members', function() {
                // Include soft-deleted admins so 'inactive' users (soft-deleted) are visible in the list.
                $members = \App\Models\Member::withTrashed()->get();
                return view('admin.members.index', ['members' => $members]);
            })
            ->name('admin.members');
    });

    Route::middleware('admin.permission:can_create_members')->group(function() {
        Route::get('/admin/members/create', [AdminMemberController::class, 'create'])->name('admin.members.create');
        Route::post('/admin/members', [AdminMemberController::class, 'store'])->name('admin.members.store');
    });

    Route::middleware('admin.permission:can_update_members')->group(function() {
        Route::get('/admin/members/{member}/edit', [AdminMemberController::class, 'edit'])->name('admin.members.edit');
        Route::put('/admin/members/{member}', [AdminMemberController::class, 'update'])->name('admin.members.update');
    });

    Route::middleware('admin.permission:can_read_members')->group(function() {
        Route::get('/admin/members/{member}/view', [AdminMemberController::class, 'view'])->name('admin.members.view');
    });

Route::middleware(['auth:admin'])->prefix('admin')->group(function() {
    Route::get('/auth-logs', [AdminAuthLogController::class, 'index'])->name('admin.auth_logs');
});

// use App\Http\Controllers\AdminAuthController;
// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('admin.login');
// });

// Route::get('/dashboard', action: function () {
//     return view('admin.dashboard');
// });

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::prefix('admin')->group(function () {
//     Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
//     Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

//     Route::middleware('auth:admin')->group(function () {
//         Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');
//         Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
//     });
// });
