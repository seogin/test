<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    @vite('resources/css/app.css') <!-- Include TailwindCSS -->
</head>

<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-md">
        @php $current = auth()->user(); @endphp
        <h1 class="text-3xl font-bold mb-6">Welcome, {{ $current->name }}</h1>

        <p class="mb-4">This is the dashboard page. You can add charts, tables, or controls here.</p>
        <div style="margin-top: 40px;">
            <a href="{{ route('admin.users') }}"
                style="background-color: navy; color: white; padding: 14px 16px; border-radius: 4px; text-decoration: none; margin-right: 10px;">
                View Admin List
            </a>
            <a href="{{ route('admin.members') }}"
                style="background-color: navy; color: white; padding: 14px 16px; border-radius: 4px; text-decoration: none; display: inline;">
                View Member List
            </a>
        </div>
        <div style="margin-top: 40px;">
            <a href="{{ route('admin.auth_logs') }}"
                style="background-color: navy; color: white; padding: 14px 16px; border-radius: 4px; text-decoration: none;">
                View Auth Logs
            </a>
        </div>
        <div style="margin-top: 40px;">
            <a href="{{ route('admin.email_notifications') }}"
                style="background-color: navy; color: white; padding: 14px 16px; border-radius: 4px; text-decoration: none;">
                Email Notifications Settings
            </a>
        </div>
        <div style="margin-top: 40px;">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit"
                    style="color: gray; padding: 8px 16px; border-radius: 4px; border: solid; cursor: pointer;">
                    Logout
                </button>
            </form>
        </div>

    </div>
</body>

</html>
