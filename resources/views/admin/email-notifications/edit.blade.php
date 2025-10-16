<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Email Notification</title>
    @vite('resources/css/app.css')
    {{-- TinyMCE CDN --}}
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Edit Notification: {{ $notification->name ?? 'Notification' }}</h1>
            <div>
                <a href="{{ route('admin.email_notifications') }}" class="bg-gray-700 text-white px-4 py-2 rounded">Back to List</a>
            </div>
        </div>

        @if(session('status'))
            <div class="mb-4 text-green-700">{{ session('status') }}</div>
        @endif

        <form action="{{ route('admin.update_email_notification', ['id' => $notification->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-4 gap-6">
                <div class="col-span-3">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Notification Name</label>
                        <input type="text" readonly value="{{ $notification->name }}" class="mt-1 block w-full border-gray-300 rounded-md p-2 bg-gray-100" />
                    </div>

                    <div class="mb-4">
                        <label for="subject" class="block text-sm font-medium text-gray-700">Subject Line</label>
                        <input id="subject" name="subject" type="text" value="{{ old('subject', $notification->subject) }}" class="mt-1 block w-full border-gray-300 rounded-md p-2" required />
                        @error('subject')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="body" class="block text-sm font-medium text-gray-700">Body</label>
                        <textarea id="body" name="body" rows="12">{{ old('body', $notification->body) }}</textarea>
                        @error('body')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="flex space-x-3">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
                        <a href="{{ route('admin.email_notifications') }}" class="bg-gray-300 px-4 py-2 rounded">Cancel</a>
                    </div>
                </div>

                <aside class="col-span-1 bg-gray-50 p-4 rounded">
                    <h3 class="font-semibold mb-2">Available Variables</h3>
                    <ul class="text-sm space-y-2">
                        <li><button type="button" onclick="insertPlaceholder('@{{member_name}}')" class="text-blue-600">@{{member_name}}</button> — Member’s full name</li>
                        <li><button type="button" onclick="insertPlaceholder('@{{verification_code}}')" class="text-blue-600">@{{verification_code}}</button> — Random 8-digit code</li>
                        <li><button type="button" onclick="insertPlaceholder('@{{matched_member_name}}')" class="text-blue-600">@{{matched_member_name}}</button> — Name of matched member</li>
                        <li><button type="button" onclick="insertPlaceholder('@{{matched_member_link}}')" class="text-blue-600">@{{matched_member_link}}</button> — Link to matched member profile</li>
                        <li><button type="button" onclick="insertPlaceholder('@{{app_name}}')" class="text-blue-600">@{{app_name}}</button> — Application name</li>
                    </ul>
                </aside>
            </div>
        </form>
    </div>

    <script>
        tinymce.init({
            selector: '#body',
            height: 400,
            menubar: false,
            plugins: 'link lists paste table image',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image | removeformat'
        });

        function insertPlaceholder(text) {
            const ed = tinymce.get('body');
            if (ed) {
                ed.focus();
                ed.selection.setContent(text);
            } else {
                // Fallback: append to textarea
                const ta = document.getElementById('body');
                ta.value += text;
            }
        }
    </script>
</body>
</html>

