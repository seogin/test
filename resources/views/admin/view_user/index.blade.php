<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Admin</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-6">View Admin Details</h1>

        <div style="margin-bottom: 40px;">
            <a href="{{ route('admin.users') }}"
                style="background-color: navy; color: white; padding: 14px 16px; border-radius: 4px; text-decoration: none;">
                Back to List
            </a>
        </div>

        @if (session('success'))
            <div class="p-3 bg-green-100 text-green-800 rounded mb-4">{{ session('success') }}</div>
        @endif

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <p class="mt-1 border border-gray-300 rounded-md p-2 bg-gray-50">{{ $admin->name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <p class="mt-1 border border-gray-300 rounded-md p-2 bg-gray-50">{{ $admin->email }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <div class="mt-1 flex items-center border border-gray-300 rounded-md p-2 bg-gray-50">
                    <span
                        class="w-3 h-3 rounded-full mr-2 
            {{ $admin->status === 'active' ? 'bg-green-500' : 'bg-red-500' }}">
                    </span>
                    <span class="text-gray-800 font-medium">
                        {{ $admin->status === 'active' ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>

                <div class="grid grid-cols-3 gap-y-2 w-full max-w-sm">
                    <!-- Create -->
                    <div class="text-gray-700 font-semibold">Create</div>
                    <div class="col-span-2">
                        <div
                            class="w-[50px] text-center text-white font-medium rounded-md py-1 
                {{ $admin->can_create ? 'bg-green-500' : 'bg-red-500' }}">
                            {{ $admin->can_create ? 'Yes' : 'No' }}
                        </div>
                    </div>

                    <!-- Read -->
                    <div class="text-gray-700 font-semibold">Read</div>
                    <div class="col-span-2">
                        <div
                            class="w-[50px] text-center text-white font-medium rounded-md py-1 
                {{ $admin->can_read ? 'bg-green-500' : 'bg-red-500' }}">
                            {{ $admin->can_read ? 'Yes' : 'No' }}
                        </div>
                    </div>

                    <!-- Update -->
                    <div class="text-gray-700 font-semibold">Update</div>
                    <div class="col-span-2">
                        <div
                            class="w-[50px] text-center text-white font-medium rounded-md py-1 
                {{ $admin->can_update ? 'bg-green-500' : 'bg-red-500' }}">
                            {{ $admin->can_update ? 'Yes' : 'No' }}
                        </div>
                    </div>

                    <!-- Delete -->
                    <div class="text-gray-700 font-semibold">Delete</div>
                    <div class="col-span-2">
                        <div
                            class="w-[50px] text-center text-white font-medium rounded-md py-1 
                {{ $admin->can_delete ? 'bg-green-500' : 'bg-red-500' }}">
                            {{ $admin->can_delete ? 'Yes' : 'No' }}
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
</body>

</html>
