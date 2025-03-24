<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light" id="documentTheme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - {{ config('app.name', 'Super Suket') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .theme-toggle {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 50;
        }

        .dark-mode-bg {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }

        .light-mode-bg {
            background: linear-gradient(135deg, #dbeafe 0%, #f5f3ff 50%, #eff6ff 100%);
        }

        .login-card {
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body class="font-sans antialiased">
    <!-- Theme Toggle Button -->
    <div class="theme-toggle">
        <button id="themeToggle" class="btn btn-circle btn-sm">
            <i id="themeIcon" class="fas fa-moon"></i>
        </button>
    </div>

    <div id="bgContainer"
        class="min-h-screen light-mode-bg flex flex-col items-center justify-center p-4 transition-all duration-500">
        <div class="text-center mb-8 fade-in">
            <div class="flex justify-center mb-4">
                <div class="w-24 h-24 rounded-full bg-primary flex items-center justify-center shadow-lg login-logo">
                    <i class="fas fa-envelope-open-text text-white text-4xl"></i>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Super Suket</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-2">Surat Pernyataan dan Surat Keterangan</p>
        </div>

        <div
            class="card-enhanced login-card w-full max-w-md bg-white/90 dark:bg-gray-800/90 fade-in backdrop-blur-lg border border-gray-100 dark:border-gray-700 shadow-xl">
            <div class="card-body p-8">
                <div class="flex justify-center mb-6">
                    <div
                        class="w-16 h-16 rounded-full bg-primary/20 dark:bg-primary/10 flex items-center justify-center">
                        <i class="fas fa-user-lock text-primary text-2xl"></i>
                    </div>
                </div>

                <h2 class="text-2xl font-semibold text-center mb-6 text-gray-800 dark:text-white">Login</h2>

                @if ($errors->any())
                    <div class="alert alert-error mb-4 rounded-xl">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error mb-4 rounded-xl">
                        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium dark:text-gray-200">Email</span>
                        </label>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" name="email"
                                class="input input-bordered input-enhanced pl-10 w-full dark:bg-gray-700 dark:text-white dark:border-gray-600"
                                value="{{ old('email') }}" required autofocus />
                        </div>
                    </div>

                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text font-medium dark:text-gray-200">Password</span>
                        </label>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" name="password"
                                class="input input-bordered input-enhanced pl-10 w-full dark:bg-gray-700 dark:text-white dark:border-gray-600"
                                required />
                        </div>
                    </div>

                    <div class="form-control mt-6">
                        <button type="submit" class="btn btn-primary w-full">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-8 text-center text-gray-600 dark:text-gray-300 text-sm fade-in">
            &copy; {{ date('Y') }} Super Suket. All rights reserved.
        </div>
    </div>

    <script>
        // Theme toggle functionality
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const documentTheme = document.getElementById('documentTheme');
        const bgContainer = document.getElementById('bgContainer');
        const htmlElement = document.documentElement;

        // Check for saved theme preference or use device preference
        const savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ?
            'dark' : 'light');

        // Apply the saved theme on page load
        setTheme(savedTheme);

        // Toggle theme when button is clicked
        themeToggle.addEventListener('click', () => {
            const currentTheme = documentTheme.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            setTheme(newTheme);
            localStorage.setItem('theme', newTheme);
        });

        function setTheme(theme) {
            documentTheme.setAttribute('data-theme', theme);

            if (theme === 'dark') {
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
                bgContainer.classList.remove('light-mode-bg');
                bgContainer.classList.add('dark-mode-bg');
                htmlElement.classList.add('dark');
            } else {
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
                bgContainer.classList.remove('dark-mode-bg');
                bgContainer.classList.add('light-mode-bg');
                htmlElement.classList.remove('dark');
            }
        }
    </script>
</body>

</html>
