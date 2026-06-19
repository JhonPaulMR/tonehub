<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToneHUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body class="bg-background text-on-background font-body-md min-h-screen flex flex-col">
    <!-- TopNavBar -->
    <x-navbar />

    <!-- Main Content Layout -->
    <div class="flex flex-1 pt-16">
        <!-- SideNavBar -->
        <x-sidebar />

        <!-- Main Canvas -->
        <main class="flex-1 {{ auth()->check() ? 'lg:ml-64' : '' }} p-gutter pb-24 w-full">
            @if(session('success'))
                <x-flash-message type="success" :message="session('success')" />
            @endif

            @if(session('error'))
                <x-flash-message type="error" :message="session('error')" />
            @endif

            @if($errors->any())
                <x-flash-message type="error" message="Please fix the errors in the form below." />
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <x-footer />
    
    @stack('scripts')
</body>
</html>
