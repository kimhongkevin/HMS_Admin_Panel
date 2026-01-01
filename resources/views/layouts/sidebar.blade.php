<aside class="w-72 bg-sky-200 shadow-2xl min-h-screen border-r border-sky-100">
    <nav class="mt-6 px-4">
        <!-- Dashboard Link -->
        <a href="{{ route('dashboard') }}" class="group flex items-center px-4 py-3.5 text-base font-semibold rounded-xl text-sky-700 bg-gradient-to-r from-sky-50 to-sky-100 hover:from-sky-100 hover:to-sky-200 transition-all duration-200 shadow-sm hover:shadow-md mb-2">
            <div class="mr-4 p-2 bg-gradient-to-br from-sky-400 to-sky-600 rounded-lg shadow-sm group-hover:shadow-md transition-all duration-200">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </div>
            <span class="text-sky-800">Dashboard</span>
        </a>

        @if(auth()->user()->isAdmin())
        <!-- Admin Only Menu -->
        <div class="mt-6">
            <div class="flex items-center px-4 mb-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-sky-300 to-transparent"></div>
                <h3 class="px-3 text-xs font-bold text-sky-600 uppercase tracking-wider">Admin Management</h3>
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-sky-300 to-transparent"></div>
            </div>

            <a href="{{ route('admin.doctors.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-700 hover:text-sky-700 hover:bg-sky-50 transition-all duration-200 mb-1.5">
                <div class="mr-3 p-1.5 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-colors">
                    <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                Doctors
            </a>

            <a href="{{ route('admin.staff.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-700 hover:text-sky-700 hover:bg-sky-50 transition-all duration-200 mb-1.5">
                <div class="mr-3 p-1.5 bg-green-100 rounded-lg group-hover:bg-green-200 transition-colors">
                    <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                Staff
            </a>

            <a href="{{ route('admin.departments.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-700 hover:text-sky-700 hover:bg-sky-50 transition-all duration-200 mb-1.5">
                <div class="mr-3 p-1.5 bg-purple-100 rounded-lg group-hover:bg-purple-200 transition-colors">
                    <svg class="h-4 w-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                Departments
            </a>

            <a href="{{ route('admin.fee-categories.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-700 hover:text-sky-700 hover:bg-sky-50 transition-all duration-200 mb-1.5">
                <div class="mr-3 p-1.5 bg-amber-100 rounded-lg group-hover:bg-amber-200 transition-colors">
                    <svg class="h-4 w-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                Fee Categories
            </a>

            <a href="{{ route('admin.fees.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-700 hover:text-sky-700 hover:bg-sky-50 transition-all duration-200 mb-1.5">
                <div class="mr-3 p-1.5 bg-emerald-100 rounded-lg group-hover:bg-emerald-200 transition-colors">
                    <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                Fees
            </a>


        </div>
        @endif

        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
        <!-- Admin & Staff Menu -->
        <div class="mt-6">
            <div class="flex items-center px-4 mb-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-sky-300 to-transparent"></div>
                <h3 class="px-3 text-xs font-bold text-sky-600 uppercase tracking-wider">Financial</h3>
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-sky-300 to-transparent"></div>
            </div>

            <a href="{{ route('invoices.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-700 hover:text-sky-700 hover:bg-sky-50 transition-all duration-200 mb-1.5">
                <div class="mr-3 p-1.5 bg-red-100 rounded-lg group-hover:bg-red-200 transition-colors">
                    <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                Invoices
            </a>

        </div>
        @endif

        <!-- All Roles Menu (Admin, Doctor, Staff) -->
        <div class="mt-6">
            <div class="flex items-center px-4 mb-3">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-sky-300 to-transparent"></div>
                <h3 class="px-3 text-xs font-bold text-sky-600 uppercase tracking-wider">Operations</h3>
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-sky-300 to-transparent"></div>
            </div>

            <a href="{{ route('patients.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-700 hover:text-sky-700 hover:bg-sky-50 transition-all duration-200 mb-1.5">
                <div class="mr-3 p-1.5 bg-yellow-100 rounded-lg group-hover:bg-yellow-200 transition-colors">
                    <svg class="h-4 w-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                Patients
            </a>

            <a href="{{ route('appointments.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-700 hover:text-sky-700 hover:bg-sky-50 transition-all duration-200 mb-1.5">
                <div class="mr-3 p-1.5 bg-indigo-100 rounded-lg group-hover:bg-indigo-200 transition-colors">
                    <svg class="h-4 w-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                Appointments
            </a>

            <a href="{{ route('documents.index') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-700 hover:text-sky-700 hover:bg-sky-50 transition-all duration-200 mb-1.5">
                <div class="mr-3 p-1.5 bg-cyan-100 rounded-lg group-hover:bg-cyan-200 transition-colors">
                    <svg class="h-4 w-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                Documents
            </a>
        </div>
    </nav>
</aside>
