<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>University Registration</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        @auth('admin')
                            <a href="{{ route('admin.import.index') }}" class="text-xl font-bold text-indigo-600">Admin Portal</a>
                        @else
                            <a href="{{ route('login') }}" class="text-xl font-bold text-indigo-600">Student Portal</a>
                        @endauth
                    </div>
                </div>
                <div class="flex items-center">

                    <!-- MODIFIED: Show student welcome/logout -->
                    @auth('student')
                        <span class="text-gray-700 mr-4">Welcome, {{ Auth::guard('student')->user()->full_name_arabic }}</span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">Logout</button>
                        </form>
                    @endauth

                    <!-- MODIFIED: Show admin welcome/logout -->
                    @auth('admin')
                        <span class="text-gray-700 mr-4">Admin: <span class="font-medium">{{ Auth::guard('admin')->user()->name }}</span></span>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">Logout</button>
                        </form>
                    @endauth

                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @yield('content')
    </main>
</body>
</html>
