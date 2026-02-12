@extends('layouts.app')

@section('title', 'Pair Performance - FXEngine')
@section('page-title', 'Pair Performance')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Pair Performance</h2>
        <p class="text-sm text-gray-600 mt-1">Analyze performance by currency pair</p>
    </div>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Pair</th>
                        <th>Trades</th>
                        <th>Win Rate</th>
                        <th>Total P/L</th>
                        <th>Avg P/L</th>
                        <th>Best Trade</th>
                        <th>Worst Trade</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">EUR/USD</td>
                        <td>42</td>
                        <td><span class="text-green-600 font-semibold">68.5%</span></td>
                        <td class="text-green-600 font-semibold">+$1,250.00</td>
                        <td>$29.76</td>
                        <td class="text-green-600">+$150.00</td>
                        <td class="text-red-600">-$80.00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">GBP/USD</td>
                        <td>38</td>
                        <td><span class="text-green-600 font-semibold">72.3%</span></td>
                        <td class="text-green-600 font-semibold">+$980.50</td>
                        <td>$25.80</td>
                        <td class="text-green-600">+$120.00</td>
                        <td class="text-red-600">-$65.00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">XAU/USD</td>
                        <td>23</td>
                        <td><span class="text-green-600 font-semibold">65.2%</span></td>
                        <td class="text-green-600 font-semibold">+$650.00</td>
                        <td>$28.26</td>
                        <td class="text-green-600">+$200.00</td>
                        <td class="text-red-600">-$100.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

