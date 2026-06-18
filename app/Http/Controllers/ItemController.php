<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Item;

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

    public function store(Request $request)
    {
        // To be implemented in Sprint 3
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        // To be implemented in Sprint 3
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('dashboard')->with('success', 'Item removido.');
    }

    public function download(Item $item)
    {
        // To be implemented in Sprint 3
    }
}
