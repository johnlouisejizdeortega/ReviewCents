<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;

class ShowcaseController extends Controller
{
    public function index(): View
    {
        return view('showcase.index', [
            'projects' => Project::with('user')->latest()->paginate(12),
        ]);
    }

    public function show(Project $project): View
    {
        $project->load('user');

        return view('showcase.show', [
            'project' => $project,
        ]);
    }
}
