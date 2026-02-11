@extends('layouts.app')

@section('title', 'Trading - FxEngne')
@section('page-title', 'Trading')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Trading Control Center</h2>
        <div class="flex space-x-3">
            <a href="{{ route('trading.manual-entry') }}" class="btn btn-primary">Manual Trade Entry</a>
            <button class="btn btn-danger">Close All Trades</button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Open Trades</h3>
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Pair</th>
                                <th>Type</th>
                                <th>Volume</th>
                                <th>Entry</th>
                                <th>Current</th>
                                <th>P/L</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-medium">EURUSD</td>
                                <td><span class="badge badge-success">BUY</span></td>
                                <td>0.10</td>
                                <td>1.0850</td>
                                <td>1.0875</td>
                                <td class="text-green-600 dark:text-green-400 font-semibold">+$25.00</td>
                                <td>
                                    <button class="text-blue-600 hover:text-blue-800 dark:text-blue-400">Modify</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('trading.open-trades') }}" class="block w-full btn btn-secondary text-left">View Open Trades</a>
                    <a href="{{ route('trading.history') }}" class="block w-full btn btn-secondary text-left">Trade History</a>
                    <button class="block w-full btn btn-danger text-left">Emergency Close All</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

