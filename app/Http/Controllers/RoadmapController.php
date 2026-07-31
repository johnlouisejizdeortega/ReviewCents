<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Roadmap;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoadmapController extends Controller
{
    public function index(Request $request): View
    {
        $query = Roadmap::with('category')->withCount('steps');

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        return view('roadmaps.index', [
            'roadmaps' => $query->latest()->paginate(9)->withQueryString(),
            'categories' => Category::orderBy('name')->get(),
            'filters' => $request->only(['category']),
        ]);
    }

    public function show(Roadmap $roadmap): View
    {
        $user = auth()->user();

        $roadmap->load([
            'category',
            'quiz.questions',
            'steps' => function ($q) use ($user) {
                $q->withCount('cards')->with('resource');
                if ($user) {
                    $q->with(['lessonProgress' => fn ($p) => $p->where('user_id', $user->id)]);
                }
            },
        ]);

        return view('roadmaps.show', [
            'roadmap' => $roadmap,
            'completion' => $roadmap->completionFor($user),
            'bestAttempt' => $roadmap->quiz?->bestAttemptFor($user),
        ]);
    }
}
