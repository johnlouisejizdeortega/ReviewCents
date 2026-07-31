<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Project;
use App\Models\Resource;
use App\Models\Roadmap;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'roadmaps' => Roadmap::with('category')->withCount('steps')->latest()->take(4)->get(),
            'topResources' => Resource::with('category')->orderByDesc('avg_rating')->orderByDesc('reviews_count')->take(4)->get(),
            'challenges' => Challenge::with('category')->latest()->take(4)->get(),
            'projects' => Project::with('user')->latest()->take(3)->get(),
            'stats' => [
                'roadmaps' => Roadmap::count(),
                'resources' => Resource::count(),
                'challenges' => Challenge::count(),
            ],
        ]);
    }
}
