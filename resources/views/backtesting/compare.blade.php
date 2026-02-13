@extends('layouts.app')

@section('title', 'Compare Strategies - FXEngine')
@section('page-title', 'Compare Strategies')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Compare Strategies</h2>
        <p class="text-sm text-gray-600 mt-1">Compare multiple strategy backtest results</p>
    </div>

    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Strategy Comparison</h3>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Strategy</th>
                        <th>Total Return</th>
                        <th>Win Rate</th>
                        <th>Sharpe Ratio</th>
                        <th>Max Drawdown</th>
                        <th>Profit Factor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">EMA Crossover</td>
                        <td class="text-green-600 font-semibold">+15.2%</td>
                        <td><span class="text-green-600 font-semibold">68.5%</span></td>
                        <td>1.85</td>
                        <td class="text-red-600">-8.5%</td>
                        <td>2.15</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">RSI Strategy</td>
                        <td class="text-green-600 font-semibold">+12.8%</td>
                        <td><span class="text-green-600 font-semibold">72.3%</span></td>
                        <td>1.82</td>
                        <td class="text-red-600">-5.8%</td>
                        <td>2.35</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection



