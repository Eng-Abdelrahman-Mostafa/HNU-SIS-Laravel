<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>University Registration</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --ink: #0f172a;
            --sand: #f8f3ea;
            --deep: #0b2f2b;
            --accent: #c58b2a;
        }

        body {
            font-family: 'Cairo', 'Tajawal', 'Georgia', serif;
            color: var(--ink);
        }
    </style>
</head>
<body class="min-h-screen bg-slate-950">
    @yield('content')
</body>
</html>
