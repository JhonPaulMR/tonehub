@props(['type' => 'success', 'message'])

@php
    $classes = $type === 'success' 
        ? 'bg-[#1A1A1A] border-primary text-primary' 
        : 'bg-error-container border-error text-on-error-container';
@endphp

<div {{ $attributes->merge(['class' => "border px-4 py-3 rounded mb-6 flex items-center justify-between $classes"]) }}>
    <span class="font-body-md">{{ $message }}</span>
    <button onclick="this.parentElement.style.display='none'" class="text-on-surface-variant hover:text-on-surface">
        <span class="material-symbols-outlined">close</span>
    </button>
</div>
