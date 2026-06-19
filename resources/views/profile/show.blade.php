@extends('layouts.app')

@section('content')
<!-- Profile Header -->
<section class="mb-12 py-12 rounded-xl border border-[#262626] bg-[#141414] relative overflow-hidden flex flex-col items-center justify-center text-center px-4">
    <!-- Abstract Background Elements -->
    <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(circle at 50% 50%, #8B5CF6 0%, transparent 60%);"></div>
    <div class="relative z-10 flex flex-col items-center max-w-2xl w-full">
        <div class="relative group {{ auth()->id() === $user->id && !request()->has('public') ? 'cursor-pointer' : '' }} mb-4">
            <img src="{{ $user->avatar_path ? asset('storage/' . $user->avatar_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=8B5CF6&color=fff' }}" alt="{{ $user->name }}" class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-[#262626] object-cover transition-opacity {{ auth()->id() === $user->id && !request()->has('public') ? 'group-hover:opacity-80' : '' }}">
            @if(auth()->id() === $user->id && !request()->has('public'))
                <form action="{{ route('profile.updateAvatar') }}" method="POST" enctype="multipart/form-data" class="absolute bottom-0 right-0" id="avatarForm">
                    @csrf
                    @method('PUT')
                    <label for="avatar_upload" class="bg-surface-container border border-[#262626] rounded-full p-2 cursor-pointer hover:border-primary hover:text-primary transition-colors flex items-center justify-center shadow-lg">
                        <span class="material-symbols-outlined text-[20px]">upload</span>
                    </label>
                    <input type="file" id="avatar_upload" name="avatar" class="hidden" accept="image/*" onchange="document.getElementById('avatarForm').submit()">
                </form>
            @endif
        </div>
        
        <h1 class="text-headline-xl font-headline-xl text-on-surface mb-2">{{ $user->name }}</h1>
        
        @if(auth()->id() === $user->id && !request()->has('public'))
            <div class="flex items-center gap-3 mb-6">
                <a href="{{ route('profile.show', ['user' => $user, 'public' => 1]) }}" class="border border-outline-variant hover:border-primary text-on-surface hover:text-primary px-4 py-2 rounded-full font-label-bold text-label-bold transition-colors flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-[18px]">public</span> View Public Profile
                </a>
                <a href="{{ route('profile.edit') }}" class="border border-outline-variant hover:border-primary text-on-surface hover:text-primary p-2 rounded-full transition-colors flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">edit</span>
                </a>
            </div>
        @else
            <p class="text-label-bold font-label-bold text-primary mb-6">Joined {{ $user->created_at->format('F Y') }}</p>
        @endif
        
        @if($user->bio)
            <p class="text-body-lg font-body-lg text-on-surface-variant max-w-xl mx-auto mb-8">{{ $user->bio }}</p>
        @else
            <p class="text-body-lg font-body-lg text-on-surface-variant max-w-xl mx-auto mb-8 italic opacity-50">This user hasn't written a bio yet.</p>
        @endif

        <div class="flex gap-8 text-center">
            <div>
                <span class="block text-headline-md font-bold text-on-surface">{{ $user->items()->count() }}</span>
                <span class="text-label-sm text-on-surface-variant uppercase tracking-wider">Uploads</span>
            </div>
            <div>
                <span class="block text-headline-md font-bold text-on-surface">{{ $user->items()->sum('downloads_count') }}</span>
                <span class="text-label-sm text-on-surface-variant uppercase tracking-wider">Downloads</span>
            </div>
        </div>
    </div>
</section>

<!-- User's Items Grid -->
<section>
    <div class="flex justify-between items-end mb-6">
        <h2 class="text-headline-lg font-headline-lg text-on-surface">Uploads by {{ $user->name }}</h2>
    </div>

    @if($items->isEmpty())
        <div class="text-center py-12 border border-[#262626] border-dashed rounded-lg">
            <span class="material-symbols-outlined text-4xl text-on-surface-variant mb-2">album</span>
            <h3 class="text-headline-md text-on-surface mb-2">No uploads yet</h3>
            <p class="text-body-md text-on-surface-variant">{{ $user->name }} hasn't shared any tones yet.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($items as $item)
                <x-item-card :item="$item" />
            @endforeach
        </div>

        <div class="mt-8">
            {{ $items->links() }}
        </div>
    @endif
</section>
@endsection
