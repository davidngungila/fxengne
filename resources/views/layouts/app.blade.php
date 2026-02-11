<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'FxEngne - Personal Trading System')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0 -translate-x-full">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">FX</span>
                        </div>
                        <span class="text-xl font-bold text-gray-900">FxEngne</span>
                    </div>
                    <button id="sidebar-close" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto py-4 px-3">
                    <ul class="space-y-1">
                        <!-- Dashboard -->
                        <li>
                            <a href="{{ route('dashboard.index') }}" class="nav-item {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <!-- Trading -->
                        <li>
                            <div class="nav-dropdown">
                                <button class="nav-item nav-dropdown-toggle {{ request()->routeIs('trading.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <span>Trading</span>
                                    <svg class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <ul class="nav-dropdown-menu">
                                    <li><a href="{{ route('trading.index') }}" class="nav-dropdown-item">Overview</a></li>
                                    <li><a href="{{ route('trading.open-trades') }}" class="nav-dropdown-item">Open Trades</a></li>
                                    <li><a href="{{ route('trading.history') }}" class="nav-dropdown-item">Trade History</a></li>
                                    <li><a href="{{ route('trading.manual-entry') }}" class="nav-dropdown-item">Manual Trade Entry</a></li>
                                </ul>
                            </div>
                        </li>

                        <!-- Strategies -->
                        <li>
                            <div class="nav-dropdown">
                                <button class="nav-item nav-dropdown-toggle {{ request()->routeIs('strategies.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                    <span>Strategies</span>
                                    <svg class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <ul class="nav-dropdown-menu">
                                    <li><a href="{{ route('strategies.index') }}" class="nav-dropdown-item">All Strategies</a></li>
                                    <li><a href="{{ route('strategies.create') }}" class="nav-dropdown-item">Create Strategy</a></li>
                                    <li><a href="{{ route('strategies.backtesting') }}" class="nav-dropdown-item">Strategy Backtesting</a></li>
                                    <li><a href="{{ route('strategies.performance') }}" class="nav-dropdown-item">Strategy Performance</a></li>
                                </ul>
                            </div>
                        </li>

                        <!-- Risk Management -->
                        <li>
                            <div class="nav-dropdown">
                                <button class="nav-item nav-dropdown-toggle {{ request()->routeIs('risk.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    <span>Risk Management</span>
                                    <svg class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <ul class="nav-dropdown-menu">
                                    <li><a href="{{ route('risk.index') }}" class="nav-dropdown-item">Risk Settings</a></li>
                                    <li><a href="{{ route('risk.daily-limits') }}" class="nav-dropdown-item">Daily Limits</a></li>
                                    <li><a href="{{ route('risk.drawdown') }}" class="nav-dropdown-item">Drawdown Protection</a></li>
                                    <li><a href="{{ route('risk.exposure') }}" class="nav-dropdown-item">Exposure Control</a></li>
                                    <li><a href="{{ route('risk.lot-calculator') }}" class="nav-dropdown-item">Lot Size Calculator</a></li>
                                </ul>
                            </div>
                        </li>

                        <!-- Analytics -->
                        <li>
                            <div class="nav-dropdown">
                                <button class="nav-item nav-dropdown-toggle {{ request()->routeIs('analytics.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>Analytics</span>
                                    <svg class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <ul class="nav-dropdown-menu">
                                    <li><a href="{{ route('analytics.index') }}" class="nav-dropdown-item">Performance Reports</a></li>
                                    <li><a href="{{ route('analytics.pair-performance') }}" class="nav-dropdown-item">Pair Performance</a></li>
                                    <li><a href="{{ route('analytics.time-analysis') }}" class="nav-dropdown-item">Time-Based Analysis</a></li>
                                    <li><a href="{{ route('analytics.win-loss') }}" class="nav-dropdown-item">Win/Loss Analysis</a></li>
                                    <li><a href="{{ route('analytics.risk-metrics') }}" class="nav-dropdown-item">Risk Metrics</a></li>
                                </ul>
                            </div>
                        </li>

                        <!-- Trading Journal -->
                        <li>
                            <div class="nav-dropdown">
                                <button class="nav-item nav-dropdown-toggle {{ request()->routeIs('journal.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    <span>Trading Journal</span>
                                    <svg class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <ul class="nav-dropdown-menu">
                                    <li><a href="{{ route('journal.index') }}" class="nav-dropdown-item">Journal Entries</a></li>
                                    <li><a href="{{ route('journal.add-note') }}" class="nav-dropdown-item">Add Trade Note</a></li>
                                    <li><a href="{{ route('journal.mistakes') }}" class="nav-dropdown-item">Mistake Tracking</a></li>
                                    <li><a href="{{ route('journal.weekly-review') }}" class="nav-dropdown-item">Weekly Review</a></li>
                                </ul>
                            </div>
                        </li>

                        <!-- Signals -->
                        <li>
                            <div class="nav-dropdown">
                                <button class="nav-item nav-dropdown-toggle {{ request()->routeIs('signals.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                    <span>Signals</span>
                                    <svg class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <ul class="nav-dropdown-menu">
                                    <li><a href="{{ route('signals.index') }}" class="nav-dropdown-item">Active Signals</a></li>
                                    <li><a href="{{ route('signals.active') }}" class="nav-dropdown-item">Signal History</a></li>
                                    <li><a href="{{ route('signals.alerts') }}" class="nav-dropdown-item">Alert Settings</a></li>
                                </ul>
                            </div>
                        </li>

                        <!-- Backtesting -->
                        <li>
                            <div class="nav-dropdown">
                                <button class="nav-item nav-dropdown-toggle {{ request()->routeIs('backtesting.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <span>Backtesting</span>
                                    <svg class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <ul class="nav-dropdown-menu">
                                    <li><a href="{{ route('backtesting.index') }}" class="nav-dropdown-item">Run Backtest</a></li>
                                    <li><a href="{{ route('backtesting.historical-data') }}" class="nav-dropdown-item">Historical Data</a></li>
                                    <li><a href="{{ route('backtesting.reports') }}" class="nav-dropdown-item">Backtest Reports</a></li>
                                    <li><a href="{{ route('backtesting.compare') }}" class="nav-dropdown-item">Compare Results</a></li>
                                </ul>
                            </div>
                        </li>

                        <!-- Broker & Execution -->
                        <li>
                            <div class="nav-dropdown">
                                <button class="nav-item nav-dropdown-toggle {{ request()->routeIs('broker.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                                    </svg>
                                    <span>Broker & Execution</span>
                                    <svg class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <ul class="nav-dropdown-menu">
                                    <li><a href="{{ route('broker.index') }}" class="nav-dropdown-item">Broker Connection</a></li>
                                    <li><a href="{{ route('broker.api-settings') }}" class="nav-dropdown-item">API Settings</a></li>
                                    <li><a href="{{ route('broker.execution-logs') }}" class="nav-dropdown-item">Execution Logs</a></li>
                                    <li><a href="{{ route('broker.vps-status') }}" class="nav-dropdown-item">VPS Status</a></li>
                                </ul>
                            </div>
                        </li>

                        <!-- Market Tools -->
                        <li>
                            <div class="nav-dropdown">
                                <button class="nav-item nav-dropdown-toggle {{ request()->routeIs('market-tools.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Market Tools</span>
                                    <svg class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <ul class="nav-dropdown-menu">
                                    <li><a href="{{ route('market-tools.index') }}" class="nav-dropdown-item">Economic Calendar</a></li>
                                    <li><a href="{{ route('market-tools.spread-monitor') }}" class="nav-dropdown-item">Spread Monitor</a></li>
                                    <li><a href="{{ route('market-tools.trading-sessions') }}" class="nav-dropdown-item">Trading Sessions</a></li>
                                </ul>
                            </div>
                        </li>

                        <!-- Profile -->
                        <li>
                            <div class="nav-dropdown">
                                <button class="nav-item nav-dropdown-toggle {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>Profile</span>
                                    <svg class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <ul class="nav-dropdown-menu">
                                    <li><a href="{{ route('profile.index') }}" class="nav-dropdown-item">Account Settings</a></li>
                                    <li><a href="{{ route('profile.security') }}" class="nav-dropdown-item">Security (2FA)</a></li>
                                    <li><a href="{{ route('profile.notifications') }}" class="nav-dropdown-item">Notification Settings</a></li>
                                    <li><a href="{{ route('profile.logs') }}" class="nav-dropdown-item">System Logs</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Sidebar Overlay (Mobile) -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 lg:hidden hidden"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-64">
            <!-- Top Bar -->
            <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 lg:px-6">
                <div class="flex items-center space-x-4">
                    <button id="sidebar-toggle" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <div class="relative header-dropdown">
                        <button class="relative p-2 text-gray-500 hover:text-gray-700 header-dropdown-toggle">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500"></span>
                        </button>
                        <div class="header-dropdown-menu">
                            <div class="px-4 py-3 border-b border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900">New Trade Executed</p>
                                            <p class="text-xs text-gray-500 mt-1">EURUSD BUY order opened at 1.0850</p>
                                            <p class="text-xs text-gray-400 mt-1">2 minutes ago</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900">Take Profit Hit</p>
                                            <p class="text-xs text-gray-500 mt-1">GBPUSD trade closed with +$150 profit</p>
                                            <p class="text-xs text-gray-400 mt-1">15 minutes ago</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900">Risk Limit Warning</p>
                                            <p class="text-xs text-gray-500 mt-1">Daily loss limit at 80%</p>
                                            <p class="text-xs text-gray-400 mt-1">1 hour ago</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="px-4 py-2 border-t border-gray-200 bg-gray-50">
                                <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View all notifications</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Section -->
                    <div class="relative header-dropdown">
                        <button class="flex items-center space-x-3 pl-4 border-l border-gray-200 header-dropdown-toggle">
                            <div class="text-left">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'User' }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                                <p class="text-xs text-blue-600 font-medium mt-0.5">{{ auth()->user()->role ?? 'Trader' }}</p>
                            </div>
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-sm">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                            </div>
                        </button>
                        <div class="header-dropdown-menu">
                            <div class="px-4 py-3 border-b border-gray-200">
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name ?? 'User' }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                            </div>
                            <div class="py-2">
                                <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span>Account Profile</span>
                                    </div>
                                </a>
                                <a href="{{ route('profile.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span>Settings</span>
                                    </div>
                                </a>
                                <a href="{{ route('profile.security') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        <span>Security & Privacy</span>
                                    </div>
                                </a>
                                <a href="{{ route('profile.notifications') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                        <span>Notification Settings</span>
                                    </div>
                                </a>
                                <a href="{{ route('profile.logs') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span>Activity Logs</span>
                                    </div>
                                </a>
                            </div>
                            <div class="px-4 py-2 border-t border-gray-200 bg-gray-50">
                                <a href="#" class="block text-sm text-red-600 hover:text-red-700 font-medium">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        <span>Sign Out</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-4 lg:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
