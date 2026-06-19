@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-8 bg-surface-container-low border border-outline-variant rounded-xl p-8 shadow-2xl relative">
    <div class="flex items-center justify-between mb-8 border-b border-outline-variant pb-4">
        <div>
            <h1 class="text-headline-lg font-headline-lg text-on-surface">Profile Settings</h1>
            <p class="text-body-md text-on-surface-variant">Update your account information and public profile.</p>
        </div>
        <a href="{{ route('profile.show', $user) }}" class="flex items-center gap-2 border border-outline-variant bg-surface-container px-4 py-2 rounded-full text-label-bold font-label-bold text-on-surface hover:border-primary hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[18px]">public</span>
            View Public
        </a>
    </div>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Avatar Upload -->
        <div class="flex items-center gap-6 mb-8">
            <div class="relative group">
                <img src="{{ $user->avatar_path ? asset('storage/' . $user->avatar_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=8B5CF6&color=fff' }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full border-4 border-surface-container-highest object-cover shadow-lg group-hover:opacity-50 transition-opacity">
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                    <span class="material-symbols-outlined text-white text-3xl drop-shadow-md">upload</span>
                </div>
                <input type="file" name="avatar" id="avatar" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
            </div>
            <div>
                <label class="block text-label-bold font-label-bold text-on-surface mb-1">Profile Picture</label>
                <p class="text-label-sm text-on-surface-variant mb-2">JPEG, PNG, or WEBP. Max 5MB.</p>
                @error('avatar')
                    <span class="text-error text-label-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Name Field -->
        <div class="space-y-2 relative">
            <label for="name" class="text-label-bold font-label-bold text-on-surface block">Display Name</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px] pointer-events-none">person</span>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full pl-10 pr-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all duration-200">
            </div>
            @error('name')
                <span class="text-error text-label-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Bio Field -->
        <div class="space-y-2">
            <label for="bio" class="text-label-bold font-label-bold text-on-surface block">Bio / Description</label>
            <textarea id="bio" name="bio" rows="4" placeholder="Tell the community about yourself, your gear, and your style..." class="w-full p-4 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all duration-200 resize-none">{{ old('bio', $user->bio) }}</textarea>
            @error('bio')
                <span class="text-error text-label-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="pt-4 border-t border-outline-variant flex justify-end">
            <button type="submit" class="bg-primary hover:bg-inverse-primary text-on-primary font-label-bold px-6 py-2.5 rounded-lg transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">save</span>
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
