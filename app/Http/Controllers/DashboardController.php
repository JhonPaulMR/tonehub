<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $items = Auth::user()->items()->latest()->paginate(10);
        return view('dashboard.index', compact('items'));
    }
}
