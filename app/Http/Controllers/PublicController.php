<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Vendor;
use App\Models\Billboard;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    function home()
    {
        $categories = Category::all()->toArray();

        return view('public.home', [
            "categories" => $categories,
        ]);
    }
    
    function search(Request $request)
    {
        $categories = Category::all()->toArray();
        $dbQuery = Billboard::query();

        // 🔍 Search by title
        if ($request->filled('query')) {
            $query = $request->input('query');
            $dbQuery->where('title', 'like', '%' . $query . '%');
        }

        // 📂 Filter by category
        if ($request->filled('category')) {
            $category_id = $request->input('category');
            $dbQuery->where('category_id', $category_id);
        }

        // 📍 ✅ ADD THIS (Location filter FIX)
        if ($request->filled('location')) {
            $location = $request->input('location');
            $dbQuery->where('location', $location);
        }

        $gigs = $dbQuery->get();

        return view('public.search', [
            'gigs' => $gigs,
            'categories' => $categories,
            'request' => $request,
        ]);
    }

    function team()
    {
        $categories = Category::all()->toArray();
        $vendorCount = Vendor::count();

        return view("public.team", [
            "categories" => $categories,
            "vendorCount" => $vendorCount,
        ]);
    }
}