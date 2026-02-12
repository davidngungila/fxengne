@extends('layouts.app')

@section('title', 'Add Journal Entry - FXEngine')
@section('page-title', 'Add Journal Entry')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Add Journal Entry</h2>
        <p class="text-sm text-gray-600 mt-1">Record your trading thoughts and analysis</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="card">
                <form class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Instrument</label>
                            <select class="form-input">
                                <option>EUR/USD</option>
                                <option>GBP/USD</option>
                                <option>XAU/USD</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Trade Result</label>
                            <select class="form-input">
                                <option>Win</option>
                                <option>Loss</option>
                                <option>Break Even</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Entry Notes</label>
                        <textarea class="form-input" rows="5" placeholder="Why did you enter this trade?"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Exit Notes</label>
                        <textarea class="form-input" rows="5" placeholder="Why did you exit?"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Lessons Learned</label>
                        <textarea class="form-input" rows="3" placeholder="What did you learn?"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Entry</button>
                </form>
            </div>
        </div>
        <div>
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tips</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li>• Be honest about mistakes</li>
                    <li>• Note emotional state</li>
                    <li>• Record market conditions</li>
                    <li>• Review entries regularly</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

