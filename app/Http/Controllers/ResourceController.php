<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResourceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Resource::with('category')->withCount('reviews');

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('q')) {
            // Case-insensitive & portable (Postgres LIKE is case-sensitive; SQLite is not).
            $query->whereRaw('LOWER(title) LIKE ?', ['%'.strtolower($request->q).'%']);
        }

        $sort = $request->get('sort', 'top');
        $query->when($sort === 'top', fn ($q) => $q->orderByDesc('avg_rating'))
            ->when($sort === 'new', fn ($q) => $q->latest());

        return view('resources.index', [
            'resources' => $query->paginate(9)->withQueryString(),
            'categories' => Category::orderBy('name')->get(),
            'filters' => $request->only(['category', 'type', 'q', 'sort']),
        ]);
    }

    public function show(Resource $resource): View
    {
        $resource->load(['category', 'submitter', 'reviews.user']);

        $userReview = auth()->check()
            ? $resource->reviews->firstWhere('user_id', auth()->id())
            : null;

        return view('resources.show', [
            'resource' => $resource,
            'userReview' => $userReview,
        ]);
    }
}
