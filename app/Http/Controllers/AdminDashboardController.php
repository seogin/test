<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Hash;

class AdminDashboardController extends Controller
{

    protected $logger;
    public function __construct(ActivityLogger $logger)
    {
        $this->logger = $logger;
    }

    // TODO
    public function dashboard(Request $request)
    {

        // $data = [
        //     'someOtherData' => // query for data
        // ];

        return response()->json([
            'success' => true,
            'message' => 'authenticated',
            // 'data' => $data // data relevant to dashboard
        ]);
    }

    public function updateAdminById(Request $request, $id)
{
    $admin = \App\Models\Admin::findOrFail($id);
    $oldData = $admin->toArray();

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:admins,email,' . $id,
        'can_create' => 'boolean',
        'can_read' => 'boolean',
        'can_update' => 'boolean',
        'can_delete' => 'boolean',
    ]);

    // Keep track of old data before update
        $beforePermissions = [
            'can_create' => $admin->can_create,
            'can_read' => $admin->can_read,
            'can_update' => $admin->can_update,
            'can_delete' => $admin->can_delete,
        ];

    $admin->update($validated);

    // Log update
    $this->logger->logUpdate($admin);

    $afterPermissions = [
        'can_create' => $admin->can_create,
        'can_read' => $admin->can_read,
        'can_update' => $admin->can_update,
        'can_delete' => $admin->can_delete,
    ];
    
    if ($beforePermissions !== $afterPermissions) {
        $this->logger->logPermissionChange($admin, $beforePermissions, $afterPermissions);
    }

    return redirect()
        ->route('admin.view_user', $admin->id)
        ->with('success', 'Admin updated successfully.');
}

    public function addUser(Request $request)
{
    $validated = $request->validate([
        'name'        => 'required|string|max:255',
        'email'       => 'required|email|unique:admins,email',
        'password'    => 'required|string|min:8|confirmed',
        'can_create'  => 'sometimes|boolean',
        'can_read'    => 'required|boolean|in:1',
        'can_update'  => 'sometimes|boolean',
        'can_delete'  => 'sometimes|boolean',
    ], [
        'can_read.in' => 'Every admin must have read permission.',
    ]);

    // Safety override
    $validated['can_read'] = 1;

    // Check if email already exists
    if (Admin::where('email', $validated['email'])->exists()) {
        return response()->json([
            'success' => false,
            'message' => 'Email already in use.',
        ], 400);
    }

    // Create new admin
    $admin = Admin::create($validated);

    // Log creation
    $this->logger->logCreate($admin);

    // If the client expects JSON, return JSON; otherwise redirect back to users list with a flash
    if ($request->wantsJson() || $request->isJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Admin created successfully.',
            'data'    => $admin,
        ]);
    }

    return redirect()->route('admin.users')->with('success', 'Admin created successfully.');
}


    public function deleteAdminById($id)
{
    $admin = \App\Models\Admin::findOrFail($id);

    // Soft delete logic
    $admin->update([
        'status' => 'inactive',  
    ]);
    $admin->delete();

    // Log deletion
    $this->logger->logDelete($admin);

    return redirect()
        ->route('admin.users')
        ->with('success', 'Admin deleted successfully.');
}

    public function reactivateAdminById(Request $request, $id)
    {
        // Only admins with create or update permission should reach this route via middleware or UI
        $admin = \App\Models\Admin::withTrashed()->findOrFail($id);

        // Restore (if soft deleted) and set status to active
        if ($admin->trashed()) {
            $admin->restore();
        }

        $admin->update(['status' => 'active']);

        // Log reactivation
        $this->logger->logReactivation($admin);

        return redirect()->route('admin.users')->with('success', 'Admin reactivated successfully.');
    }
}