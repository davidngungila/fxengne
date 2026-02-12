@extends('layouts.app')

@section('title', 'Recovery Codes - FxEngne')
@section('page-title', 'Recovery Codes')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Recovery Codes</h2>
            <p class="text-sm text-gray-600 mt-1">Save these codes in a safe place</p>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-600">{{ session('success') }}</p>
        </div>
        @endif

        <!-- Warning -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>
                    <h3 class="font-semibold text-yellow-900 mb-1">Important</h3>
                    <p class="text-sm text-yellow-800">
                        These recovery codes can be used to access your account if you lose access to your authenticator device. 
                        Each code can only be used once. Store them in a secure location.
                    </p>
                </div>
            </div>
        </div>

        <!-- Recovery Codes -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 font-mono text-sm">
                @foreach($recoveryCodes as $code)
                <div class="bg-white px-4 py-2 rounded border border-gray-200 text-center">
                    {{ $code }}
                </div>
                @endforeach
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
            <button onclick="copyCodes()" class="btn btn-secondary flex-1">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                Copy All Codes
            </button>
            
            <form action="{{ route('auth.two-factor.recovery-codes.regenerate') }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="btn btn-secondary w-full" onclick="return confirm('Are you sure? This will invalidate all existing recovery codes.')">
                    Regenerate Codes
                </button>
            </form>
            
            <a href="{{ route('profile.security') }}" class="btn btn-primary flex-1">
                Done
            </a>
        </div>
    </div>
</div>

<script>
function copyCodes() {
    const codes = @json($recoveryCodes);
    const text = codes.join('\n');
    
    navigator.clipboard.writeText(text).then(() => {
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Copied!';
        btn.classList.add('bg-green-500');
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('bg-green-500');
        }, 2000);
    });
}
</script>
@endsection

