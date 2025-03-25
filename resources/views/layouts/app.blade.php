<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light" id="documentTheme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Super Suket') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .sidebar-logo {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.5);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }

        .sidebar-icon {
            @apply flex items-center justify-center w-8 h-8 rounded-lg text-white;
            transition: all 0.3s ease;
        }

        .sidebar-link:hover .sidebar-icon {
            transform: translateY(-2px);
        }

        .sidebar-link.active .sidebar-icon {
            @apply bg-primary;
        }

        .theme-toggle {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            z-index: 50;
        }
    </style>
</head>

<body class="min-h-screen bg-base-200 font-sans">
    <!-- Theme Toggle Button -->
    <div class="theme-toggle">
        <button id="themeToggle" class="btn btn-circle btn-sm shadow-lg">
            <i id="themeIcon" class="fas fa-moon"></i>
        </button>
    </div>

    <div class="drawer lg:drawer-open">
        <input id="drawer" type="checkbox" class="drawer-toggle" />

        <!-- Main Content -->
        <div class="drawer-content flex flex-col min-h-screen">
            <!-- Navbar -->
            <div class="navbar navbar-enhanced sticky top-0 z-50">
                <div class="flex-none lg:hidden">
                    <label for="drawer" class="btn btn-square btn-ghost drawer-button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </label>
                </div>
                <div class="flex-1">
                    <span class="text-xl font-bold text-primary">{{ config('app.name', 'Super Suket') }}</span>
                </div>
                <div class="flex-none flex items-center gap-2">
                    <!-- Theme Toggle Button -->
                    <div class="dropdown dropdown-end">
                        <label tabindex="0" class="btn btn-ghost btn-circle">
                            <div class="indicator">
                                <i id="themeIcon" class="fas fa-moon text-lg"></i>
                            </div>
                        </label>
                        <div tabindex="0"
                            class="dropdown-content z-[1] menu p-2 shadow-lg bg-base-100 dark:bg-gray-800 rounded-box w-52 mt-4">
                            <div class="p-3">
                                <h3 class="font-medium text-gray-700 dark:text-gray-200 mb-2">Pilih Tema</h3>
                                <div class="flex gap-2">
                                    <button id="lightTheme"
                                        class="theme-btn flex-1 py-2 px-3 rounded-lg flex items-center justify-center gap-2 bg-blue-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600">
                                        <i class="fas fa-sun text-amber-500"></i>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Light</span>
                                    </button>
                                    <button id="darkTheme"
                                        class="theme-btn flex-1 py-2 px-3 rounded-lg flex items-center justify-center gap-2 bg-gray-800 border border-gray-700">
                                        <i class="fas fa-moon text-blue-400"></i>
                                        <span class="text-sm font-medium text-white">Dark</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown dropdown-end">
                        <label tabindex="0" class="btn btn-ghost hover:bg-primary hover:bg-opacity-10 rounded-lg">
                            <span class="mr-2">{{ Auth::user()->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </label>
                        <form method="POST" action="{{ route('logout') }}">
                            <ul tabindex="0"
                                class="dropdown-content menu p-2 shadow-soft bg-base-100 dark:bg-gray-800 rounded-xl w-52 mt-2">
                                <li>

                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left hover:bg-primary hover:bg-opacity-10 rounded-lg">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>

                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 p-4 md:p-6 fade-in">
                @if (session('error'))
                    <div class="alert alert-error mb-4 rounded-xl">
                        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success mb-4 rounded-xl">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    </div>
                @endif

                @yield('content')
                {{ $slot ?? '' }}
            </main>
        </div>

        <!-- Sidebar -->
        <div class="drawer-side">
            <label for="drawer" class="drawer-overlay"></label>
            <aside class="bg-base-100 dark:bg-gray-800 w-80 min-h-screen shadow-md flex flex-col">
                <!-- Sidebar Header -->
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-center mb-4">
                        <div
                            class="w-16 h-16 rounded-full bg-primary flex items-center justify-center shadow-lg sidebar-logo">
                            <i class="fas fa-envelope-open-text text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="text-center">
                        <h1 class="text-xl font-bold text-primary dark:text-blue-400">Super Suket</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manajemen Surat Desa</p>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <div class="p-4 space-y-1 flex-1 overflow-y-auto pb-24">
                    <a href="{{ route('dashboard') }}"
                        class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                        <div
                            class="sidebar-icon bg-blue-500 {{ request()->routeIs('dashboard') ? 'bg-primary' : 'bg-blue-500/70' }}">
                            <i class="fas fa-home"></i>
                        </div>
                        <span class="ml-3 font-medium text-gray-700 dark:text-gray-200">Dashboard</span>
                    </a>

                    @if (Auth::user()->role->name === 'Admin')
                        <div class="divider my-3 text-sm text-gray-500 dark:text-gray-400 font-medium">Master Data</div>

                        <a href="{{ route('villages.index') }}"
                            class="sidebar-link {{ request()->routeIs('villages.*') ? 'active' : '' }} flex items-center p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                            <div
                                class="sidebar-icon bg-indigo-500 {{ request()->routeIs('villages.*') ? 'bg-primary' : 'bg-indigo-500/70' }}">
                                <i class="fas fa-building"></i>
                            </div>
                            <span class="ml-3 font-medium text-gray-700 dark:text-gray-200">Data Instansi</span>
                        </a>

                        <a href="{{ route('departments.index') }}"
                            class="sidebar-link {{ request()->routeIs('departments.*') ? 'active' : '' }} flex items-center p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                            <div
                                class="sidebar-icon bg-purple-500 {{ request()->routeIs('departments.*') ? 'bg-primary' : 'bg-purple-500/70' }}">
                                <i class="fas fa-sitemap"></i>
                            </div>
                            <span class="ml-3 font-medium text-gray-700 dark:text-gray-200">Data Departemen</span>
                        </a>

                        <a href="{{ route('letter-classifications.index') }}"
                            class="sidebar-link {{ request()->routeIs('letter-classifications.*') ? 'active' : '' }} flex items-center p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                            <div
                                class="sidebar-icon bg-pink-500 {{ request()->routeIs('letter-classifications.*') ? 'bg-primary' : 'bg-pink-500/70' }}">
                                <i class="fas fa-tags"></i>
                            </div>
                            <span class="ml-3 font-medium text-gray-700 dark:text-gray-200">Klasifikasi Surat</span>
                        </a>

                        <a href="{{ route('users.index') }}"
                            class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }} flex items-center p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                            <div
                                class="sidebar-icon bg-teal-500 {{ request()->routeIs('users.*') ? 'bg-primary' : 'bg-teal-500/70' }}">
                                <i class="fas fa-users"></i>
                            </div>
                            <span class="ml-3 font-medium text-gray-700 dark:text-gray-200">Data Pengguna</span>
                        </a>
                    @endif

                    <div class="divider my-3 text-sm text-gray-500 dark:text-gray-400 font-medium">Surat</div>

                    <div class="px-3 mb-3">
                        <a href="{{ route('outgoing-letters.create') }}" class="btn btn-primary w-full gap-2">
                            <i class="fas fa-pen-to-square"></i> Tulis Surat
                        </a>
                    </div>

                    <a href="{{ route('incoming-letters.index') }}"
                        class="sidebar-link {{ request()->routeIs('incoming-letters.*') ? 'active' : '' }} flex items-center p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                        <div
                            class="sidebar-icon bg-amber-500 {{ request()->routeIs('incoming-letters.*') ? 'bg-primary' : 'bg-amber-500/70' }}">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <span class="ml-3 font-medium text-gray-700 dark:text-gray-200">Surat Masuk</span>
                    </a>

                    <a href="{{ route('outgoing-letters.index') }}"
                        class="sidebar-link {{ request()->routeIs('outgoing-letters.*') && !request()->routeIs('outgoing-letters.kades-approval') && !request()->routeIs('outgoing-letters.umum-processing') ? 'active' : '' }} flex items-center p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                        <div
                            class="sidebar-icon bg-green-500 {{ request()->routeIs('outgoing-letters.*') && !request()->routeIs('outgoing-letters.kades-approval') && !request()->routeIs('outgoing-letters.umum-processing') ? 'bg-primary' : 'bg-green-500/70' }}">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <span class="ml-3 font-medium text-gray-700 dark:text-gray-200">Surat Keluar</span>
                    </a>
                </div>

                <!-- Sidebar Footer -->
                <div class="w-full p-4 border-t border-gray-100 dark:border-gray-700 mt-auto">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <i class="fas fa-user text-gray-500 dark:text-gray-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                    {{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ Auth::user()->role->name ?? 'User' }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-ghost btn-sm">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <script>
        // Theme toggle functionality
        const themeIcon = document.getElementById('themeIcon');
        const documentTheme = document.getElementById('documentTheme');
        const htmlElement = document.documentElement;
        const lightThemeBtn = document.getElementById('lightTheme');
        const darkThemeBtn = document.getElementById('darkTheme');

        // Check for saved theme preference or use device preference
        const savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ?
            'dark' : 'light');

        // Apply the saved theme on page load
        setTheme(savedTheme);

        // Toggle theme when buttons are clicked
        if (lightThemeBtn) {
            lightThemeBtn.addEventListener('click', () => {
                setTheme('light');
                localStorage.setItem('theme', 'light');
            });
        }

        if (darkThemeBtn) {
            darkThemeBtn.addEventListener('click', () => {
                setTheme('dark');
                localStorage.setItem('theme', 'dark');
            });
        }

        function setTheme(theme) {
            if (!documentTheme) return;

            documentTheme.setAttribute('data-theme', theme);

            if (theme === 'dark') {
                if (themeIcon) {
                    themeIcon.classList.remove('fa-moon');
                    themeIcon.classList.add('fa-sun');
                    themeIcon.classList.add('text-amber-400');
                }
                htmlElement.classList.add('dark');

                // Update button states
                if (darkThemeBtn && lightThemeBtn) {
                    darkThemeBtn.classList.add('bg-blue-900');
                    darkThemeBtn.classList.add('border-blue-700');
                    darkThemeBtn.classList.add('text-white');
                    lightThemeBtn.classList.remove('bg-blue-100');
                    lightThemeBtn.classList.add('bg-gray-700');
                    lightThemeBtn.classList.add('text-gray-300');
                }
            } else {
                if (themeIcon) {
                    themeIcon.classList.remove('fa-sun');
                    themeIcon.classList.remove('text-amber-400');
                    themeIcon.classList.add('fa-moon');
                }
                htmlElement.classList.remove('dark');

                // Update button states
                if (darkThemeBtn && lightThemeBtn) {
                    darkThemeBtn.classList.remove('bg-blue-900');
                    darkThemeBtn.classList.remove('border-blue-700');
                    darkThemeBtn.classList.remove('text-white');
                    darkThemeBtn.classList.add('text-gray-700');
                    lightThemeBtn.classList.add('bg-blue-100');
                    lightThemeBtn.classList.remove('bg-gray-700');
                    lightThemeBtn.classList.remove('text-gray-300');
                }
            }
        }

        // Sidebar toggle functionality
        // ... existing code ...
    </script>
</body>

</html>
