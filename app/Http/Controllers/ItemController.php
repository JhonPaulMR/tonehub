<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Item;
use App\Models\Tag;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Jobs\ProcessAudioUpload;

class ItemController extends Controller
{
    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(StoreItemRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $data['cover_image_path'] = $request->file('cover_image')->store('covers', 'public');
        }

        if ($request->hasFile('preset_file')) {
            $file = $request->file('preset_file');
            $extension = $file->getClientOriginalExtension() ?: 'bin';
            $filename = \Illuminate\Support\Str::random(40) . '.' . $extension;
            $data['preset_file_path'] = $file->storeAs('presets', $filename, 'public');
        }

        $item = $request->user()->items()->create($data);

        $tempDryPath = null;
        $tempWetPath = null;

        if ($request->hasFile('dry_sample')) {
            $tempDryPath = $request->file('dry_sample')->store('temp', 'local');
        }
        if ($request->hasFile('wet_sample')) {
            $tempWetPath = $request->file('wet_sample')->store('temp', 'local');
        }

        if ($tempDryPath || $tempWetPath) {
            ProcessAudioUpload::dispatch($item, $tempDryPath, $tempWetPath);
        }

        $this->syncTags($item, $request->tags);

        return redirect()->route('dashboard')->with('success', 'Item uploaded successfully! Audio files are being processed in the background.');
    }

    public function edit(Item $item)
    {
        Gate::authorize('update', $item);
        
        // Prepare tags for Tagify
        $tagifyData = $item->tags->map(function ($tag) {
            return ['value' => $tag->name];
        })->toJson();
        
        return view('items.edit', compact('item', 'tagifyData'));
    }

    public function update(UpdateItemRequest $request, Item $item)
    {
        Gate::authorize('update', $item);
        
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            if ($item->cover_image_path) Storage::disk('public')->delete($item->cover_image_path);
            $data['cover_image_path'] = $request->file('cover_image')->store('covers', 'public');
        }

        if ($request->hasFile('preset_file')) {
            if ($item->preset_file_path) Storage::disk('public')->delete($item->preset_file_path);
            $file = $request->file('preset_file');
            $extension = $file->getClientOriginalExtension() ?: 'bin';
            $filename = \Illuminate\Support\Str::random(40) . '.' . $extension;
            $data['preset_file_path'] = $file->storeAs('presets', $filename, 'public');
        }

        $tempDryPath = null;
        $tempWetPath = null;
        
        if ($request->hasFile('dry_sample')) {
            if ($item->dry_sample_path) Storage::disk('public')->delete($item->dry_sample_path);
            $tempDryPath = $request->file('dry_sample')->store('temp', 'local');
        }
        if ($request->hasFile('wet_sample')) {
            if ($item->wet_sample_path) Storage::disk('public')->delete($item->wet_sample_path);
            $tempWetPath = $request->file('wet_sample')->store('temp', 'local');
        }

        if ($tempDryPath || $tempWetPath) {
            ProcessAudioUpload::dispatch($item, $tempDryPath, $tempWetPath);
        }

        $item->update($data);
        $this->syncTags($item, $request->tags);

        $msg = 'Item updated successfully!';
        if ($tempDryPath || $tempWetPath) {
            $msg .= ' New audio files are being processed in the background.';
        }

        return redirect()->route('dashboard')->with('success', $msg);
    }

    public function destroy(Item $item)
    {
        Gate::authorize('delete', $item);
        
        if ($item->cover_image_path) Storage::disk('public')->delete($item->cover_image_path);
        if ($item->preset_file_path) Storage::disk('public')->delete($item->preset_file_path);
        if ($item->dry_sample_path) Storage::disk('public')->delete($item->dry_sample_path);
        if ($item->wet_sample_path) Storage::disk('public')->delete($item->wet_sample_path);

        $item->delete();
        return redirect()->route('dashboard')->with('success', 'Item removed.');
    }

    public function download(Item $item)
    {
        $item->increment('downloads_count');
        return Storage::disk('public')->download($item->preset_file_path);
    }

    private function syncTags(Item $item, ?string $tagsString)
    {
        if (!$tagsString) {
            $item->tags()->detach();
            return;
        }

        $tagNames = [];
        
        // Check if string is JSON from Tagify
        $decoded = json_decode($tagsString, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            foreach ($decoded as $tagItem) {
                if (isset($tagItem['value'])) {
                    $tagNames[] = trim($tagItem['value']);
                }
            }
        } else {
            // Fallback for simple comma-separated string
            $tagNames = array_filter(array_map('trim', explode(',', $tagsString)));
        }

        $tagIds = [];

        foreach ($tagNames as $name) {
            $tag = Tag::firstOrCreate(['name' => strtolower($name)]);
            $tagIds[] = $tag->id;
        }

        $item->tags()->sync($tagIds);
    }
}
