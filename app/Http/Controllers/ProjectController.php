<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        return view('projects.index', [
            'projects' => $request->user()->projects()->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('projects.create', ['project' => new Project()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['user_id'] = $request->user()->id;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('projects');
        }

        Project::create($data);

        return redirect()->route('projects.index')->with('success', 'Project added to your showcase.');
    }

    public function edit(Request $request, Project $project): View
    {
        $this->authorizeOwner($request, $project);

        return view('projects.edit', ['project' => $project]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $this->authorizeOwner($request, $project);

        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            if ($project->image_path) {
                Storage::delete($project->image_path);
            }
            $data['image_path'] = $request->file('image')->store('projects');
        }

        $project->update($data);

        return redirect()->route('projects.index')->with('success', 'Project updated.');
    }

    public function destroy(Request $request, Project $project): RedirectResponse
    {
        $this->authorizeOwner($request, $project);

        if ($project->image_path) {
            Storage::delete($project->image_path);
        }
        $project->delete();

        return back()->with('success', 'Project removed.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'live_url' => ['nullable', 'url', 'max:255'],
            'repo_url' => ['nullable', 'url', 'max:255'],
            'tags' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);
    }

    private function authorizeOwner(Request $request, Project $project): void
    {
        abort_unless($project->user_id === $request->user()->id, 403);
    }
}
