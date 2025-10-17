<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Member Details</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-3xl bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6">Member Details</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block font-medium">Name</label>
                <div class="border rounded px-3 py-2 bg-gray-100">{{ $member->name }}</div>
            </div>
            <div>
                <label class="block font-medium">Email</label>
                <div class="border rounded px-3 py-2 bg-gray-100">{{ $member->email }}</div>
            </div>
            <div>
                <label class="block font-medium">Phone</label>
                <div class="border rounded px-3 py-2 bg-gray-100">{{ $member->phone }}</div>
            </div>
            <div>
                <label class="block font-medium">City</label>
                <div class="border rounded px-3 py-2 bg-gray-100">{{ $member->city ?? '-' }}</div>
            </div>
            <div>
                <label class="block font-medium">State</label>
                <div class="border rounded px-3 py-2 bg-gray-100">{{ $member->state ?? '-' }}</div>
            </div>
            <div>
                <label class="block font-medium">Country</label>
                <div class="border rounded px-3 py-2 bg-gray-100">{{ $member->country ?? '-' }}</div>
            </div>
            <div>
                <label class="block font-medium">Verified</label>
                <div class="border rounded px-3 py-2 bg-gray-100">{{ $member->verified ? 'Yes' : 'No' }}</div>
            </div>
            <div>
                <label class="block font-medium">Subscription</label>
                <div class="border rounded px-3 py-2 bg-gray-100">{{ $member->subscription }}</div>
            </div>
        </div>

        {{-- Profile Photo --}}
        <div class="mb-4">
            <label class="block font-medium">Profile Photo</label>
            @if($member->profile_photo)
                <img src="{{ asset('storage/'.$member->profile_photo) }}" 
                     alt="Profile Photo" 
                     class="w-32 h-32 mt-2 rounded object-cover border">
            @else
                <div class="border rounded px-3 py-2 bg-gray-100 mt-2 w-32 h-32 flex items-center justify-center text-gray-500">
                    No Photo
                </div>
            @endif
        </div>

        {{-- Uploaded Files --}}
        <div class="mb-4">
            <label class="block font-medium mb-1">Uploaded Files</label>
            @if($member->uploaded_files)
                @foreach(json_decode($member->uploaded_files, true) as $file)
                    <div class="mb-1">
                        <a href="{{ asset('storage/'.$file['path']) }}" target="_blank" class="text-blue-600 hover:underline">
                            {{ $file['original_name'] }}
                        </a>
                    </div>
                @endforeach
            @else
                <div class="text-gray-500">No files uploaded</div>
            @endif
        </div>

        <div class="flex justify-end mt-6">
            <a href="{{ route('admin.members') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                Back to List
            </a>
        </div>
    </div>
</body>
</html>
