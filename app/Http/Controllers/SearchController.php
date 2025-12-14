<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Difficulty;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::published()
            ->with(['difficulty', 'tags', 'user']);

        if ($request->filled('q')) {
            $q = $request->q;

            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhereHas('contents', fn ($c) =>
                        $c->where('comment', 'like', "%{$q}%")
                    )
                    ->orWhereHas('tags', fn ($t) =>
                        $t->where('name', 'like', "%{$q}%")
                    );
            });
        }

        if ($request->filled('difficulty_id')) {
            $query->where('difficulty_id', $request->difficulty_id);
        }

        return view('search.index', [
            'posts' => $query->latest()->paginate(12),
            'difficulties' => Difficulty::all(),
        ]);
    }
}
