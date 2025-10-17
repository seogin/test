<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($member) ? 'Edit Member' : 'Add New Member' }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-3xl bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6">{{ isset($member) ? 'Edit Member' : 'Add New Member' }}</h2>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Success --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ isset($member) ? route('admin.members.update', $member->id) : route('admin.members.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($member))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block mb-1 font-medium">Name *</label>
                    <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ old('name', $member->name ?? '') }}" required>
                </div>
                <div>
                    <label class="block mb-1 font-medium">Email *</label>
                    <input type="email" name="email" class="w-full border rounded px-3 py-2" value="{{ old('email', $member->email ?? '') }}" required>
                </div>
                <div>
                    <label class="block mb-1 font-medium">Phone *</label>
                    <input type="text" name="phone" class="w-full border rounded px-3 py-2" value="{{ old('phone', $member->phone ?? '') }}" required>
                </div>
                <div>
                    <label class="block mb-1 font-medium">City</label>
                    <input type="text" name="city" class="w-full border rounded px-3 py-2" value="{{ old('city', $member->city ?? '') }}">
                </div>
                <div>
                    <label class="block mb-1 font-medium">State</label>
                    <input type="text" name="state" class="w-full border rounded px-3 py-2" value="{{ old('state', $member->state ?? '') }}">
                </div>
                <div>
                    <label class="block mb-1 font-medium">Country</label>
                    <input type="text" name="country" class="w-full border rounded px-3 py-2" value="{{ old('country', $member->country ?? '') }}">
                </div>
                <div>
                    <label class="block mb-1 font-medium">Verified *</label>
                    <select name="verified" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- Select --</option>
                        <option value="1" {{ old('verified', $member->verified ?? '') == 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('verified', $member->verified ?? '') == 0 ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-1 font-medium">Subscription *</label>
                    <select name="subscription" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- Select --</option>
                        <option value="Free" {{ old('subscription', $member->subscription ?? '') == 'Free' ? 'selected' : '' }}>Free</option>
                        <option value="Paid" {{ old('subscription', $member->subscription ?? '') == 'Paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
            </div>

            {{-- Profile Photo --}}
            <div class="mb-4">
                <label class="block mb-1 font-medium">Profile Photo (JPG/PNG)</label>
                <input type="file" name="profile_photo" id="profile_photo_input" class="w-full border rounded px-3 py-2" accept=".jpg,.jpeg,.png">
                @if(isset($member) && $member->profile_photo)
                    <img src="{{ asset('storage/'.$member->profile_photo) }}" id="profile_photo_preview" class="w-32 h-32 mt-2 rounded object-cover border">
                @else
                    <img src="" id="profile_photo_preview" class="w-32 h-32 mt-2 rounded object-cover border hidden">
                @endif
            </div>

            {{-- Multiple Files --}}
            <div class="mb-4">
                <label class="block mb-1 font-medium">Upload Files (PDF/JPG, multiple)</label>
                <input type="file" name="files[]" id="files_input" class="w-full border rounded px-3 py-2" multiple accept=".pdf,.jpg,.jpeg">

                {{-- Existing files --}}
                @if(isset($member) && $member->uploaded_files)
                <ul id="existing-files-list">
                @foreach($member->uploaded_files ? json_decode($member->uploaded_files) : [] as $index => $file)
                    <li data-index="{{ $index }}">
                        <a href="{{ asset('storage/'.$file->path) }}" target="_blank">{{ $file->original_name }}</a>
                        <button type="button" class="ml-2 text-red-600 hover:underline" onclick="removeExistingFile({{ $index }})">Remove</button>
                        <input type="hidden" name="existing_files[]" value='{{ json_encode($file) }}'>
                    </li>
                @endforeach
                </ul>   
                @endif

                {{-- New files preview --}}
                <ul id="new-files-list" class="mt-2 list-disc list-inside"></ul>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <a href="{{ route('admin.members') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">{{ isset($member) ? 'Update Member' : 'Save Member' }}</button>
            </div>
        </form>
    </div>

    <script>
        // Profile photo preview
        const photoInput = document.getElementById('profile_photo_input');
        const photoPreview = document.getElementById('profile_photo_preview');
        photoInput.addEventListener('change', function() {
            const file = this.files[0];
            if(file){
                const reader = new FileReader();
                reader.onload = e => {
                    photoPreview.src = e.target.result;
                    photoPreview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        // Multiple files preview and remove
        const filesInput = document.getElementById('files_input');
        const newFilesList = document.getElementById('new-files-list');

        // Keep track of all new files
        let newFilesArray = [];

        filesInput.addEventListener('change', function() {
            Array.from(this.files).forEach(file => {
                newFilesArray.push(file);

                const index = newFilesArray.length - 1;
                const span = document.createElement('div');
                span.dataset.index = index;
                span.innerHTML = `
                    <span class="text-green-600 font-bold">+</span>
                    <span>${file.name}</span>
                    <button type="button" class="ml-2 text-red-600 hover:underline" onclick="removeNewFile(${index})">Remove</button>
                `;
                newFilesList.appendChild(span);
            });

            // Reset input to allow re-selecting same files
            this.value = '';
        });
        
        // Remove file from newFilesArray and preview
        function removeNewFile(index) {
            const li = newFilesList.querySelector(`li[data-index='${index}']`);
            if (li) li.remove();
            newFilesArray[index] = null; // mark as removed
        }

        // On form submit, append the new files to the input
        document.querySelector('form').addEventListener('submit', function(e) {
            const dt = new DataTransfer();
            newFilesArray.forEach(file => {
                if (file) dt.items.add(file);
            });
            filesInput.files = dt.files;
        });


        function removeExistingFile(index){
            const li = document.querySelector(`#existing-files-list li[data-index='${index}']`);
            if(li) li.remove();
        }
    </script>
</body>
</html>
