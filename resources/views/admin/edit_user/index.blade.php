<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-6">Edit Admin</h1>

        <div style="margin-bottom: 40px;">
            <a href="{{ route('admin.users') }}"
               style="background-color: navy; color: white; padding: 14px 16px; border-radius: 4px; text-decoration: none;">
                Back to List
            </a>
        </div>

        <form action="{{ url('/admin/update/' . $admin->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            @if(session('success'))
                <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="p-3 bg-red-100 text-red-800 rounded">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $admin->name) }}"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $admin->email) }}"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
            </div>

            <div>
                <span class="block text-sm font-medium text-gray-700 mb-2">Permissions</span>
                <div class="space-y-2">
                    @foreach(['create', 'read', 'update', 'delete'] as $perm)
                        <div>
                            <input type="hidden" name="can_{{ $perm }}" value="0">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="can_{{ $perm }}" value="1"
                                       class="form-checkbox"
                                       {{ old('can_'.$perm, $admin->{'can_'.$perm}) ? 'checked' : '' }}>
                                <span class="ml-2 capitalize">{{ $perm }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex space-x-3 mt-6">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save Changes
                </button>
                <a href="{{ route('admin.users') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</body>

</html>
