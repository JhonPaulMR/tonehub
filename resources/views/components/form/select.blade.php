@props(['name', 'label', 'options' => [], 'value' => '', 'required' => false])

<div class="flex flex-col gap-2">
    <label for="{{ $name }}" class="text-label-md font-label-md text-on-surface flex items-center gap-1">
        {{ $label }}
        @if($required)
            <span class="text-error" title="Required">*</span>
        @endif
    </label>
    <div class="relative">
        <select id="{{ $name }}" 
                name="{{ $name }}" 
                @if($required) required @endif
                class="w-full bg-surface-container border @error($name) border-error focus:ring-error @else border-outline-variant focus:border-primary focus:ring-primary @enderror text-on-surface rounded-lg px-4 py-3 appearance-none focus:outline-none focus:ring-1 transition-colors">
            <option value="" disabled {{ old($name, $value) == '' ? 'selected' : '' }}>Select an option</option>
            @foreach($options as $val => $text)
                <option value="{{ $val }}" {{ old($name, $value) == $val ? 'selected' : '' }}>{{ $text }}</option>
            @endforeach
        </select>
        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">
            expand_more
        </span>
    </div>
    @error($name)
        <p class="text-error text-label-sm">{{ $message }}</p>
    @enderror
</div>
