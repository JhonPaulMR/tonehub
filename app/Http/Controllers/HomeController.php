<?php

namespace App\Http\Controllers;

use App\Models\Item;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::with(['user', 'tags'])
            ->latest()
            ->paginate(12);
            
        return view('home', compact('items'));
    }
}
