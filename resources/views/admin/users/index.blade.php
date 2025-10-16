<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    @vite('resources/css/app.css') <!-- Include TailwindCSS -->
    @vite('resources/ts/users.ts')
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-md" style="height: 85vh;">
        <h1 class="text-3xl font-bold mb-6">All Admins</h1>
        <div style="margin-bottom: 40px;">
            <a href="{{ route('admin.dashboard') }}"
                style="background-color: navy; color: white; padding: 14px 16px; border-radius: 4px; text-decoration: none; margin-right: 10px;">
                Back to Dashboard
            </a>
            @php $current = auth()->user(); @endphp
            @if ($current && $current->can_create)
            <a href="{{ route('admin.add_user') }}"
                style="background-color: navy; color: white; padding: 14px 16px; border-radius: 4px; text-decoration: none;">
                Add New Admin
            </a>
            @endif
            <input type="text" id="search" placeholder="Search admins..." class="ml-4 p-2 border border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200" style="margin-bottom: 100px;">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b border-gray-200 text-left">ID</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left">Name</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left">Email</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left">Status</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left">Created At</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left">Updated At</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left">Permissions</th>
                        @php $current = auth()->user(); @endphp
                        @if ($current && ($current->can_update || $current->can_delete))
                            <th class="py-2 px-4 border-b border-gray-200 text-left">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admins as $admin)
                        <tr class="hover:bg-gray-50 admin-row">
                            <td class="py-2 px-4 border-b border-gray-200">{{ $admin->id }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $admin->name }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $admin->email }}</td>
                            @php $isActive = ($admin->status === 'active'); @endphp
                            <td class="py-2 px-4 border-b border-gray-200 {{ $admin->trashed() ? 'opacity-60 ' : '' }}">
                                @if ($isActive)
                                    <span class="text-green-600 font-semibold">Active</span>
                                @else
                                    <span class="text-red-600 font-semibold">Inactive</span>
                                @endif
                            </td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $admin->created_at }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $admin->updated_at }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                @php
                                    $perms = [];
                                    if ($admin->can_create) {
                                        $perms[] = 'Create';
                                    }
                                    if ($admin->can_read) {
                                        $perms[] = 'Read';
                                    }
                                    if ($admin->can_update) {
                                        $perms[] = 'Update';
                                    }
                                    if ($admin->can_delete) {
                                        $perms[] = 'Delete';
                                    }
                                @endphp
                                {{ empty($perms) ? '—' : implode(', ', $perms) }}
                            </td>

                            @if ($current && ($current->can_update || $current->can_delete))
                                <td class="py-2 px-4 border-b border-gray-200">
                                    @if ($admin->id !== $current->id)
                                        @php $isInactive = !$isActive || $admin->trashed(); @endphp
                                        @if($isInactive && ($current->can_create || $current->can_update))
                                            <form method="POST" action="{{ route('admin.reactivate_user', $admin->id) }}" onsubmit="return confirm('Reactivate this admin?');">
                                                @csrf
                                                <button type="submit" class="bg-gray-300 px-3 py-1 rounded">Reactivate</button>
                                            </form>
                                        @else
                                            <div class="relative inline-block text-left">
                                                <button type="button"
                                                    onclick="document.getElementById('menu-{{ $admin->id }}').classList.toggle('hidden')"
                                                    class="bg-gray-100 px-3 py-1 rounded">Actions</button>
                                                <div id="menu-{{ $admin->id }}"
                                                    class="hidden origin-top-right absolute right-0 mt-2 w-36 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                                    <div class="py-1" role="menu" aria-orientation="vertical"
                                                        aria-labelledby="options-menu">
                                                        @if ($current->can_read)
                                                            <a href="{{ route('admin.view_user', $admin->id) }}"
                                                                class="block px-4 py-1 text-sm text-gray-700 hover:bg-gray-100"
                                                                role="menuitem">View</a>
                                                        @endif
                                                        @if ($current->can_update)
                                                            <a href="{{ route('admin.edit_user', $admin->id) }}"
                                                                class="block px-4 py-1 text-sm text-gray-700 hover:bg-gray-100"
                                                                role="menuitem">Edit</a>
                                                        @endif
                                                        @if ($current->can_delete)
                                                            <form method="POST"
                                                                action="{{ route('admin.delete_user', $admin->id) }}"
                                                                onsubmit="return confirm('Are you sure you want to delete this admin?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="w-full text-left px-4 py-1 text-sm text-red-600 hover:bg-gray-100"
                                                                    role="menuitem">
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-sm text-gray-500"></span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td class="py-4 px-4 border-b border-gray-200 text-center" colspan="{{ ($current && ($current->can_update || $current->can_delete)) ? 7 : 6 }}">No admins found.
                            </td>
                        </tr>
                    @endforelse
                    <!-- Shown when client-side search finds no matches -->
                    <tr id="no-results" class="hidden">
                        <td class="py-4 px-4 border-b border-gray-200 text-center text-gray-600" colspan="{{ ($current && ($current->can_update || $current->can_delete)) ? 7 : 6 }}">No admins match your search.</td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination controls -->
            <div id="pagination-controls" class="flex items-center justify-between mt-4 fixed bottom-0 left-0 right-0 bg-white p-4 border-t border-gray-200">
                <div class="text-sm text-gray-600" id="pagination-info"></div>
                <div class="inline-flex items-center space-x-2" id="pagination-buttons"></div>
            </div>

        </div>
    </div>
</body>
</html>

