<?php

namespace App\Http\Controllers;

use App\Models\Item;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::with(['user', 'tags'])
            ->when($request->input('category'), function ($query, $category) {
                // Ensure correct casing based on options
                $mappedCategory = match(strtolower($category)) {
                    'preset' => 'Preset',
                    'capture' => 'Capture',
                    'ir' => 'IR',
                    default => $category
                };
                $query->where('category', $mappedCategory);
            })
            ->search($request->input('search'))
            ->latest()
            ->paginate(12)
            ->withQueryString();
            
        return view('home', compact('items'));
    }
}
