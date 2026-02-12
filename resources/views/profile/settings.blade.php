@extends('layouts.app')

@section('title', 'Profile Settings - FxEngne')
@section('page-title', 'Profile Settings')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Profile Settings</h2>
        <p class="text-sm text-gray-600 mt-1">Manage your account information</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                <form class="space-y-4">
                    <div>
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-input" value="John Doe">
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" class="form-input" value="john@example.com">
                    </div>
                    <div>
                        <label class="form-label">Phone</label>
                        <input type="tel" class="form-input" value="+1 234 567 8900">
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
        <div>
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Preferences</h3>
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="checkbox" class="form-checkbox" checked>
                        <span class="ml-2 text-sm text-gray-700">Email notifications</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" class="form-checkbox" checked>
                        <span class="ml-2 text-sm text-gray-700">SMS alerts</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

