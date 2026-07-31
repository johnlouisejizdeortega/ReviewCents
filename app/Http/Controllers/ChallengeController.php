<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Challenge;
use App\Models\ChallengeSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChallengeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Challenge::with('category')->withCount('submissions');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        return view('challenges.index', [
            'challenges' => $query->latest()->paginate(9)->withQueryString(),
            'categories' => Category::orderBy('name')->get(),
            'filters' => $request->only(['type', 'difficulty']),
        ]);
    }

    public function show(Challenge $challenge): View
    {
        $challenge->load('category');

        return view('challenges.show', [
            'challenge' => $challenge,
            'mySubmission' => $challenge->submissionFor(auth()->user()),
        ]);
    }

    public function submit(Request $request, Challenge $challenge): RedirectResponse
    {
        $data = $request->validate([
            'submission_url' => ['nullable', 'url', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        ChallengeSubmission::create([
            'user_id' => $request->user()->id,
            'challenge_id' => $challenge->id,
            'submission_url' => $data['submission_url'] ?? null,
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Submission received! An admin will review it soon.');
    }
}
