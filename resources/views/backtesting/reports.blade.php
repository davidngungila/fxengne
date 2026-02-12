@extends('layouts.app')

@section('title', 'Backtest Reports - FxEngne')
@section('page-title', 'Backtest Reports')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Backtest Reports</h2>
        <p class="text-sm text-gray-600 mt-1">View detailed backtest results and analysis</p>
    </div>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Strategy</th>
                        <th>Period</th>
                        <th>Total Return</th>
                        <th>Win Rate</th>
                        <th>Sharpe Ratio</th>
                        <th>Max Drawdown</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">EMA Crossover</td>
                        <td class="text-sm text-gray-600">Jan 2024</td>
                        <td class="text-green-600 font-semibold">+15.2%</td>
                        <td><span class="text-green-600 font-semibold">68.5%</span></td>
                        <td>1.85</td>
                        <td class="text-red-600">-8.5%</td>
                        <td><button class="text-blue-600 hover:text-blue-700 text-sm">View Report</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

