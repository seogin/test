<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    @vite('resources/css/app.css') <!-- Include TailwindCSS -->
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-md" style="height: 85vh;">
        <h1 class="text-3xl font-bold mb-6">Email Notifications</h1>
        <div style="margin-bottom: 40px;">
            <a href="{{ route('admin.dashboard') }}"
                style="background-color: navy; color: white; padding: 14px 16px; border-radius: 4px; text-decoration: none; margin-right: 10px;">
                Back to Dashboard
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200" style="margin-bottom: 100px;">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b border-gray-200 text-left">Sr. No</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left">Notification Name</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left">Description</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notifications as $index => $notification)
                        <tr class="{{ $index % 2 == 0 ? 'bg-gray-50' : '' }}">
                            <td class="py-2 px-4 border-b border-gray-200">{{ $notification->id }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $notification->name }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $notification->description }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                <a href="{{ route('admin.edit_email_notification', ['id' => $notification->id]) }}"
                                   class="text-blue-600 px-4 py-2 rounded">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

