@props(['item'])

<div class="group bg-[#141414] border border-[#262626] rounded-lg overflow-hidden hover:border-primary/50 transition-colors duration-300 relative flex flex-col h-full">
    <div class="h-40 relative bg-[#0A0A0A] overflow-hidden shrink-0">
        @if($item->cover_image_path)
            <img src="{{ asset('storage/' . $item->cover_image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-500">
        @else
            <!-- Placeholder Gradient -->
            <div class="w-full h-full bg-gradient-to-br from-[#2a2a2a] to-[#141414] opacity-80 group-hover:scale-105 transition-transform duration-500 flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl text-on-surface-variant opacity-30">album</span>
            </div>
        @endif

        <!-- Overlay Play Button -->
        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <a href="{{ route('items.show', $item) }}" class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-on-primary transform scale-90 group-hover:scale-100 transition-all duration-300">
                <span class="material-symbols-outlined fill text-2xl">arrow_forward</span>
            </a>
        </div>

        <div class="absolute top-3 left-3">
            <span class="bg-[#1A1A1A]/90 backdrop-blur-sm text-on-surface font-label-bold text-label-bold px-2 py-1 rounded text-[10px] uppercase border border-[#262626]">{{ $item->category }}</span>
        </div>
    </div>

    <div class="p-4 flex flex-col flex-1 min-h-[140px]">
        <div class="flex justify-between items-start mb-2">
            <a href="{{ route('items.show', $item) }}" class="text-body-md font-body-md font-bold text-on-surface hover:text-primary transition-colors truncate pr-2 block">
                {{ $item->title }}
            </a>
        </div>
        
        <a href="{{ route('profile.show', $item->user) }}" class="text-label-sm font-label-sm text-on-surface-variant hover:text-primary mb-4 transition-colors">By {{ $item->user->name }}</a>
        
        <div class="mt-auto pt-4 flex items-center justify-between border-t border-[#262626]/50">
            <div class="flex flex-wrap gap-1">
                @foreach($item->tags->take(3) as $tag)
                    <x-tag-badge :tag="$tag" />
                @endforeach
                @if($item->tags->count() > 3)
                    <span class="text-[10px] text-on-surface-variant px-1">+{{ $item->tags->count() - 3 }}</span>
                @endif
            </div>
            <span class="text-label-sm font-label-sm text-on-surface-variant flex items-center gap-1 ml-2 shrink-0">
                <span class="material-symbols-outlined text-[14px]">download</span> {{ $item->downloads_count }}
            </span>
        </div>
    </div>
</div>
