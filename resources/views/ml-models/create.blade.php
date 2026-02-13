@extends('layouts.app')

@section('title', 'Create ML Model - FXEngine')
@section('page-title', 'Create ML Model')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Create New ML Model</h2>
        <p class="text-sm text-gray-600 mt-1">Configure and train a machine learning model for trading</p>
    </div>

    <form action="{{ route('ml-models.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Basic Information -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
            <div class="space-y-4">
                <div>
                    <label class="form-label">Model Name</label>
                    <input type="text" name="name" class="form-input" placeholder="e.g., XAUUSD TFT Model" required>
                </div>
                
                <div>
                    <label class="form-label">Model Type</label>
                    <select name="type" id="modelType" class="form-input" required>
                        <option value="">Select Model Type</option>
                        @foreach($modelTypes as $key => $label)
                        <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1" id="typeDescription"></p>
                </div>
                
                <div>
                    <label class="form-label">Architecture</label>
                    <select name="architecture" id="architecture" class="form-input" required>
                        <option value="">Select Architecture</option>
                        @foreach($architectures as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-input" rows="3" placeholder="Describe the model's purpose and configuration..."></textarea>
                </div>
            </div>
        </div>

        <!-- Model Configuration -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Model Configuration</h3>
            <div class="space-y-4">
                <div>
                    <label class="form-label">Input Features</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="config[features][]" value="OHLC" checked class="form-checkbox">
                            <span class="text-sm">OHLC</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="config[features][]" value="RSI" checked class="form-checkbox">
                            <span class="text-sm">RSI</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="config[features][]" value="MACD" checked class="form-checkbox">
                            <span class="text-sm">MACD</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="config[features][]" value="BB" class="form-checkbox">
                            <span class="text-sm">Bollinger Bands</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="config[features][]" value="ATR" class="form-checkbox">
                            <span class="text-sm">ATR</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="config[features][]" value="Volume" class="form-checkbox">
                            <span class="text-sm">Volume</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="config[features][]" value="EMA" class="form-checkbox">
                            <span class="text-sm">EMA</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="config[features][]" value="Time" class="form-checkbox">
                            <span class="text-sm">Time Encoding</span>
                        </label>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Timeframe</label>
                        <select name="config[timeframe]" class="form-input">
                            <option value="M1">M1 (1 Minute)</option>
                            <option value="M5">M5 (5 Minutes)</option>
                            <option value="M15" selected>M15 (15 Minutes)</option>
                            <option value="H1">H1 (1 Hour)</option>
                            <option value="H4">H4 (4 Hours)</option>
                            <option value="D1">D1 (Daily)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="form-label">Instrument</label>
                        <select name="config[instrument]" class="form-input">
                            <option value="XAU_USD" selected>XAU/USD (Gold)</option>
                            <option value="EUR_USD">EUR/USD</option>
                            <option value="GBP_USD">GBP/USD</option>
                            <option value="USD_JPY">USD/JPY</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Training Configuration -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Training Configuration</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Training Period (Years)</label>
                    <input type="number" name="training_config[period_years]" value="2" min="1" max="10" class="form-input" required>
                </div>
                
                <div>
                    <label class="form-label">Test Split (%)</label>
                    <input type="number" name="training_config[test_split]" value="25" min="10" max="40" class="form-input" required>
                </div>
                
                <div>
                    <label class="form-label">Epochs</label>
                    <input type="number" name="training_config[epochs]" value="100" min="10" max="1000" class="form-input">
                </div>
                
                <div>
                    <label class="form-label">Batch Size</label>
                    <input type="number" name="training_config[batch_size]" value="32" min="8" max="256" class="form-input">
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('ml-models.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Model</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeDescriptions = {
        'price_direction': 'Predict BUY/SELL/HOLD signals. Best for direct integration into trading bot. Recommended: TFT or LSTM.',
        'volatility': 'Forecast price movement magnitude. Best for risk management and position sizing. Recommended: XGBoost.',
        'sentiment': 'Analyze financial news sentiment. Note: Reactive, not predictive. Use as confirmation filter. Recommended: FinBERT.',
        'parameter_optimization': 'Optimize traditional indicator parameters using ML-driven search. Recommended: Grid Search.'
    };
    
    const typeArchitectures = {
        'price_direction': ['TFT', 'LSTM', 'Hybrid'],
        'volatility': ['XGBoost', 'RandomForest'],
        'sentiment': ['FinBERT'],
        'parameter_optimization': ['GridSearch']
    };
    
    document.getElementById('modelType').addEventListener('change', function() {
        const type = this.value;
        const descEl = document.getElementById('typeDescription');
        const archEl = document.getElementById('architecture');
        
        if (type && typeDescriptions[type]) {
            descEl.textContent = typeDescriptions[type];
            
            // Update architecture options
            archEl.innerHTML = '<option value="">Select Architecture</option>';
            if (typeArchitectures[type]) {
                typeArchitectures[type].forEach(arch => {
                    const option = document.createElement('option');
                    option.value = arch;
                    option.textContent = arch;
                    archEl.appendChild(option);
                });
            } else {
                @foreach($architectures as $key => $label)
                const option{{ $key }} = document.createElement('option');
                option{{ $key }}.value = '{{ $key }}';
                option{{ $key }}.textContent = '{{ $label }}';
                archEl.appendChild(option{{ $key }});
                @endforeach
            }
        } else {
            descEl.textContent = '';
        }
    });
    
    // Trigger on load if type is preselected
    if (document.getElementById('modelType').value) {
        document.getElementById('modelType').dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
@endsection


