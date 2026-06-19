@extends('layouts.app')

@section('content')
@push('styles')
    <style>
        .tagify {
            --tags-border-color: #262626;
            --tags-hover-border-color: #8B5CF6;
            --tags-focus-border-color: #8B5CF6;
            --tag-bg: #2a2a2a;
            --tag-hover: #3a3a3a;
            --tag-text-color: #e5e5e5;
            --tag-text-color--edit: #e5e5e5;
            --tag-pad: 0.3rem 0.5rem;
            --tag-inset-shadow-size: 1.1em;
            --tag-invalid-color: #ef4444;
            --tag-invalid-bg: rgba(239, 68, 68, 0.1);
            background: #0A0A0A;
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            transition: all 0.2s;
        }
        .tagify__input { color: #e5e5e5; }
    </style>
@endpush

<div class="flex items-center justify-center">
    <div class="w-full max-w-2xl bg-surface-container rounded-xl p-8 border border-[#262626] relative overflow-hidden focus-within:border-primary transition-colors">
        <!-- Subtle accent glow -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-3/4 h-1 bg-gradient-to-r from-transparent via-primary to-transparent opacity-50 blur-sm"></div>
        
        <header class="mb-8 text-center">
            <h1 class="text-headline-lg font-headline-lg text-primary mb-2 tracking-tight">Edit Tone</h1>
            <p class="text-body-md text-on-surface-variant">Update your item details and assets.</p>
        </header>

        <form action="{{ route('items.update', $item) }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ 
            coverPreview: '{{ $item->cover_image_path ? asset('storage/' . $item->cover_image_path) : '' }}', 
            toneFileName: '{{ $item->preset_file_path ? basename($item->preset_file_path) : null }}', 
            audioFileName: '{{ $item->wet_sample_path ? basename($item->wet_sample_path) : null }}'
        }">
            @csrf
            @method('PUT')
            
            <!-- General Info Section -->
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-form.input name="title" label="Title" :value="$item->title" placeholder="e.g., Plexi Crunch Lead" required="true" />
                    
                    <x-form.select name="category" label="Category" :value="$item->category" required="true" :options="[
                        'Preset' => 'Preset',
                        'Capture' => 'Capture',
                        'IR' => 'IR'
                    ]" />
                </div>

                <x-form.input name="hardware_model" label="Original Model (Optional)" :value="$item->hardware_model" placeholder="e.g., Marshall 1959 Super Lead" />

                <x-form.textarea name="description" label="Description" :value="$item->description" placeholder="Describe the tonal characteristics, gear used, and recommended usage..." rows="3" />
            </div>

            <!-- File Upload Zones -->
            <div class="space-y-4 pt-4 border-t border-outline-variant">
                <h2 class="text-label-bold font-label-bold text-primary mb-2">Assets</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Cover Image Upload -->
                    <div class="space-y-1">
                        <label class="text-label-bold font-label-bold text-on-surface">Cover Image</label>
                        <div class="relative w-full h-24 rounded-lg border-2 border-dashed border-outline-variant hover:border-primary bg-surface-container-low transition-colors duration-200 flex flex-col items-center justify-center cursor-pointer group overflow-hidden">
                            <input type="file" id="cover_image" name="cover_image" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer z-10" @change="coverPreview = $event.target.files.length ? URL.createObjectURL($event.target.files[0]) : null">
                            
                            <template x-if="coverPreview">
                                <img :src="coverPreview" class="absolute inset-0 w-full h-full object-cover z-0 group-hover:opacity-50 transition-opacity">
                            </template>
                            
                            <div class="flex flex-col items-center" x-show="!coverPreview">
                                <span class="material-symbols-outlined text-outline group-hover:text-primary mb-2 text-3xl">image</span>
                                <p class="text-label-sm font-label-sm text-on-surface-variant group-hover:text-on-surface">Upload Cover Image</p>
                            </div>

                            <div class="relative z-10 flex flex-col items-center opacity-0 group-hover:opacity-100 transition-opacity bg-background/80 p-4 rounded-lg backdrop-blur-sm" x-show="coverPreview">
                                <span class="material-symbols-outlined text-primary mb-1">image</span>
                                <p class="text-label-sm font-label-sm text-primary">Change Image</p>
                            </div>
                        </div>
                        @error('cover_image')
                            <span class="text-error text-label-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Tone File Upload -->
                    <div class="space-y-1">
                        <div class="relative w-full h-24 rounded-lg border-2 border-dashed border-outline-variant hover:border-primary bg-surface-container-low transition-colors duration-200 flex flex-col items-center justify-center cursor-pointer group">
                            <input type="file" id="preset_file" name="preset_file" class="absolute inset-0 opacity-0 cursor-pointer z-10" @change="toneFileName = $event.target.files.length ? $event.target.files[0].name : null">
                            <span class="material-symbols-outlined text-outline group-hover:text-primary mb-1">upload_file</span>
                            <p class="text-label-sm font-label-sm text-on-surface-variant group-hover:text-on-surface" x-text="toneFileName || 'Upload new Tone File (optional)'">Upload new Tone File (optional)</p>
                            <span class="text-[10px] text-on-surface-variant mt-1" x-show="!toneFileName">Leave empty to keep current file</span>
                        </div>
                        @error('preset_file')
                            <span class="text-error text-label-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Audio Sample Upload -->
                    <div class="space-y-1">
                        <div class="relative w-full h-24 rounded-lg border-2 border-dashed border-outline-variant hover:border-primary bg-surface-container-low transition-colors duration-200 flex flex-col items-center justify-center cursor-pointer group">
                            <input type="file" id="wet_sample" name="wet_sample" accept="audio/*" class="absolute inset-0 opacity-0 cursor-pointer z-10" @change="audioFileName = $event.target.files.length ? $event.target.files[0].name : null">
                            <span class="material-symbols-outlined text-outline group-hover:text-primary mb-1">audio_file</span>
                            <p class="text-label-sm font-label-sm text-on-surface-variant group-hover:text-on-surface" x-text="audioFileName || 'Upload new Audio Sample (optional)'">Upload new Audio Sample (optional)</p>
                            <span class="text-[10px] text-on-surface-variant mt-1" x-show="!audioFileName">Leave empty to keep current file</span>
                        </div>
                        @error('wet_sample')
                            <span class="text-error text-label-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Tags -->
            <div class="space-y-2 pt-4" x-data="tagInput([])">
                <label for="tags" class="text-label-bold font-label-bold text-on-surface block">Tags</label>
                <div class="flex flex-wrap gap-2 mb-2">
                    <span class="px-3 py-1 rounded-full border border-outline-variant text-label-sm font-label-sm text-on-surface-variant">Separate tags with commas</span>
                </div>
                <input type="text" id="tags" name="tags" value="{{ old('tags', $tagifyData) }}" x-ref="tagInput" placeholder="e.g. Rock, Metal, Clean, Ambient" class="w-full bg-[#0A0A0A] border border-[#262626] rounded-lg px-4 py-3 text-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all duration-200">
                @error('tags')
                    <span class="text-error text-label-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="pt-6 flex justify-end gap-4">
                <a href="{{ route('dashboard') }}" class="px-6 py-3 rounded-lg text-label-bold font-label-bold text-on-surface border border-outline-variant hover:bg-surface-container-highest transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-3 rounded-lg text-label-bold font-label-bold text-white bg-[#8B5CF6] hover:bg-inverse-primary transition-colors duration-200 shadow-lg shadow-primary/20">
                    Update Tone
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


