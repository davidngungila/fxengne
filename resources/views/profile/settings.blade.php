@extends('layouts.app')

@section('title', 'Profile Settings - FXEngine')
@section('page-title', 'Profile Settings')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Profile Settings</h2>
        <p class="text-sm text-gray-600 mt-1">Manage your account information and preferences</p>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Image Section -->
        <div>
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Picture</h3>
                <div class="flex flex-col items-center space-y-4">
                    <div class="relative">
                        @if(auth()->user()->profile_image)
                            <img src="{{ auth()->user()->getProfileImageUrl() }}" alt="{{ auth()->user()->name }}" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 shadow-lg">
                        @else
                            <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center border-4 border-gray-200 shadow-lg">
                                <span class="text-white font-bold text-4xl">{{ auth()->user()->getAvatarInitial() }}</span>
                            </div>
                        @endif
                        <div class="absolute bottom-0 right-0 w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center border-4 border-white shadow-lg cursor-pointer hover:bg-blue-700 transition-colors" onclick="document.getElementById('profileImageInput').click()">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <form id="profileImageForm" action="{{ route('profile.upload-image') }}" method="POST" enctype="multipart/form-data" class="hidden">
                        @csrf
                        <input type="file" id="profileImageInput" name="profile_image" accept="image/jpeg,image/png,image/jpg,image/gif" onchange="uploadImage()">
                    </form>
                    <div class="flex flex-col space-y-2 w-full">
                        <button onclick="document.getElementById('profileImageInput').click()" class="btn btn-primary w-full text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Upload New Photo
                        </button>
                        @if(auth()->user()->profile_image)
                        <form action="{{ route('profile.remove-image') }}" method="POST" onsubmit="return confirm('Are you sure you want to remove your profile image?')">
                            @csrf
                            <button type="submit" class="btn btn-secondary w-full text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Remove Photo
                            </button>
                        </form>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 text-center">
                        JPG, PNG or GIF. Max size 2MB.
                    </p>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="lg:col-span-2">
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                <form class="space-y-4">
                    <div>
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-input" value="{{ auth()->user()->name }}" name="name">
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" class="form-input" value="{{ auth()->user()->email }}" readonly>
                        <p class="text-xs text-gray-500 mt-1">Email cannot be changed</p>
                    </div>
                    <div>
                        <label class="form-label">Phone</label>
                        <input type="tel" class="form-input" placeholder="+1 234 567 8900">
                    </div>
                    <div>
                        <label class="form-label">Timezone</label>
                        <select class="form-input">
                            <option>UTC</option>
                            <option>EST</option>
                            <option>PST</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function uploadImage() {
    const form = document.getElementById('profileImageForm');
    const fileInput = document.getElementById('profileImageInput');
    
    if (fileInput.files && fileInput.files[0]) {
        // Validate file size (2MB)
        if (fileInput.files[0].size > 2048 * 1024) {
            alert('File size must be less than 2MB');
            return;
        }
        
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!allowedTypes.includes(fileInput.files[0].type)) {
            alert('Please select a valid image file (JPG, PNG, or GIF)');
            return;
        }
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Uploading...';
        }
        
        // Submit form
        form.submit();
    }
}

// Preview image before upload
document.getElementById('profileImageInput')?.addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(event) {
            // Optional: Show preview
            // const preview = document.querySelector('.profile-preview');
            // if (preview) preview.src = event.target.result;
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>
@endpush
@endsection
