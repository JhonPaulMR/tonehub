<header class="fixed top-0 left-0 w-full z-50 flex justify-between items-center px-gutter h-16 bg-background border-b border-outline-variant">
    <div class="flex items-center gap-8">
        <a href="{{ route('home') }}" class="text-headline-md font-headline-md font-bold text-primary tracking-tight">ToneHUB</a>
        <nav class="hidden md:flex gap-6">
            <a href="{{ route('home') }}" class="text-primary border-b-2 border-primary pb-5 mt-5 font-label-bold text-label-bold transition-all duration-200">Explore</a>
        </nav>
    </div>
    <div class="flex items-center gap-6">
        <div class="relative hidden sm:block w-64">
            <form action="{{ route('home') }}" method="GET">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tones, amps, cabs..." class="w-full bg-[#0A0A0A] border border-[#262626] rounded-full py-2 pl-10 pr-4 text-body-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-on-surface placeholder:text-on-surface-variant transition-all">
            </form>
        </div>
        <div class="flex items-center gap-4">
            @auth
                <a href="{{ route('items.create') }}" class="text-on-surface-variant hover:text-primary transition-colors duration-200" title="Upload">
                    <span class="material-symbols-outlined">upload</span>
                </a>
                <a href="{{ route('profile.edit') }}">
                    <img src="{{ auth()->user()->avatar_path ? asset('storage/' . auth()->user()->avatar_path) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=8B5CF6&color=fff' }}" alt="User profile" class="w-8 h-8 rounded-full border border-[#262626] object-cover">
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-on-surface-variant hover:text-error transition-colors duration-200" title="Logout">
                        <span class="material-symbols-outlined">logout</span>
                    </button>
                </form>
            @endauth
            @guest
                <a href="{{ route('login') }}" class="text-on-surface-variant hover:text-primary font-label-bold">Login</a>
                <a href="{{ route('register') }}" class="bg-primary text-on-primary px-4 py-2 rounded-lg font-label-bold hover:bg-inverse-primary transition-colors">Register</a>
            @endguest
        </div>
    </div>
</header>
