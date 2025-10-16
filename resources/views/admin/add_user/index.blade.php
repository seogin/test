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
        <h1 class="text-3xl font-bold mb-6">Add a New Admin</h1>
        <div style="margin-bottom: 40px;">
            <a href="{{ route('admin.users') }}"
                style="background-color: navy; color: white; padding: 14px 16px; border-radius: 4px; text-decoration: none;">
                Back to Admin List
            </a>
        </div>
        <form action="{{ route('admin.add.submit') }}" method="POST" class="space-y-4">
            @csrf
            @if (session('success'))
                <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="p-3 bg-red-100 text-red-800 rounded">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input required type="text" name="name" id="name" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input required type="email" name="email" id="email" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input required type="password" name="password" id="password" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                    Password</label>
                <input required type="password" name="password_confirmation" id="password_confirmation" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
            </div>
            <div>
                <span class="block text-sm font-medium text-gray-700">Permissions</span>
                <div class="mt-2 space-y-2">
                    <div>
                        <input type="hidden" name="can_create" value="0" />
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="can_create" value="1" class="form-checkbox">
                            <span class="ml-2">Create</span>
                        </label>
                    </div>
                    <div>
                        <input type="hidden" name="can_read" value="1" />
                        <!-- Always on (if off, cannot see dashboard) -->
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="can_read" value="1" class="form-checkbox" checked
                                disabled>
                            <span class="ml-2">Read (Required)</span>
                        </label>
                    </div>

                    <div>
                        <input type="hidden" name="can_update" value="0" />
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="can_update" value="1" class="form-checkbox">
                            <span class="ml-2">Update</span>
                        </label>
                    </div>
                    <div>
                        <input type="hidden" name="can_delete" value="0" />
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="can_delete" value="1" class="form-checkbox">
                            <span class="ml-2">Delete</span>
                        </label>
                    </div>
                </div>
                <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add
                    Admin</button>
            </div>
        </form>
    </div>
</body>

</html>
