@extends('layouts.app')

@section('title', 'Economic Calendar - FXEngine')
@section('page-title', 'Economic Calendar')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Economic Calendar</h2>
            <p class="text-sm text-gray-600 mt-1">Track important economic events and their impact on markets</p>
        </div>
        <div class="flex items-center space-x-3">
            <select id="filterCountry" class="form-input text-sm">
                <option value="all">All Countries</option>
                <option value="US">United States</option>
                <option value="EU">European Union</option>
                <option value="GB">United Kingdom</option>
                <option value="JP">Japan</option>
                <option value="AU">Australia</option>
                <option value="CA">Canada</option>
                <option value="CH">Switzerland</option>
                <option value="NZ">New Zealand</option>
            </select>
            <select id="filterImpact" class="form-input text-sm">
                <option value="all">All Impact</option>
                <option value="high">High Impact</option>
                <option value="medium">Medium Impact</option>
                <option value="low">Low Impact</option>
            </select>
            <button id="refreshCalendar" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Calendar Navigation -->
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-4">
                <button id="prevWeek" class="btn btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <h3 class="text-lg font-semibold text-gray-900" id="currentWeek">This Week</h3>
                <button id="nextWeek" class="btn btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
            <button id="todayBtn" class="btn btn-secondary text-sm">Today</button>
        </div>

        <!-- Calendar Days -->
        <div class="grid grid-cols-7 gap-2 mb-4" id="calendarDays">
            <!-- Days will be populated by JavaScript -->
        </div>
    </div>

    <!-- Events List -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Upcoming Events</h3>
        <div id="eventsList" class="space-y-3">
            <!-- Events will be populated by JavaScript -->
        </div>
    </div>

    <!-- Event Details Modal -->
    <div id="eventModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200 sticky top-0 bg-white">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900" id="eventTitle">Event Details</h3>
                    <button id="closeEventModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="px-6 py-4" id="eventDetails">
                <!-- Event details will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentDate = new Date();
    let events = [];

    // Sample economic events data
    const sampleEvents = [
        {
            id: 1,
            date: new Date(),
            time: '08:30',
            country: 'US',
            event: 'Non-Farm Payrolls',
            impact: 'high',
            previous: '150K',
            forecast: '180K',
            actual: null,
            currency: 'USD'
        },
        {
            id: 2,
            date: new Date(),
            time: '10:00',
            country: 'EU',
            event: 'ECB Interest Rate Decision',
            impact: 'high',
            previous: '4.50%',
            forecast: '4.50%',
            actual: null,
            currency: 'EUR'
        },
        {
            id: 3,
            date: new Date(Date.now() + 86400000),
            time: '09:00',
            country: 'GB',
            event: 'GDP Growth Rate',
            impact: 'medium',
            previous: '0.2%',
            forecast: '0.3%',
            actual: null,
            currency: 'GBP'
        },
        {
            id: 4,
            date: new Date(Date.now() + 86400000),
            time: '12:30',
            country: 'US',
            event: 'CPI (Consumer Price Index)',
            impact: 'high',
            previous: '3.2%',
            forecast: '3.1%',
            actual: null,
            currency: 'USD'
        },
        {
            id: 5,
            date: new Date(Date.now() + 2 * 86400000),
            time: '02:00',
            country: 'JP',
            event: 'Bank of Japan Policy Rate',
            impact: 'high',
            previous: '-0.10%',
            forecast: '-0.10%',
            actual: null,
            currency: 'JPY'
        }
    ];

    function initializeCalendar() {
        renderCalendarDays();
        loadEvents();
    }

    function renderCalendarDays() {
        const daysContainer = document.getElementById('calendarDays');
        const startOfWeek = new Date(currentDate);
        startOfWeek.setDate(currentDate.getDate() - currentDate.getDay());
        
        const weekLabel = startOfWeek.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) + 
                         ' - ' + 
                         new Date(startOfWeek.getTime() + 6 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        document.getElementById('currentWeek').textContent = weekLabel;

        daysContainer.innerHTML = '';
        const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        
        dayNames.forEach(day => {
            const dayElement = document.createElement('div');
            dayElement.className = 'text-center py-2 text-sm font-medium text-gray-700';
            dayElement.textContent = day;
            daysContainer.appendChild(dayElement);
        });

        for (let i = 0; i < 7; i++) {
            const date = new Date(startOfWeek);
            date.setDate(startOfWeek.getDate() + i);
            const dayElement = document.createElement('div');
            dayElement.className = `text-center py-3 rounded-lg cursor-pointer transition-colors ${
                date.toDateString() === new Date().toDateString() 
                    ? 'bg-blue-100 text-blue-700 font-semibold' 
                    : 'hover:bg-gray-100'
            }`;
            dayElement.textContent = date.getDate();
            dayElement.dataset.date = date.toISOString().split('T')[0];
            dayElement.addEventListener('click', () => filterByDate(date));
            daysContainer.appendChild(dayElement);
        }
    }

    function loadEvents() {
        // In production, fetch from API
        events = sampleEvents.map(e => ({
            ...e,
            date: new Date(e.date)
        }));
        renderEvents();
    }

    function renderEvents(filteredEvents = null) {
        const eventsList = document.getElementById('eventsList');
        const displayEvents = filteredEvents || events.sort((a, b) => {
            if (a.date.getTime() !== b.date.getTime()) {
                return a.date - b.date;
            }
            return a.time.localeCompare(b.time);
        });

        if (displayEvents.length === 0) {
            eventsList.innerHTML = '<p class="text-center text-gray-500 py-8">No events found for selected filters.</p>';
            return;
        }

        eventsList.innerHTML = displayEvents.map(event => {
            const impactColors = {
                high: 'bg-red-100 text-red-800 border-red-300',
                medium: 'bg-yellow-100 text-yellow-800 border-yellow-300',
                low: 'bg-green-100 text-green-800 border-green-300'
            };

            const countryFlags = {
                'US': '🇺🇸',
                'EU': '🇪🇺',
                'GB': '🇬🇧',
                'JP': '🇯🇵',
                'AU': '🇦🇺',
                'CA': '🇨🇦',
                'CH': '🇨🇭',
                'NZ': '🇳🇿'
            };

            return `
                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors cursor-pointer" onclick="showEventDetails(${event.id})">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <span class="text-2xl">${countryFlags[event.country] || '🌍'}</span>
                                <div>
                                    <h4 class="font-semibold text-gray-900">${event.event}</h4>
                                    <p class="text-sm text-gray-600">${event.country} • ${event.currency}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4 mt-3 text-sm">
                                <div>
                                    <span class="text-gray-600">Time:</span>
                                    <span class="font-medium ml-2">${event.time}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Previous:</span>
                                    <span class="font-medium ml-2">${event.previous}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Forecast:</span>
                                    <span class="font-medium ml-2">${event.forecast}</span>
                                </div>
                            </div>
                        </div>
                        <div class="ml-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium border ${impactColors[event.impact]}">
                                ${event.impact.toUpperCase()}
                            </span>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function showEventDetails(eventId) {
        const event = events.find(e => e.id === eventId);
        if (!event) return;

        document.getElementById('eventTitle').textContent = event.event;
        document.getElementById('eventDetails').innerHTML = `
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Date & Time</p>
                        <p class="font-semibold">${event.date.toLocaleDateString()} at ${event.time}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Country</p>
                        <p class="font-semibold">${event.country}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Currency</p>
                        <p class="font-semibold">${event.currency}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Impact</p>
                        <p class="font-semibold">${event.impact.toUpperCase()}</p>
                    </div>
                </div>
                <div class="border-t pt-4">
                    <p class="text-sm text-gray-600 mb-2">Previous Value</p>
                    <p class="text-lg font-semibold">${event.previous}</p>
                </div>
                <div class="border-t pt-4">
                    <p class="text-sm text-gray-600 mb-2">Forecast</p>
                    <p class="text-lg font-semibold">${event.forecast}</p>
                </div>
                <div class="border-t pt-4">
                    <p class="text-sm text-gray-600 mb-2">Actual</p>
                    <p class="text-lg font-semibold ${event.actual ? 'text-blue-600' : 'text-gray-400'}">
                        ${event.actual || 'Pending'}
                    </p>
                </div>
            </div>
        `;
        document.getElementById('eventModal').classList.remove('hidden');
    }

    function filterByDate(date) {
        const filtered = events.filter(e => 
            e.date.toDateString() === date.toDateString()
        );
        renderEvents(filtered);
    }

    // Event handlers
    document.getElementById('prevWeek').addEventListener('click', () => {
        currentDate.setDate(currentDate.getDate() - 7);
        renderCalendarDays();
        renderEvents();
    });

    document.getElementById('nextWeek').addEventListener('click', () => {
        currentDate.setDate(currentDate.getDate() + 7);
        renderCalendarDays();
        renderEvents();
    });

    document.getElementById('todayBtn').addEventListener('click', () => {
        currentDate = new Date();
        renderCalendarDays();
        renderEvents();
    });

    document.getElementById('filterCountry').addEventListener('change', function(e) {
        const country = e.target.value;
        const impact = document.getElementById('filterImpact').value;
        applyFilters(country, impact);
    });

    document.getElementById('filterImpact').addEventListener('change', function(e) {
        const country = document.getElementById('filterCountry').value;
        const impact = e.target.value;
        applyFilters(country, impact);
    });

    function applyFilters(country, impact) {
        let filtered = events;
        
        if (country !== 'all') {
            filtered = filtered.filter(e => e.country === country);
        }
        
        if (impact !== 'all') {
            filtered = filtered.filter(e => e.impact === impact);
        }
        
        renderEvents(filtered);
    }

    document.getElementById('closeEventModal').addEventListener('click', () => {
        document.getElementById('eventModal').classList.add('hidden');
    });

    document.getElementById('refreshCalendar').addEventListener('click', () => {
        loadEvents();
    });

    // Make showEventDetails available globally
    window.showEventDetails = showEventDetails;

    // Initialize
    initializeCalendar();
});
</script>
@endpush
@endsection

