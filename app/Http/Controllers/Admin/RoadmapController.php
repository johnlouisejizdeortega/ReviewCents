<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Resource;
use App\Models\Roadmap;
use App\Models\RoadmapStep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RoadmapController extends Controller
{
    public function index(): View
    {
        return view('admin.roadmaps.index', [
            'roadmaps' => Roadmap::with('category')->withCount('steps')->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.roadmaps.form', [
            'roadmap' => new Roadmap(),
            'categories' => Category::orderBy('name')->get(),
            'resources' => Resource::orderBy('title')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['title']);
        $roadmap = Roadmap::create($data);
        $this->syncSteps($roadmap, $request);

        return redirect()->route('admin.roadmaps.index')->with('success', 'Roadmap created. Add its test next.');
    }

    public function edit(Roadmap $roadmap): View
    {
        $roadmap->load('steps');

        return view('admin.roadmaps.form', [
            'roadmap' => $roadmap,
            'categories' => Category::orderBy('name')->get(),
            'resources' => Resource::orderBy('title')->get(),
        ]);
    }

    public function update(Request $request, Roadmap $roadmap): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['title']);
        $roadmap->update($data);
        $this->syncSteps($roadmap, $request);

        return redirect()->route('admin.roadmaps.index')->with('success', 'Roadmap updated.');
    }

    public function destroy(Roadmap $roadmap): RedirectResponse
    {
        $roadmap->delete();

        return back()->with('success', 'Roadmap deleted.');
    }

    private function syncSteps(Roadmap $roadmap, Request $request): void
    {
        $steps = $request->input('steps', []);
        $roadmap->steps()->delete();

        foreach (array_values($steps) as $i => $step) {
            if (empty($step['title'])) {
                continue;
            }
            RoadmapStep::create([
                'roadmap_id' => $roadmap->id,
                'title' => $step['title'],
                'description' => $step['description'] ?? null,
                'resource_id' => ! empty($step['resource_id']) ? $step['resource_id'] : null,
                'position' => $i + 1,
            ]);
        }
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'level' => ['required', 'in:beginner,intermediate,advanced'],
        ]);
    }
}
