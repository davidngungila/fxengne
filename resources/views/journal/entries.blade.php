@extends('layouts.app')

@section('title', 'Journal Entries - FXEngine')
@section('page-title', 'Journal Entries')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Journal Entries</h2>
            <p class="text-sm text-gray-600 mt-1">All your trading journal entries</p>
        </div>
        <a href="{{ route('journal.add-note') }}" class="btn btn-primary">Add Entry</a>
    </div>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Instrument</th>
                        <th>Type</th>
                        <th>Result</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-50">
                        <td class="text-sm text-gray-600">2024-01-15</td>
                        <td class="font-medium">EUR/USD</td>
                        <td><span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">Long</span></td>
                        <td><span class="text-green-600 font-semibold">Win</span></td>
                        <td class="text-sm text-gray-600">Good entry on support level...</td>
                        <td><button class="text-blue-600 hover:text-blue-700 text-sm">View</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection



