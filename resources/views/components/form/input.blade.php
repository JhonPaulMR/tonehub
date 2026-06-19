@props(['name', 'label', 'type' => 'text', 'placeholder' => '', 'value' => '', 'required' => false])

<div class="flex flex-col gap-2">
    <label for="{{ $name }}" class="text-label-md font-label-md text-on-surface flex items-center gap-1">
        {{ $label }}
        @if($required)
            <span class="text-error" title="Required">*</span>
        @endif
    </label>
    <input type="{{ $type }}" 
           id="{{ $name }}" 
           name="{{ $name }}" 
           value="{{ old($name, $value) }}"
           placeholder="{{ $placeholder }}"
           @if($required) required @endif
           class="w-full bg-surface-container border @error($name) border-error focus:ring-error @else border-outline-variant focus:border-primary focus:ring-primary @enderror text-on-surface rounded-lg px-4 py-3 focus:outline-none focus:ring-1 transition-colors" />
    @error($name)
        <p class="text-error text-label-sm">{{ $message }}</p>
    @enderror
</div>
