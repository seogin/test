<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminMemberController extends Controller
{
    // Show the form to create a new member
    public function create()
    {
        return view('admin.members.create');
    }

    // Store a new member
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','email','max:255', Rule::unique('members','email')],
            'phone' => 'required|string|max:30',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'verified' => 'required|in:0,1',
            'subscription' => 'required|in:Free,Paid',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:20480',
            'files.*' => 'nullable|mimes:pdf,jpg,jpeg|max:51200',
        ]);

        // Profile photo
        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')->store('members/profile_photos', 'public');
        }

        // Multiple files
        $uploadedFiles = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('members/uploads', 'public');
                $uploadedFiles[] = [
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ];
            }
        }

        $member = Member::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['phone'], // initial password
            'phone' => $validated['phone'],
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'country' => $validated['country'] ?? null,
            'verified' => $validated['verified'],
            'subscription' => $validated['subscription'],
            'profile_photo' => $validated['profile_photo'] ?? null,
            'uploaded_files' => json_encode($uploadedFiles),
        ]);

        return redirect()->route('admin.members')->with('success', 'Member created successfully.');
    }

    // Cancel / back to list
    public function cancel()
    {
        return redirect()->route('admin.members');
    }

    // Show edit form
    public function edit(Member $member)
    {
        return view('admin.members.create', compact('member'));
    }

    // Update member
    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,'.$member->id,
            'phone' => 'required|string|max:30',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'verified' => 'required|in:0,1',
            'subscription' => 'required|in:Free,Paid',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:20480',
            'files.*' => 'nullable|mimes:pdf,jpg,jpeg|max:51200',
        ]);

        $member->fill($validated);

        // Profile photo
        if ($request->hasFile('profile_photo')) {
            if ($member->profile_photo && Storage::disk('public')->exists($member->profile_photo)) {
                Storage::disk('public')->delete($member->profile_photo);
            }
            $member->profile_photo = $request->file('profile_photo')->store('members/profile_photos', 'public');
        }
        
        $existingFiles = $request->input('existing_files', []); // JSON strings
        $existingFiles = array_map(fn($f) => is_string($f) ? json_decode($f, true) : $f, $existingFiles);

        // Handle new uploaded files
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('members/uploads', 'public');
                $existingFiles[] = [
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ];
            }
        }

        // Save all files to DB
        $member->uploaded_files = json_encode($existingFiles);

        $member->save();

        return redirect()->route('admin.members')->with('success', 'Member updated successfully.');
    }

    /**
     * Display the member details in read-only mode.
     */
    public function view(Member $member)
    {
        return view('admin.members.view', compact('member'));
    }
}
