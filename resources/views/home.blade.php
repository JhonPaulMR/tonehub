@extends('layouts.app')

@section('content')
<!-- Hero Section -->
@unless(request('search'))
<section class="mb-margin-desktop py-12 rounded-xl border border-[#262626] bg-[#141414] relative overflow-hidden flex flex-col items-center justify-center text-center px-4">
    <!-- Abstract Background Elements -->
    <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(circle at 50% 50%, #8B5CF6 0%, transparent 60%);"></div>
    <div class="relative z-10 max-w-2xl">
        <h1 class="text-headline-xl font-headline-xl text-on-surface mb-6">Discover Your Tone</h1>
        <p class="text-body-lg font-body-lg text-on-surface-variant mb-8 max-w-xl mx-auto">Explore thousands of premium captures, presets, and impulse responses created by professional audio engineers and guitarists worldwide.</p>
        <div class="flex justify-center gap-4">
            <a href="{{ route('home') }}" class="bg-[#8B5CF6] hover:bg-inverse-primary text-white font-label-bold text-label-bold px-6 py-3 rounded-full transition-colors">
                Browse Tones
            </a>
            @auth
            <a href="{{ route('items.create') }}" class="border border-outline-variant hover:border-primary text-on-surface hover:text-primary font-label-bold text-label-bold px-6 py-3 rounded-full transition-colors bg-transparent">
                Upload
            </a>
            @else
            <a href="{{ route('login') }}" class="border border-outline-variant hover:border-primary text-on-surface hover:text-primary font-label-bold text-label-bold px-6 py-3 rounded-full transition-colors bg-transparent">
                Upload
            </a>
            @endauth
        </div>
    </div>
</section>
@endunless

<!-- Search & Filters -->
<section class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <div class="relative w-full md:w-96">
            <form action="{{ route('home') }}" method="GET">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, amp, or creator..." class="w-full bg-[#0A0A0A] border border-[#262626] rounded-full py-3 pl-10 pr-4 text-body-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-on-surface placeholder:text-on-surface-variant transition-all">
            </form>
        </div>
        <div class="flex gap-2 overflow-x-auto pb-2 md:pb-0 w-full md:w-auto scrollbar-hide">
            <a href="{{ route('home') }}" class="whitespace-nowrap px-4 py-2 {{ !request('category') ? 'bg-primary text-on-primary' : 'bg-[#1A1A1A] text-on-surface-variant hover:text-on-surface hover:border-outline-variant' }} font-label-bold text-label-bold rounded-full border border-[#262626] transition-colors">All</a>
            
            @php
                $categories = ['preset' => 'Preset', 'capture' => 'Capture', 'ir' => 'IR'];
            @endphp
            
            @foreach($categories as $value => $label)
                <a href="{{ route('home', ['category' => $value, 'search' => request('search')]) }}" class="whitespace-nowrap px-4 py-2 {{ request('category') === $value ? 'bg-primary text-on-primary' : 'bg-[#1A1A1A] text-on-surface-variant hover:text-on-surface hover:border-outline-variant' }} font-label-bold text-label-bold rounded-full border border-[#262626] transition-colors">{{ $label }}</a>
            @endforeach
        </div>
    </div>
</section>

<!-- Grid Content -->
<section>
    <div class="flex justify-between items-end mb-6">
        <h2 class="text-headline-lg font-headline-lg text-on-surface">
            {{ request('search') ? 'Search Results' : 'Trending Tones' }}
        </h2>
    </div>

    @if($items->isEmpty())
        <div class="text-center py-12 border border-[#262626] border-dashed rounded-lg">
            <span class="material-symbols-outlined text-4xl text-on-surface-variant mb-2">music_off</span>
            <h3 class="text-headline-md text-on-surface mb-2">No tones found</h3>
            <p class="text-body-md text-on-surface-variant">Try adjusting your search or filters.</p>
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
