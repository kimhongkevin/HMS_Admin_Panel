@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-gray-600 mt-1">Welcome back, {{ auth()->user()->name }}. Manage your hospital efficiently.</p>
            </div>
            <div class="text-right">
                <div class="text-lg font-semibold text-gray-900">{{ now()->format('l, F j, Y') }}</div>
                <div class="text-sm text-gray-600">{{ now()->format('h:i A') }}</div>
            </div>
        </div>

        <!-- Top Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Patients -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Patients</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalPatients) }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-green-600 text-sm font-medium flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                @php
                                    $growthRate = $totalPatients > 0 ? round(($totalPatients / max($totalPatients, 1)) * 12, 1) : 0;
                                @endphp
                                {{ $growthRate }}% increase
                            </span>
                            <span class="text-gray-500 text-sm ml-2">Last 6 months</span>
                        </div>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Doctors Available -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Doctors Available</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalDoctors }}</p>
                        <div class="mt-2">
                            @php
                                $totalDoctorsCount = \App\Models\User::where('role', 'doctor')->count();
                                $availabilityRate = $totalDoctorsCount > 0 ? round(($totalDoctors / $totalDoctorsCount) * 100) : 0;
                            @endphp
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-green-500 rounded-full" style="width: {{ $availabilityRate }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $availabilityRate }}% availability rate</p>
                        </div>
                    </div>
                    <div class="bg-green-50 p-3 rounded-lg">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Appointments Today -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Appointments Today</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $todayAppointments }}</p>
                        <div class="flex items-center mt-2">
                            @php
                                $yesterdayAppointments = \App\Models\Appointment::whereDate('appointment_date', now()->subDay())->count();
                                $todayDiff = $todayAppointments - $yesterdayAppointments;
                                $diffColor = $todayDiff >= 0 ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800';
                                $diffText = $todayDiff >= 0 ? "+{$todayDiff}" : "{$todayDiff}";
                            @endphp
                            <span class="{{ $diffColor }} text-xs font-semibold px-2.5 py-0.5 rounded">
                                {{ $diffText }} from yesterday
                            </span>
                        </div>
                    </div>
                    <div class="bg-purple-50 p-3 rounded-lg">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Revenue (Total) -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($totalRevenue, 2) }}</p>
                        <div class="flex items-center mt-2">
                            @php
                                $lastMonthRevenue = \App\Models\Invoice::where('status', 'paid')
                                    ->whereMonth('created_at', now()->subMonth()->month)
                                    ->sum('total');
                                $thisMonthRevenue = \App\Models\Invoice::where('status', 'paid')
                                    ->whereMonth('created_at', now()->month)
                                    ->sum('total');
                                $growth = $lastMonthRevenue > 0 ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) : 0;
                                $growthColor = $growth >= 0 ? 'text-green-600' : 'text-red-600';
                                $growthSign = $growth >= 0 ? '+' : '';
                            @endphp
                            <span class="{{ $growthColor }} text-sm font-medium flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                {{ $growthSign }}{{ $growth }}% growth
                            </span>
                        </div>
                    </div>
                    <div class="bg-yellow-50 p-3 rounded-lg">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle Row: Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Patient Growth Chart -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Patient Growth</h3>
                    <span class="text-sm text-gray-500">Last 6 months</span>
                </div>
                <div class="h-64">
                    <div class="flex items-end h-48 space-x-4 pt-4">
                        @foreach($patientGrowth['months'] as $index => $month)
                            @php
                                $maxValue = max($patientGrowth['values']) ?: 1;
                                $height = ($patientGrowth['values'][$index] / $maxValue) * 100;
                                $color = $index == count($patientGrowth['months'])-1 ? 'bg-blue-600' : 'bg-blue-400';
                            @endphp
                            <div class="flex-1 flex flex-col items-center">
                                <div class="w-full flex justify-center items-end h-48">
                                    <div class="{{ $color }} rounded-t-lg w-3/4 transition-all hover:bg-blue-700" style="height: {{ $height }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 mt-2">{{ $month }}</span>
                                <span class="text-xs font-semibold mt-1">{{ $patientGrowth['values'][$index] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Weekly Appointments Chart -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Weekly Appointments</h3>
                    <span class="text-sm text-gray-500">Current week</span>
                </div>
                <div class="h-64">
                    <div class="relative h-48 pt-8">
                        @php
                            $maxAppointments = max($weeklyAppointments['appointments']) ?: 1;
                        @endphp

                        <!-- Grid lines -->
                        <div class="absolute inset-0 flex flex-col justify-between">
                            @for($i = 0; $i <= 4; $i++)
                                <div class="border-t border-gray-100"></div>
                            @endfor
                        </div>

                        <!-- Data line and points -->
                        <svg class="absolute inset-0 w-full h-full" style="overflow: visible">
                            <!-- Draw connecting lines -->
                            @foreach($weeklyAppointments['days'] as $index => $day)
                                @if($index < count($weeklyAppointments['days']) - 1)
                                    @php
                                        $x1 = (($index + 0.5) / count($weeklyAppointments['days'])) * 100;
                                        $y1 = 100 - (($weeklyAppointments['appointments'][$index] / $maxAppointments) * 100);
                                        $x2 = (($index + 1.5) / count($weeklyAppointments['days'])) * 100;
                                        $y2 = 100 - (($weeklyAppointments['appointments'][$index + 1] / $maxAppointments) * 100);
                                    @endphp
                                    <line x1="{{ $x1 }}%" y1="{{ $y1 }}%" x2="{{ $x2 }}%" y2="{{ $y2 }}%" stroke="#a78bfa" stroke-width="2"/>
                                @endif
                            @endforeach

                            <!-- Draw data points -->
                            @foreach($weeklyAppointments['days'] as $index => $day)
                                @php
                                    $x = (($index + 0.5) / count($weeklyAppointments['days'])) * 100;
                                    $y = 100 - (($weeklyAppointments['appointments'][$index] / $maxAppointments) * 100);
                                    $isCurrent = $day === now()->format('D');
                                @endphp
                                <circle cx="{{ $x }}%" cy="{{ $y }}%" r="5" fill="{{ $isCurrent ? '#7c3aed' : '#a78bfa' }}" stroke="white" stroke-width="2"/>
                            @endforeach
                        </svg>

                        <!-- Labels -->
                        <div class="absolute inset-0 flex items-end justify-between px-2">
                            @foreach($weeklyAppointments['days'] as $index => $day)
                                <div class="flex flex-col items-center flex-1">
                                    <span class="text-xs font-semibold text-purple-600 mb-2">{{ $weeklyAppointments['appointments'][$index] }}</span>
                                    <span class="text-xs text-gray-500">{{ $day }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Row: Recent Patients & Department Distribution -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Patients -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Patients</h3>
                    <a href="{{ route('patients.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        View all →
                    </a>
                </div>
                <div class="space-y-4">
                    @forelse($recentPatients as $patient)
                        <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <span class="font-bold text-blue-700">{{ $patient['initials'] }}</span>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $patient['name'] }}</h4>
                                        <p class="text-sm text-gray-500">{{ $patient['age'] }} years • {{ $patient['department'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        @php
                                            $statusColors = [
                                                'Critical' => 'bg-red-100 text-red-800',
                                                'Stable' => 'bg-green-100 text-green-800',
                                                'Recovering' => 'bg-yellow-100 text-yellow-800',
                                                'Scheduled' => 'bg-blue-100 text-blue-800',
                                                'New' => 'bg-purple-100 text-purple-800',
                                            ];
                                        @endphp
                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusColors[$patient['status']] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $patient['status'] }}
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">{{ $patient['time'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">No patients registered yet</p>
                    @endforelse
                </div>
            </div>

            <!-- Department Distribution (FIXED PIE CHART) -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Department Distribution</h3>
                    <a href="{{ route('admin.departments.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        View all →
                    </a>
                </div>

                @if(count($departmentDistribution) > 0)
                <div class="flex items-center h-64">
                    <!-- Donut Chart using CSS -->
                    <div class="w-1/2 flex justify-center">
                        <div class="relative w-48 h-48">
                            @php
                                $cumulativePercent = 0;
                                $colors = [
                                    'bg-blue-500' => '#3B82F6',
                                    'bg-green-500' => '#10B981',
                                    'bg-purple-500' => '#8B5CF6',
                                    'bg-yellow-500' => '#F59E0B',
                                    'bg-pink-500' => '#EC4899',
                                    'bg-indigo-500' => '#6366F1',
                                    'bg-red-500' => '#EF4444',
                                ];
                            @endphp

                            <!-- SVG Pie Chart -->
                            <svg viewBox="0 0 200 200" class="transform -rotate-90">
                                @foreach($departmentDistribution as $index => $dept)
                                    @php
                                        $colorClass = $dept['color'];
                                        $hexColor = $colors[$colorClass] ?? '#6B7280';
                                        $percentage = $dept['percentage'];
                                        $offset = $cumulativePercent;
                                        $cumulativePercent += $percentage;

                                        // Calculate stroke-dasharray for pie slice
                                        $circumference = 2 * pi() * 85; // radius = 85 for a nice donut
                                        $dashArray = ($percentage / 100) * $circumference;
                                        $dashOffset = -($offset / 100) * $circumference;
                                    @endphp

                                    <circle
                                        cx="100"
                                        cy="100"
                                        r="85"
                                        fill="transparent"
                                        stroke="{{ $hexColor }}"
                                        stroke-width="40"
                                        stroke-dasharray="{{ $dashArray }} {{ $circumference }}"
                                        stroke-dashoffset="{{ $dashOffset }}"
                                        class="transition-all hover:stroke-width-[45]"
                                    />
                                @endforeach

                                <!-- Center white circle for donut effect -->
                                <circle cx="100" cy="100" r="65" fill="white"/>
                            </svg>

                            <!-- Center text -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-900">{{ array_sum(array_column($departmentDistribution, 'count')) }}</div>
                                    <div class="text-xs text-gray-500">Total</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="w-1/2 pl-6 space-y-3 max-h-64 overflow-y-auto">
                        @foreach($departmentDistribution as $dept)
                            <div class="flex items-center">
                                <div class="w-4 h-4 {{ $dept['color'] }} rounded mr-3 flex-shrink-0"></div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-sm font-medium text-gray-900 truncate">{{ $dept['name'] }}</span>
                                        <span class="text-sm font-semibold text-gray-900 ml-2">{{ $dept['percentage'] }}%</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full {{ $dept['color'] }}" style="width: {{ $dept['percentage'] }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-500 ml-2">({{ $dept['count'] }})</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="flex items-center justify-center h-64">
                    <p class="text-gray-500">No department data available</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions (Only for Admin) -->
        @if(auth()->user()->isAdmin())
        <div class="mt-8 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <a href="{{ route('patients.create') }}" class="flex flex-col items-center justify-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                    <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-900 text-center">Add Patient</span>
                </a>

                <a href="{{ route('appointments.create') }}" class="flex flex-col items-center justify-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                    <svg class="w-8 h-8 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-900 text-center">Schedule Appointment</span>
                </a>

                <a href="{{ route('invoices.create') }}" class="flex flex-col items-center justify-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                    <svg class="w-8 h-8 text-yellow-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="text-sm font-medium text-gray-900 text-center">Create Invoice</span>
                </a>

                <a href="{{ route('documents.create') }}" class="flex flex-col items-center justify-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                    <svg class="w-8 h-8 text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-900 text-center">Upload Document</span>
                </a>

                <a href="{{ route('admin.doctors.create') }}" class="flex flex-col items-center justify-center p-4 bg-pink-50 rounded-lg hover:bg-pink-100 transition">
                    <svg class="w-8 h-8 text-pink-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-900 text-center">Add Doctor</span>
                </a>

                <a href="{{ route('admin.staff.create') }}" class="flex flex-col items-center justify-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                    <svg class="w-8 h-8 text-indigo-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-900 text-center">Add Staff</span>
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
