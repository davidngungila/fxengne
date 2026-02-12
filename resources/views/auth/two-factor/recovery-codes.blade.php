@extends('layouts.app')

@section('title', 'Recovery Codes - FXEngine')
@section('page-title', 'Recovery Codes')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Two-Factor Authentication Recovery Codes</h2>
        <p class="text-sm text-gray-600 mt-1">Save these codes in a safe place. You'll need them if you lose access to your authenticator device.</p>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Critical Warning -->
            <div class="card bg-gradient-to-br from-red-50 to-orange-50 border-red-200">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-semibold text-red-900 mb-2">⚠️ Critical: Save These Codes Now</h3>
                        <p class="text-sm text-red-800 mb-3">
                            These recovery codes are your backup access method. If you lose your authenticator device, 
                            you'll need one of these codes to sign in. <strong>Each code can only be used once.</strong>
                        </p>
                        <div class="bg-white rounded-lg p-3 border border-red-200">
                            <p class="text-xs text-red-700 font-medium">
                                💡 <strong>Tip:</strong> Print this page or save these codes in a password manager. 
                                Don't store them in the same place as your password.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recovery Codes Display -->
            <div class="card">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Your Recovery Codes</h3>
                        <p class="text-sm text-gray-600 mt-1">You have <span class="font-semibold text-blue-600">{{ count($recoveryCodes) }} unused codes</span></p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="printCodes()" class="btn btn-secondary text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print
                        </button>
                    </div>
                </div>

                <!-- Codes Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6" id="codesContainer">
                    @foreach($recoveryCodes as $index => $code)
                    <div class="group relative bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="text-xs font-semibold text-gray-500 mr-2">Code #{{ $index + 1 }}</span>
                                    <span class="px-2 py-0.5 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Unused</span>
                                </div>
                                <div class="font-mono text-lg font-bold text-gray-900 tracking-wider select-all" id="code-{{ $index }}">
                                    {{ $code }}
                                </div>
                            </div>
                            <button 
                                onclick="copySingleCode('{{ $code }}', {{ $index }})" 
                                class="ml-3 p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                title="Copy code"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Bulk Actions -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex flex-wrap gap-3">
                        <button onclick="copyAllCodes()" class="btn btn-secondary flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            Copy All Codes
                        </button>
                        
                        <a href="{{ route('auth.two-factor.recovery-codes.download') }}" class="btn btn-secondary flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download as TXT
                        </a>
                        
                        <form action="{{ route('auth.two-factor.recovery-codes.regenerate') }}" method="POST" class="flex-1">
                            @csrf
                            <button 
                                type="submit" 
                                class="btn btn-danger w-full flex items-center justify-center" 
                                onclick="return confirm('⚠️ WARNING: This will invalidate all existing recovery codes. Are you sure you want to regenerate them?')"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Regenerate All Codes
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- How to Use Recovery Codes -->
            <div class="card bg-blue-50 border-blue-200">
                <div class="flex items-start mb-4">
                    <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-blue-900">How to Use Recovery Codes</h3>
                </div>
                <ol class="space-y-3 text-sm text-blue-800">
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-6 h-6 bg-blue-200 text-blue-800 rounded-full flex items-center justify-center text-xs font-semibold mr-3">1</span>
                        <span>If you lose access to your authenticator device, go to the login page</span>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-6 h-6 bg-blue-200 text-blue-800 rounded-full flex items-center justify-center text-xs font-semibold mr-3">2</span>
                        <span>Enter your email and password as usual</span>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-6 h-6 bg-blue-200 text-blue-800 rounded-full flex items-center justify-center text-xs font-semibold mr-3">3</span>
                        <span>When prompted for a 2FA code, click "Use Recovery Code"</span>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-6 h-6 bg-blue-200 text-blue-800 rounded-full flex items-center justify-center text-xs font-semibold mr-3">4</span>
                        <span>Enter one of your recovery codes (each code can only be used once)</span>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-6 h-6 bg-blue-200 text-blue-800 rounded-full flex items-center justify-center text-xs font-semibold mr-3">5</span>
                        <span>After using a recovery code, regenerate new codes for security</span>
                    </li>
                </ol>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Security Tips -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Security Best Practices</h3>
                <div class="space-y-3">
                    <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                        <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Store Securely</p>
                            <p class="text-xs text-gray-600 mt-1">Save codes in a password manager or secure physical location</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                        <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Keep Separate</p>
                            <p class="text-xs text-gray-600 mt-1">Don't store recovery codes with your password</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                        <svg class="w-5 h-5 text-yellow-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-900">One-Time Use</p>
                            <p class="text-xs text-gray-600 mt-1">Each code can only be used once. After use, regenerate new codes</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                        <svg class="w-5 h-5 text-red-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Don't Share</p>
                            <p class="text-xs text-gray-600 mt-1">Never share your recovery codes with anyone</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card bg-gradient-to-br from-blue-50 to-purple-50 border-blue-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recovery Code Status</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Total Codes</span>
                            <span class="text-lg font-bold text-gray-900">{{ count($recoveryCodes) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-blue-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Unused Codes</span>
                            <span class="text-lg font-bold text-green-600">{{ count($recoveryCodes) }}</span>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-sm text-gray-600">Used Codes</span>
                            <span class="text-lg font-bold text-gray-400">0</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('profile.security') }}" class="block w-full btn btn-primary text-center">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Back to Security Settings
                    </a>
                    <a href="{{ route('dashboard.index') }}" class="block w-full btn btn-secondary text-center">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Go to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #printSection, #printSection * {
        visibility: visible;
    }
    #printSection {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>

@push('scripts')
<script>
const recoveryCodes = @json($recoveryCodes);

function copySingleCode(code, index) {
    navigator.clipboard.writeText(code).then(() => {
        const codeElement = document.getElementById(`code-${index}`);
        const originalText = codeElement.textContent;
        
        // Visual feedback
        codeElement.parentElement.parentElement.classList.add('ring-2', 'ring-green-500');
        codeElement.textContent = 'Copied!';
        codeElement.classList.add('text-green-600');
        
        setTimeout(() => {
            codeElement.textContent = originalText;
            codeElement.classList.remove('text-green-600');
            codeElement.parentElement.parentElement.classList.remove('ring-2', 'ring-green-500');
        }, 1500);
    }).catch(err => {
        console.error('Failed to copy:', err);
        alert('Failed to copy code. Please try again.');
    });
}

function copyAllCodes() {
    const text = recoveryCodes.join('\n');
    
    navigator.clipboard.writeText(text).then(() => {
        const btn = event.target.closest('button');
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Copied!';
        btn.classList.add('bg-green-500', 'hover:bg-green-600');
        btn.classList.remove('bg-gray-200');
        
        setTimeout(() => {
            btn.innerHTML = originalHtml;
            btn.classList.remove('bg-green-500', 'hover:bg-green-600');
            btn.classList.add('bg-gray-200');
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy:', err);
        alert('Failed to copy codes. Please try again.');
    });
}

function printCodes() {
    // Create print-friendly content
    const printContent = `
        <div style="padding: 40px; font-family: Arial, sans-serif;">
            <h1 style="color: #1f2937; margin-bottom: 10px;">FXEngine - Recovery Codes</h1>
            <p style="color: #6b7280; margin-bottom: 30px;">Generated: ${new Date().toLocaleString()}</p>
            
            <div style="background: #fef2f2; border-left: 4px solid #ef4444; padding: 20px; margin-bottom: 30px; border-radius: 8px;">
                <h2 style="color: #991b1b; margin-bottom: 10px;">⚠️ IMPORTANT</h2>
                <p style="color: #7f1d1d; line-height: 1.6;">
                    Store these codes in a safe place. Each code can only be used once. 
                    If you lose access to your authenticator device, you'll need one of these codes to sign in.
                </p>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 30px;">
                ${recoveryCodes.map((code, index) => `
                    <div style="background: #f9fafb; border: 2px solid #e5e7eb; padding: 15px; border-radius: 8px;">
                        <div style="font-size: 12px; color: #6b7280; margin-bottom: 5px;">Code #${index + 1}</div>
                        <div style="font-family: monospace; font-size: 18px; font-weight: bold; color: #111827; letter-spacing: 2px;">${code}</div>
                    </div>
                `).join('')}
            </div>
            
            <div style="background: #eff6ff; border-left: 4px solid #3b82f6; padding: 20px; border-radius: 8px;">
                <h3 style="color: #1e40af; margin-bottom: 10px;">How to Use</h3>
                <ol style="color: #1e3a8a; line-height: 1.8; padding-left: 20px;">
                    <li>If you lose access to your authenticator device, go to the login page</li>
                    <li>Enter your email and password as usual</li>
                    <li>When prompted for a 2FA code, click "Use Recovery Code"</li>
                    <li>Enter one of your recovery codes above</li>
                    <li>After using a recovery code, regenerate new codes for security</li>
                </ol>
            </div>
        </div>
    `;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>FXEngine Recovery Codes</title>
                <style>
                    body { margin: 0; padding: 0; }
                    @media print {
                        @page { margin: 20mm; }
                    }
                </style>
            </head>
            <body>${printContent}</body>
        </html>
    `);
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => {
        printWindow.print();
    }, 250);
}

// Select all text when clicking on a code
document.addEventListener('DOMContentLoaded', function() {
    const codeElements = document.querySelectorAll('[id^="code-"]');
    codeElements.forEach(element => {
        element.addEventListener('click', function() {
            const range = document.createRange();
            range.selectNodeContents(this);
            const selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
        });
    });
});
</script>
@endpush
@endsection
