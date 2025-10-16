<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AuthLog;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminAuthLogController extends Controller
{
    /**
     * Show login/logout, failed attempts, and lockout events with filter
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all'); // login, logout, failed, lockout, all

        $query = AuthLog::with(['actorAdmin'])->orderByDesc('created_at');

        if ($filter === 'login') {
            $query->where('action', 'login');
        } elseif ($filter === 'logout') {
            $query->where('action', 'logout');
        } elseif ($filter === 'failed') {
            $query->whereIn('action', ['failed_login', 'failed_login_unknown_user']);
        } elseif ($filter === 'lockout') {
            $query->where('action', 'account_locked');
        }

        $logs = $query->paginate(20)->appends(['filter' => $filter]);

        return view('admin.auth_logs.index', compact('logs', 'filter'));
    }
}
