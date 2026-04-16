<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'School Management') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50">
        <div class="flex">
            <aside class="w-64 bg-indigo-900 text-white min-h-screen fixed left-0 top-0">
                <div class="p-6 border-b border-indigo-800">
                    <h1 class="text-xl font-bold">School MS</h1>
                    <p class="text-xs text-indigo-300 mt-1">Student Portal</p>
                </div>
                <nav class="mt-4">
                    <a href="{{ route('student.dashboard') }}" class="flex items-center px-6 py-3 hover:bg-indigo-800 {{ request()->routeIs('student.dashboard') ? 'bg-indigo-800' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('student.live-class') }}" class="flex items-center px-6 py-3 hover:bg-indigo-800 {{ request()->routeIs('student.live-class') ? 'bg-indigo-800' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Live Classes
                    </a>
                    <a href="{{ route('student.live-exams.index') }}" class="flex items-center px-6 py-3 hover:bg-indigo-800 {{ request()->routeIs('student.live-exams.*') ? 'bg-indigo-800' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Live Exams
                    </a>
                    <a href="{{ route('student.course-content') }}" class="flex items-center px-6 py-3 hover:bg-indigo-800 {{ request()->routeIs('student.course-content') ? 'bg-indigo-800' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Course Content
                    </a>
                    <a href="{{ route('student.past-classes') }}" class="flex items-center px-6 py-3 hover:bg-indigo-800 {{ request()->routeIs('student.past-classes') ? 'bg-indigo-800' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Past Classes
                    </a>
                    <a href="{{ route('student.past-exams.index') }}" class="flex items-center px-6 py-3 hover:bg-indigo-800 {{ request()->routeIs('student.past-exams.*') ? 'bg-indigo-800' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Past Exams
                    </a>
                    <a href="{{ route('student.practice-exam.index') }}" class="flex items-center px-6 py-3 hover:bg-indigo-800 {{ request()->routeIs('student.practice-exam.*') ? 'bg-indigo-800' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Practice Exam
                    </a>
                    <a href="{{ route('student.solve-sheets') }}" class="flex items-center px-6 py-3 hover:bg-indigo-800 {{ request()->routeIs('student.solve-sheets') ? 'bg-indigo-800' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Solve Sheets
                    </a>
                    <a href="{{ route('student.qna.index') }}" class="flex items-center px-6 py-3 hover:bg-indigo-800 {{ request()->routeIs('student.qna.*') ? 'bg-indigo-800' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Q&A
                    </a>
                    <a href="{{ route('student.performance') }}" class="flex items-center px-6 py-3 hover:bg-indigo-800 {{ request()->routeIs('student.performance') ? 'bg-indigo-800' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Performance
                    </a>
                    <a href="{{ route('student.payments') }}" class="flex items-center px-6 py-3 hover:bg-indigo-800 {{ request()->routeIs('student.payments') ? 'bg-indigo-800' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Payments
                    </a>
                </nav>
            </aside>

            <div class="ml-64 flex-1">
                <header class="bg-white shadow">
                    <div class="flex items-center justify-between px-6 py-4">
                        <div class="text-sm text-gray-500">
                            @yield('header-title', 'Student Portal')
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-sm text-right">
                                <p class="font-medium text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-gray-500 text-xs">{{ Auth::user()->student->student_id ?? 'N/A' }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm text-red-600 hover:text-red-800">Logout</button>
                            </form>
                        </div>
                    </div>
                </header>
                
                <main class="p-6">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
