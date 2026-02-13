@extends('layouts.app')

@section('title', 'Trading Journal - FXEngine')
@section('page-title', 'Trading Journal')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Trading Journal</h2>
        <p class="text-sm text-gray-600 mt-1">Track and analyze your trading decisions</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <p class="text-sm text-gray-600">Total Entries</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">127</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">This Week</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">18</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Mistakes Logged</p>
            <p class="text-2xl font-bold text-red-600 mt-1">12</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Lessons Learned</p>
            <p class="text-2xl font-bold text-green-600 mt-1">45</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('journal.entries') }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">Journal Entries</h3>
                    <p class="text-sm text-gray-600">View all entries</p>
                </div>
            </div>
        </a>

        <a href="{{ route('journal.add-note') }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">Add Note</h3>
                    <p class="text-sm text-gray-600">Create new entry</p>
                </div>
            </div>
        </a>

        <a href="{{ route('journal.mistakes') }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">Mistakes</h3>
                    <p class="text-sm text-gray-600">Learn from mistakes</p>
                </div>
            </div>
        </a>

        <a href="{{ route('journal.weekly-review') }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">Weekly Review</h3>
                    <p class="text-sm text-gray-600">Weekly analysis</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection

@section('title', 'Trading Journal - FXEngine')
@section('page-title', 'Trading Journal')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Trading Journal</h2>
        <p class="text-sm text-gray-600 mt-1">Track and analyze your trading decisions</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <p class="text-sm text-gray-600">Total Entries</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">127</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">This Week</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">18</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Mistakes Logged</p>
            <p class="text-2xl font-bold text-red-600 mt-1">12</p>
        </div>
        <div class="card">
            <p class="text-sm text-gray-600">Lessons Learned</p>
            <p class="text-2xl font-bold text-green-600 mt-1">45</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('journal.entries') }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">Journal Entries</h3>
                    <p class="text-sm text-gray-600">View all entries</p>
                </div>
            </div>
        </a>

        <a href="{{ route('journal.add-note') }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">Add Note</h3>
                    <p class="text-sm text-gray-600">Create new entry</p>
                </div>
            </div>
        </a>

        <a href="{{ route('journal.mistakes') }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">Mistakes</h3>
                    <p class="text-sm text-gray-600">Learn from mistakes</p>
                </div>
            </div>
        </a>

        <a href="{{ route('journal.weekly-review') }}" class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">Weekly Review</h3>
                    <p class="text-sm text-gray-600">Weekly analysis</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
