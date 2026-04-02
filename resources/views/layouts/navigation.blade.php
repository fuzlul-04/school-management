<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @can('roles.manage')
                    <x-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.*')">
                        {{ __('Roles') }}
                    </x-nav-link>
                    @endcan
                    @can('users.manage')
                    <x-nav-link :href="route('permissions.index')" :active="request()->routeIs('permissions.*')">
                        {{ __('Permissions') }}
                    </x-nav-link>
                    @endcan

                    <!-- Academic Management Dropdown -->
                    <div class="relative" x-data="{ academicOpen: false }">
                        <a href="#" @click.prevent="academicOpen = !academicOpen" class="inline-flex items-center px-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            Academic
                            <svg class="fill-current h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <div x-show="academicOpen" @click.away="academicOpen = false" class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" style="display: none;">
                            <x-dropdown-link :href="route('classrooms.index')">
                                {{ __('Classes') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('subjects.index')">
                                {{ __('Subjects') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('teacher-subjects.index')">
                                {{ __('Teacher Subjects') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('teacher-classes.index')">
                                {{ __('Teacher Classes') }}
                            </x-dropdown-link>
                            <div class="border-t border-gray-100"></div>
                            <x-dropdown-link :href="route('class-routines.index')">
                                {{ __('Class Routines') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('class-routines.timetable')">
                                {{ __('Timetable') }}
                            </x-dropdown-link>
                            <div class="border-t border-gray-100"></div>
                            <x-dropdown-link :href="route('academic-years.index')">
                                {{ __('Academic Years') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('teachers.index')">
                                {{ __('Teachers') }}
                            </x-dropdown-link>
                            <div class="border-t border-gray-100"></div>
                            <x-dropdown-link :href="route('admissions.index')">
                                {{ __('Admissions') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('students.index')">
                                {{ __('Students') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('guardians.index')">
                                {{ __('Guardians') }}
                            </x-dropdown-link>
                        </div>
                    </div>

                    <!-- Finance Management Dropdown -->
                    <div class="relative" x-data="{ financeOpen: false }">
                        <a href="#" @click.prevent="financeOpen = !financeOpen" class="inline-flex items-center px-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            Finance
                            <svg class="fill-current h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <div x-show="financeOpen" @click.away="financeOpen = false" class="absolute left-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 z-50" style="display: none;">
                            <x-dropdown-link :href="route('finance.index')">
                                {{ __('Dashboard') }}
                            </x-dropdown-link>
                            <div class="border-t border-gray-100"></div>
                            <x-dropdown-link :href="route('fee-types.index')">
                                {{ __('Fee Types') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('fee-structures.index')">
                                {{ __('Fee Structures') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('invoices.index')">
                                {{ __('Invoices') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('invoices.generate')">
                                {{ __('Generate Invoices') }}
                            </x-dropdown-link>
                            <div class="border-t border-gray-100"></div>
                            <x-dropdown-link :href="route('payments.index')">
                                {{ __('Payments') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('finance.due-invoices')">
                                {{ __('Due Invoices') }}
                            </x-dropdown-link>
                            <div class="border-t border-gray-100"></div>
                            <x-dropdown-link :href="route('finance.student-summary')">
                                {{ __('Student Summary') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('finance.reports')">
                                {{ __('Reports') }}
                            </x-dropdown-link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('classrooms.index')" :active="request()->routeIs('classrooms.*')">
                {{ __('Classes') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('subjects.index')" :active="request()->routeIs('subjects.*')">
                {{ __('Subjects') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('teacher-subjects.index')" :active="request()->routeIs('teacher-subjects.*')">
                {{ __('Teacher Subjects') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('teacher-classes.index')" :active="request()->routeIs('teacher-classes.*')">
                {{ __('Teacher Classes') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('class-routines.index')" :active="request()->routeIs('class-routines.*')">
                {{ __('Class Routines') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admissions.index')" :active="request()->routeIs('admissions.*')">
                {{ __('Admissions') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">
                {{ __('Students') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('guardians.index')" :active="request()->routeIs('guardians.*')">
                {{ __('Guardians') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('finance.index')" :active="request()->routeIs('finance.*')">
                {{ __('Finance') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('fee-structures.index')" :active="request()->routeIs('fee-structures.*')">
                {{ __('Fee Structures') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')">
                {{ __('Invoices') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')">
                {{ __('Payments') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>