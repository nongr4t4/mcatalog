<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $isAdminPreview = $request->boolean('preview') && $request->user()?->isAdmin();
        if ($product->is_archived && !$isAdminPreview) {
            abort(404);
        }

        $validated = $request->validate([
            'stars' => ['required', 'integer', 'min:0', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $review = Review::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'product_id' => $product->id,
            ],
            [
                'stars' => $validated['stars'],
                'comment' => $validated['comment'] ?? null,
            ]
        );

        $product->loadAvg('reviews', 'stars')->loadCount('reviews');

        $response = [
            'message' => 'Відгук збережено',
            'average_rating' => round((float) ($product->reviews_avg_stars ?? 0), 1),
            'reviews_count' => (int) ($product->reviews_count ?? 0),
            'review' => [
                'id' => $review->id,
                'stars' => $review->stars,
                'comment' => $review->comment,
            ],
        ];

        if ($request->expectsJson()) {
            return response()->json($response);
        }

        return back()->with('success', 'Відгук збережено')->withFragment('reviews');
    }

    public function update(Request $request, Product $product, Review $review)
    {
        if ($review->user_id !== $request->user()->id || $review->product_id !== $product->id) {
            abort(403);
        }

        $validated = $request->validate([
            'stars' => ['required', 'integer', 'min:0', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $review->update($validated);
        $product->loadAvg('reviews', 'stars')->loadCount('reviews');

        $response = [
            'message' => 'Відгук оновлено',
            'average_rating' => round((float) ($product->reviews_avg_stars ?? 0), 1),
            'reviews_count' => (int) ($product->reviews_count ?? 0),
            'review' => [
                'id' => $review->id,
                'stars' => $review->stars,
                'comment' => $review->comment,
                'updated_at' => $review->updated_at,
            ],
        ];

        if ($request->expectsJson()) {
            return response()->json($response);
        }

        return back()->with('success', 'Відгук оновлено')->withFragment('reviews');
    }

    public function destroy(Request $request, Product $product, Review $review)
    {
        if ($review->user_id !== $request->user()->id || $review->product_id !== $product->id) {
            abort(403);
        }

        $review->delete();
        $product->loadAvg('reviews', 'stars')->loadCount('reviews');

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Відгук видалено',
                'average_rating' => round((float) ($product->reviews_avg_stars ?? 0), 1),
                'reviews_count' => (int) ($product->reviews_count ?? 0),
            ]);
        }

        return back()->with('success', 'Відгук видалено')->withFragment('reviews');
    }
}
