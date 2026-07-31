<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChallengeSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    public function index(): View
    {
        return view('admin.submissions.index', [
            'submissions' => ChallengeSubmission::with(['user', 'challenge'])
                ->orderByRaw("status = 'pending' desc")
                ->latest()
                ->get(),
        ]);
    }

    public function review(Request $request, ChallengeSubmission $submission): RedirectResponse
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'feedback' => ['required', 'string', 'max:2000'],
        ]);

        $submission->update([
            'rating' => $data['rating'],
            'feedback' => $data['feedback'],
            'status' => 'reviewed',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Submission rated and feedback sent.');
    }
}
