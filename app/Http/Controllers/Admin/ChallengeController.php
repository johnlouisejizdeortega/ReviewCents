<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Challenge;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ChallengeController extends Controller
{
    public function index(): View
    {
        return view('admin.challenges.index', [
            'challenges' => Challenge::with('category')->withCount('submissions')->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.challenges.form', [
            'challenge' => new Challenge(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['title']);
        Challenge::create($data);

        return redirect()->route('admin.challenges.index')->with('success', 'Challenge created.');
    }

    public function edit(Challenge $challenge): View
    {
        return view('admin.challenges.form', [
            'challenge' => $challenge,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Challenge $challenge): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['title']);
        $challenge->update($data);

        return redirect()->route('admin.challenges.index')->with('success', 'Challenge updated.');
    }

    public function destroy(Challenge $challenge): RedirectResponse
    {
        $challenge->delete();

        return back()->with('success', 'Challenge deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'type' => ['required', 'in:challenge,mission'],
            'points' => ['required', 'integer', 'min:0', 'max:1000'],
        ]);
    }
}
