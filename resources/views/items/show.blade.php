@extends('layouts.app')

@section('content')
<div class="max-w-max-width mx-auto">
    <!-- Header & Title -->
    <header class="mb-8 flex flex-col md:flex-row md:items-start justify-between gap-6 border-b border-surface-container-highest pb-6">
        <div class="flex-grow">
            <h1 class="text-headline-xl font-headline-xl text-on-surface mb-2 tracking-tight">{{ $item->title }}</h1>
            <div class="flex flex-wrap items-center gap-3 text-on-surface-variant">
                <a href="{{ route('profile.show', $item->user) }}" class="flex items-center gap-2 hover:text-primary transition-colors">
                    <img src="{{ $item->user->avatar_path ? asset('storage/' . $item->user->avatar_path) : 'https://ui-avatars.com/api/?name=' . urlencode($item->user->name) . '&background=8B5CF6&color=fff' }}" class="w-6 h-6 rounded-full border border-outline-variant object-cover">
                    <span class="text-label-bold font-label-bold">{{ $item->user->name }}</span>
                </a>
                <span class="text-surface-container-high hidden sm:inline">•</span>
                <span class="text-label-sm font-label-sm">{{ $item->created_at->format('M d, Y') }}</span>
                <span class="text-surface-container-high hidden sm:inline">•</span>
                <span class="text-label-sm font-label-sm uppercase tracking-wider px-2 py-0.5 rounded bg-[#1A1A1A] border border-[#262626] text-on-surface">{{ $item->category }}</span>
            </div>
        </div>
        <div class="flex gap-3 self-start">
            <a href="{{ route('items.download', $item) }}" class="flex items-center justify-center gap-2 px-8 py-3 rounded-lg bg-primary text-on-primary hover:bg-primary-fixed transition-colors font-label-bold text-label-bold shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-lg">download</span>
                Download
            </a>
            @can('update', $item)
                <a href="{{ route('items.edit', $item) }}" class="flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-surface-container-high text-on-surface hover:bg-surface-container-highest transition-colors border border-outline-variant font-label-bold text-label-bold">
                    <span class="material-symbols-outlined text-lg">edit</span>
                    Edit
                </a>
            @endcan
        </div>
    </header>

    <!-- Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left Column: Equipment Image & Metadata (5 cols) -->
        <div class="lg:col-span-5 flex flex-col gap-8">
            <!-- Main Equipment Image -->
            <div class="rounded-xl overflow-hidden bg-surface-container aspect-square relative group border border-[#262626]">
                @if($item->cover_image_path)
                    <img src="{{ asset('storage/' . $item->cover_image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-[#2a2a2a] to-[#141414] flex items-center justify-center transition-transform duration-700 group-hover:scale-105">
                        <span class="material-symbols-outlined text-6xl text-on-surface-variant opacity-30">graphic_eq</span>
                    </div>
                @endif
            </div>

            <!-- Metadata Section -->
            <div class="flex flex-col gap-6">
                @if($item->description)
                <div>
                    <h3 class="text-label-bold font-label-bold text-on-surface mb-2">Description</h3>
                    <p class="text-body-md font-body-md text-on-surface-variant leading-relaxed">
                        {{ $item->description }}
                    </p>
                </div>
                @endif

                @if($item->hardware_model)
                <div>
                    <h3 class="text-label-bold font-label-bold text-on-surface mb-2">Original Gear</h3>
                    <p class="text-body-md font-body-md text-on-surface-variant">{{ $item->hardware_model }}</p>
                </div>
                @endif

                @if($item->tags->isNotEmpty())
                <div>
                    <h3 class="text-label-bold font-label-bold text-on-surface mb-2">Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($item->tags as $tag)
                            <a href="{{ route('home', ['search' => $tag->name]) }}" class="px-4 py-1.5 rounded-full bg-surface-container-high text-on-surface border border-transparent hover:border-primary hover:text-primary text-label-sm font-label-bold transition-colors">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Audio & Details (7 cols) -->
        <div class="lg:col-span-7 flex flex-col gap-8">
            <x-audio-player :item="$item" />

            <!-- Stats Grid -->
            <div class="flex flex-col gap-4">
                <h3 class="text-headline-md font-headline-md text-on-surface mb-2">Stats</h3>
                
                <div class="grid grid-cols-2 gap-4 mt-2">
                    <div class="rounded-lg p-6 bg-surface-container border border-outline-variant flex flex-col items-center justify-center text-center">
                        <span class="material-symbols-outlined text-3xl text-primary mb-2">download</span>
                        <span class="text-headline-lg text-on-surface font-bold">{{ $item->downloads_count }}</span>
                        <span class="text-label-sm text-on-surface-variant uppercase tracking-wider mt-1">Downloads</span>
                    </div>
                    
                    <div class="rounded-lg p-6 bg-surface-container border border-outline-variant flex flex-col items-center justify-center text-center">
                        <span class="material-symbols-outlined text-3xl text-primary mb-2">audio_file</span>
                        <span class="text-headline-lg text-on-surface font-bold">{{ strtoupper($item->preset_file_path ? pathinfo($item->preset_file_path, PATHINFO_EXTENSION) : 'N/A') }}</span>
                        <span class="text-label-sm text-on-surface-variant uppercase tracking-wider mt-1">Format</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
