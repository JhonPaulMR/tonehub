@props(['name', 'label', 'placeholder' => '', 'value' => '', 'required' => false, 'rows' => 4])

<div class="flex flex-col gap-2">
    <label for="{{ $name }}" class="text-label-md font-label-md text-on-surface flex items-center gap-1">
        {{ $label }}
        @if($required)
            <span class="text-error" title="Required">*</span>
        @endif
    </label>
    <textarea id="{{ $name }}" 
              name="{{ $name }}" 
              rows="{{ $rows }}"
              placeholder="{{ $placeholder }}"
              @if($required) required @endif
              class="w-full bg-surface-container border @error($name) border-error focus:ring-error @else border-outline-variant focus:border-primary focus:ring-primary @enderror text-on-surface rounded-lg px-4 py-3 focus:outline-none focus:ring-1 transition-colors">{{ old($name, $value) }}</textarea>
    @error($name)
        <p class="text-error text-label-sm">{{ $message }}</p>
    @enderror
</div>
