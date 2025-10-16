<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailNotification;

class AdminEmailNotificationController extends Controller
{
    public function edit($id)
    {
        $notification = EmailNotification::findOrFail($id);
        return view('admin.email-notifications.edit', compact('notification'));
    }

    public function update(Request $request, $id)
    {
        $notification = EmailNotification::findOrFail($id);

        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        // Basic sanitization: allow a subset of HTML tags
        $allowed = '<p><a><br><strong><em><ul><ol><li><span><div><img>'; 
        $cleanBody = strip_tags($data['body'], $allowed);

        $notification->subject = $data['subject'];
        $notification->body = $cleanBody;
        $notification->save();

        return redirect()->route('admin.email_notifications')->with('status', 'Notification updated successfully.');
    }
}
