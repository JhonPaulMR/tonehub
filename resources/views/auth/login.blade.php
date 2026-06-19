<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ToneHUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Autofill override for dark theme to prevent white background flash */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px theme('colors.surface-container-lowest') inset !important;
            -webkit-text-fill-color: theme('colors.on-surface') !important;
        }
    </style>
</head>
<body class="bg-background min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Subtle ambient background glow -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-primary-container/5 rounded-full blur-3xl pointer-events-none"></div>
    
    <!-- Login Container -->
    <main class="w-full max-w-[420px] relative z-10">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <h1 class="text-headline-xl font-headline-xl text-primary tracking-tight mb-2">ToneHUB</h1>
            <p class="text-body-md font-body-md text-on-surface-variant">Sign in to your studio ecosystem.</p>
        </div>

        @if($errors->any())
            <div class="bg-error-container border border-error text-on-error-container px-4 py-3 rounded-lg mb-6 shadow-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li class="font-body-md">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Central Card -->
        <div class="bg-surface-container-low border border-outline-variant rounded-xl p-8 shadow-2xl backdrop-blur-sm relative group transition-all duration-300">
            <!-- Hover highlight effect -->
            <div class="absolute inset-0 rounded-xl border border-primary/0 group-hover:border-primary/20 transition-colors duration-500 pointer-events-none"></div>
            
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <!-- Email Field -->
                <div class="space-y-2 relative">
                    <label for="email" class="text-label-bold font-label-bold text-on-surface block">Email Address</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px] pointer-events-none">mail</span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="user@studio.com" class="w-full pl-10 pr-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-md font-body-md text-on-surface placeholder:text-on-surface-variant/50 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all duration-200 shadow-sm">
                    </div>
                </div>

                <!-- Password Field -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="password" class="text-label-bold font-label-bold text-on-surface block">Password</label>
                    </div>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px] pointer-events-none">lock</span>
                        <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="••••••••" class="w-full pl-10 pr-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-md font-body-md text-on-surface placeholder:text-on-surface-variant/50 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all duration-200 shadow-sm">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 bg-surface-container-lowest border border-outline-variant rounded text-primary focus:ring-primary focus:ring-offset-background">
                    <label for="remember" class="ml-2 text-label-bold text-on-surface-variant">Remember me</label>
                </div>

                <!-- Action Button -->
                <button type="submit" class="w-full py-3 px-4 bg-inverse-primary hover:bg-inverse-primary/90 text-[#ffffff] text-label-bold font-label-bold rounded-lg transition-all duration-200 flex items-center justify-center gap-2 mt-2 shadow-[0_4px_14px_0_rgba(109,59,215,0.2)] hover:shadow-[0_6px_20px_rgba(109,59,215,0.3)] hover:-translate-y-[1px]">
                    <span>Login</span>
                    <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                </button>
            </form>
        </div>

        <!-- Footer Links -->
        <div class="mt-8 text-center">
            <p class="text-body-md font-body-md text-on-surface-variant">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-primary hover:text-inverse-primary font-semibold transition-colors">Create Account</a>
            </p>
        </div>
    </main>
</body>
</html>
