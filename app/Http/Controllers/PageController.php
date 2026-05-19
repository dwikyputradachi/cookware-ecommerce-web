<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(Request $request, $slug)
    {
        $page = Page::where('slug', $slug)
                    ->where('is_active', true)
                    ->first();

        if (! $page) {
            abort(404);
        }

        return view('page', compact('page'));
    }
}
