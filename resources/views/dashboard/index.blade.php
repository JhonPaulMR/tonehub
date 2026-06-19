@extends('layouts.app')

@section('content')
<div class="max-w-max-width mx-auto">
    <!-- Header -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-4">
        <div>
            <h1 class="text-headline-xl font-headline-xl text-on-surface tracking-tight mb-2">My Library</h1>
            <p class="text-body-lg font-body-lg text-on-surface-variant">Manage your uploaded tones, captures, and presets.</p>
        </div>
        <a href="{{ route('items.create') }}" class="bg-primary text-on-primary px-6 py-3 rounded font-label-bold text-label-bold hover:bg-primary-container transition-colors flex items-center gap-2 shrink-0">
            <span class="material-symbols-outlined">add</span>
            Upload New
        </a>
    </header>

    <!-- Data Table -->
    <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden shadow-lg">
        @if($items->isEmpty())
            <div class="text-center py-16 px-4">
                <span class="material-symbols-outlined text-5xl text-on-surface-variant mb-4 opacity-50">library_music</span>
                <h3 class="text-headline-md text-on-surface mb-2">Your library is empty</h3>
                <p class="text-body-md text-on-surface-variant max-w-md mx-auto mb-6">You haven't uploaded any items yet. Share your first tone with the community!</p>
                <a href="{{ route('items.create') }}" class="inline-flex items-center gap-2 bg-primary text-on-primary px-6 py-2.5 rounded-full font-label-bold text-label-bold hover:bg-primary-container transition-all">
                    Upload Tone
                </a>
            </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead>
                    <tr class="bg-surface-container border-b border-outline-variant">
                        <th class="p-4 text-label-bold font-label-bold text-on-surface-variant">Title</th>
                        <th class="p-4 text-label-bold font-label-bold text-on-surface-variant">Category</th>
                        <th class="p-4 text-label-bold font-label-bold text-on-surface-variant text-right">Downloads</th>
                        <th class="p-4 text-label-bold font-label-bold text-on-surface-variant text-center w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-body-md font-body-md divide-y divide-outline-variant">
                    @foreach($items as $item)
                    <tr class="hover:bg-surface-container-low transition-colors group">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0 overflow-hidden">
                                    @if($item->cover_image_path)
                                        <img src="{{ asset('storage/' . $item->cover_image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="material-symbols-outlined text-primary">graphic_eq</span>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('items.show', $item) }}" class="text-on-surface font-semibold group-hover:text-primary transition-colors block">
                                        {{ $item->title }}
                                    </a>
                                    <div class="text-label-sm font-label-sm text-on-surface-variant mt-1">Uploaded {{ $item->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-2 py-1 rounded bg-[#1A1A1A] border border-outline-variant text-label-sm font-label-sm text-on-surface uppercase tracking-wider">
                                {{ $item->category }}
                            </span>
                        </td>
                        <td class="p-4 text-right text-on-surface-variant">{{ $item->downloads_count }}</td>
                        <td class="p-4">
                            <div class="flex justify-center gap-3">
                                <a href="{{ route('items.edit', $item) }}" class="text-on-surface-variant hover:text-primary transition-colors p-1" title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </a>
                                
                                <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-on-surface-variant hover:text-error transition-colors p-1" title="Delete">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-surface-container px-4 py-3 border-t border-outline-variant sm:px-6">
            {{ $items->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
