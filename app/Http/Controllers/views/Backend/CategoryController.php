<?php

namespace App\Http\Controllers\Views\Backend;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index (Request $request)
    {
        $categories = Category::orderBy('en')->get();
        return view('backend.categories.index', compact('categories'));
    }
}
