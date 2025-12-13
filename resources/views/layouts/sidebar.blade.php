<aside class="w-64 bg-sky-200 shadow-md min-h-screen">
    <nav class="mt-5 px-2">
        <a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md text-gray-900 hover:bg-gray-50">
            <svg class="mr-4 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Dashboard
        </a>

        @if(auth()->user()->isAdmin())
        <!-- Admin Menu -->
        <div class="mt-2">
            <h3 class="px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Management</h3>
            <a href="{{ route('admin.doctors.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md text-gray-600 hover:bg-gray-50">
                Doctors
            </a>
            <a href="{{ route('admin.staff.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md text-gray-600 hover:bg-gray-50">
                Staff
            </a>
            <a href="{{ route('admin.departments.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md text-gray-600 hover:bg-gray-50">
                Departments
            </a>

            <a href="{{ route('admin.fee-categories.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md text-gray-600 hover:bg-gray-50">
                Fee Categories
            </a>
            <a href="{{ route('admin.fees.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md text-gray-600 hover:bg-gray-50">
                Fee Management
            </a>
        </div>
        @endif

        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
        <!-- Patient & Invoice Menu -->
        <div class="mt-2">
            <a href="{{ route('patients.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md text-gray-600 hover:bg-gray-50">
                Patients
            </a>
            <a href="{{ route('invoices.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md text-gray-600 hover:bg-gray-50">
                Invoices
            </a>
            <a href="{{ route('appointments.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md text-gray-600 hover:bg-gray-50">
                Appointments
            </a>
        </div>
        @endif

        <!-- Documents Menu - All roles -->
        <div class="mt-2">
            <a href="{{ route('documents.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md text-gray-600 hover:bg-gray-50">
                Documents
            </a>

        </div>
    </nav>
</aside>
