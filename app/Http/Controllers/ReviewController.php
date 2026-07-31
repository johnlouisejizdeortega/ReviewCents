<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Resource $resource): RedirectResponse
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'body' => ['nullable', 'string', 'max:2000'],
        ]);

        Review::updateOrCreate(
            ['user_id' => $request->user()->id, 'resource_id' => $resource->id],
            ['rating' => $data['rating'], 'body' => $data['body'] ?? null],
        );

        $resource->recalculateRating();

        return back()->with('success', 'Your review has been saved.');
    }

    public function update(Request $request, Review $review): RedirectResponse
    {
        abort_unless($review->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'body' => ['nullable', 'string', 'max:2000'],
        ]);

        $review->update($data);
        $review->resource->recalculateRating();

        return back()->with('success', 'Your review has been updated.');
    }

    public function destroy(Request $request, Review $review): RedirectResponse
    {
        abort_unless($review->user_id === $request->user()->id, 403);

        $resource = $review->resource;
        $review->delete();
        $resource->recalculateRating();

        return back()->with('success', 'Your review has been removed.');
    }
}
