<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResourceController extends Controller
{
    public function index(): View
    {
        return view('admin.resources.index', [
            'resources' => Resource::with('category')->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.resources.form', [
            'resource' => new Resource(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['title']);
        $data['submitted_by'] = $request->user()->id;
        Resource::create($data);

        return redirect()->route('admin.resources.index')->with('success', 'Resource created.');
    }

    public function edit(Resource $resource): View
    {
        return view('admin.resources.form', [
            'resource' => $resource,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Resource $resource): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['title']);
        $resource->update($data);

        return redirect()->route('admin.resources.index')->with('success', 'Resource updated.');
    }

    public function destroy(Resource $resource): RedirectResponse
    {
        $resource->delete();

        return back()->with('success', 'Resource deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'url' => ['nullable', 'url', 'max:255'],
            'type' => ['required', 'in:course,tutorial,tool,book,bootcamp'],
            'thumbnail' => ['nullable', 'url', 'max:255'],
        ]);
    }
}
