@props(['tag'])

<a href="{{ route('home', ['search' => $tag->name]) }}" class="text-[10px] bg-surface-container-high px-2 py-0.5 rounded text-on-surface-variant border border-outline-variant hover:border-primary hover:text-primary transition-colors inline-block whitespace-nowrap">
    {{ $tag->name }}
</a>
