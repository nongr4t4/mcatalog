<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('categories', 'photos')
            ->withAvg('reviews', 'stars')
            ->withCount('reviews')
            ->active();

        // Фільтрація по категоріям (id)
        if ($request->filled('category')) {
            $categoryId = (int) $request->input('category');
            if ($categoryId > 0) {
                $query->whereHas('categories', function ($q) use ($categoryId) {
                    $q->where('categories.id', $categoryId);
                });
            }
        }

        // Пошук по назві та опису
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // Фільтрація за мінімальною ціною
        if ($request->has('price_from') && $request->price_from !== null && $request->price_from !== '') {
            $query->where('price', '>=', (float) $request->price_from);
        }

        // Фільтрація за максимальною ціною
        if ($request->has('price_to') && $request->price_to !== null && $request->price_to !== '') {
            $query->where('price', '<=', (float) $request->price_to);
        }

        // Фільтрація за рейтингом (0-5)
        // Використовуємо підзапит, щоб фільтр працював незалежно від alias/HAVING.
        if ($request->filled('stars')) {
            $minStars = max(0, min(5, (int) $request->input('stars')));
            if ($minStars > 0) {
                $query->whereRaw(
                    '(select coalesce(avg(stars), 0) from reviews where reviews.product_id = products.id) >= ?',
                    [$minStars]
                );
            }
        }

        // Сортування
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        // Отримуємо мін/макс ціни для плейсхолдерів
        $priceRange = Product::active()->selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();

        return view('client.catalog.index', compact('products', 'categories', 'priceRange'));
    }

    public function show(Product $product)
    {
        $isAdminPreview = request()->boolean('preview') && auth()->check() && auth()->user()->role === 'admin';
        if ($product->is_archived && !$isAdminPreview) {
            abort(404);
        }
        $product->load(['categories', 'photos', 'reviews.user'])
            ->loadAvg('reviews', 'stars')
            ->loadCount('reviews');

        $userReview = null;
        if (auth()->check()) {
            $userReview = $product->reviews->firstWhere('user_id', auth()->id());
        }

        return view('client.catalog.show', [
            'product' => $product,
            'isAdminPreview' => $isAdminPreview,
            'userReview' => $userReview,
        ]);
    }
}