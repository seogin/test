<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members</title>
    @vite('resources/css/app.css') <!-- Include TailwindCSS -->
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-md" style="height: 85vh;">
        <h1 class="text-3xl font-bold mb-6">All Members</h1>

        <div style="margin-bottom: 40px;">
            <a href="{{ route('admin.dashboard') }}"
                style="background-color: navy; color: white; padding: 14px 16px; border-radius: 4px; text-decoration: none; margin-right: 10px;">
                Back to Dashboard
            </a>
            @php $current = auth()->user(); @endphp
            @if ($current && $current->can_create_members)
            <a href=""
                style="background-color: navy; color: white; padding: 14px 16px; border-radius: 4px; text-decoration: none;">
                Add New Member
            </a>
            @endif
            <input type="text" id="search" placeholder="Search members..." class="ml-4 p-2 border border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200" style="margin-bottom: 100px;">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b border-gray-200 text-left">ID</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left">Name</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left">Email</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left">Phone</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left">City</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left">Verified</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left">Subscription</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left">Created At</th>
                        <th class="py-2 px-4 border-b border-gray-200 text-left">Updated At</th>
                        @if ($current && ($current->can_update_members || $current->can_delete_members))
                            <th class="py-2 px-4 border-b border-gray-200 text-left">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($members as $member)
                        <tr class="hover:bg-gray-50 member-row">
                            <td class="py-2 px-4 border-b border-gray-200">{{ $member->id }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $member->name }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $member->email }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $member->phone }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $member->city ?? '—' }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                @if ($member->verified)
                                    <span class="text-green-600 font-semibold">Yes</span>
                                @else
                                    <span class="text-red-600 font-semibold">No</span>
                                @endif
                            </td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                {{ $member->subscription }}
                            </td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $member->created_at }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $member->updated_at }}</td>

                            @if ($current && ($current->can_update_members || $current->can_delete_members))
                                <td class="py-2 px-4 border-b border-gray-200">
                                    <div class="relative inline-block text-left">
                                        <button type="button"
                                            onclick="document.getElementById('menu-{{ $member->id }}').classList.toggle('hidden')"
                                            class="bg-gray-100 px-3 py-1 rounded">Actions</button>
                                        <div id="menu-{{ $member->id }}"
                                            class="hidden origin-top-right absolute right-0 mt-2 w-36 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                                @if ($current->can_read_members)
                                                    <a href=""
                                                        class="block px-4 py-1 text-sm text-gray-700 hover:bg-gray-100"
                                                        role="menuitem">View</a>
                                                @endif
                                                @if ($current->can_update_members)
                                                    <a href=""
                                                        class="block px-4 py-1 text-sm text-gray-700 hover:bg-gray-100"
                                                        role="menuitem">Edit</a>
                                                @endif
                                                @if ($current->can_delete_members)
                                                    <form method="POST"
                                                        action=""
                                                        onsubmit="return confirm('Are you sure you want to delete this member?');">
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
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td class="py-4 px-4 border-b border-gray-200 text-center text-gray-600"
                                colspan="{{ ($current && ($current->can_update_members || $current->can_delete_members)) ? 10 : 9 }}">
                                No members found.
                            </td>
                        </tr>
                    @endforelse

                    <!-- Shown when client-side search finds no matches -->
                    <tr id="no-results" class="hidden">
                        <td class="py-4 px-4 border-b border-gray-200 text-center text-gray-600"
                            colspan="{{ ($current && ($current->can_update_members || $current->can_delete_members)) ? 10 : 9 }}">
                            No members match your search.
                        </td>
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
