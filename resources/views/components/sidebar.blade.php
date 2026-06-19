@auth
<aside class="hidden lg:flex flex-col fixed left-0 top-16 h-[calc(100vh-64px)] w-64 p-4 bg-surface-container border-r border-outline-variant z-40">
    <div class="flex items-center gap-3 mb-8 px-2 mt-4">
        <img src="{{ auth()->user()->avatar_path ? asset('storage/' . auth()->user()->avatar_path) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=8B5CF6&color=fff' }}" alt="User Avatar" class="w-10 h-10 rounded-full border border-[#262626] object-cover">
        <div>
            <h3 class="font-label-bold text-label-bold text-on-surface truncate max-w-[150px]">{{ auth()->user()->name }}</h3>
        </div>
    </div>
    
    <nav class="flex flex-col gap-2 flex-1">
        <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('home') ? 'text-primary bg-surface-container-high' : 'text-on-surface-variant hover:bg-surface-container-highest' }} font-label-bold text-label-bold transition-all rounded-lg">
            <span class="material-symbols-outlined">home</span> Home
        </a>
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('dashboard') ? 'text-primary bg-surface-container-high' : 'text-on-surface-variant hover:bg-surface-container-highest' }} font-label-bold text-label-bold transition-all rounded-lg">
            <span class="material-symbols-outlined">library_music</span> My Library
        </a>
        <a href="{{ route('profile.show', auth()->user()) }}" class="flex items-center gap-3 px-3 py-2 {{ request()->routeIs('profile.show') ? 'text-primary bg-surface-container-high' : 'text-on-surface-variant hover:bg-surface-container-highest' }} font-label-bold text-label-bold transition-all rounded-lg">
            <span class="material-symbols-outlined">person</span> Profile
        </a>
    </nav>
    
    <div class="mt-auto flex flex-col gap-4">
        <a href="{{ route('items.create') }}" class="w-full bg-[#8B5CF6] hover:bg-inverse-primary text-white font-label-bold text-label-bold py-3 rounded-full transition-colors flex justify-center items-center gap-2">
            <span class="material-symbols-outlined">upload</span> Upload Tone
        </a>
    </div>
</aside>
@endauth
