<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // ===== Список товарів (фільтри/сортування) =====
    public function index(Request $request)
    {
        $query = Product::with('categories')
            ->withAvg('reviews', 'stars')
            ->withCount('reviews');

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('price_from')) {
            $query->where('price', '>=', (float) $request->input('price_from'));
        }

        if ($request->filled('price_to')) {
            $query->where('price', '<=', (float) $request->input('price_to'));
        }

        if ($request->filled('stars')) {
            $minStars = max(0, min(5, (int) $request->input('stars')));
            if ($minStars > 0) {
                $query->whereRaw(
                    '(select coalesce(avg(stars), 0) from reviews where reviews.product_id = products.id) >= ?',
                    [$minStars]
                );
            }
        }

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

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    // ===== Форма створення товару =====
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    // ===== Створення товару + завантаження фото =====
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categories' => 'array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'main_photo' => 'nullable|integer',
            'photo_order' => 'array',
            'photo_order.*' => 'integer',
            'main_new_index' => 'nullable|integer',
            'is_archived' => 'nullable|boolean',
        ]);

        $validated['is_archived'] = $request->has('is_archived');

        $product = Product::create($validated);
        $product->categories()->sync($request->categories ?? []);

        // Завантаження фото
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('products', 'public');
                ProductPhoto::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_main' => $index === 0,
                    'order' => $index
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Товар створено');
    }

    // ===== Перегляд товару (адмін) =====
    public function show(Product $product)
    {
        $product->load(['categories', 'reviews.user']);
        return view('admin.products.show', compact('product'));
    }

    // ===== Форма редагування товару =====
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // ===== Оновлення товару (включно з фото) =====
    public function update(Request $request, Product $product)
    {
        // Валідація: основні поля + керування фото (порядок/основне/видалення до збереження)
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categories' => 'array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_order' => 'array',
            'photo_order.*' => 'integer',
            'main_photo' => 'nullable|integer',
            'photos_delete' => 'array',
            'photos_delete.*' => 'integer',
            'is_archived' => 'nullable|boolean',
        ]);

        $validated['is_archived'] = $request->has('is_archived');
        $product->update($validated);
        $product->categories()->sync($request->categories ?? []);

        // Наявні фото: порядок та основне фото
        $photoOrderInput = $request->input('photo_order', []);
        $mainPhotoId = $request->input('main_photo');

        $product->load('photos');
        foreach ($product->photos as $photo) {
            $photo->order = $photoOrderInput[$photo->id] ?? ($photo->order ?? 0);
            $photo->is_main = $mainPhotoId ? (int)$mainPhotoId === $photo->id : $photo->is_main;
            $photo->save();
        }

        // Наявні фото: видалення застосовується лише після натискання "Зберегти"
        $deleteIds = array_values(array_unique(array_map('intval', (array) $request->input('photos_delete', []))));
        if (!empty($deleteIds)) {
            $photosToDelete = ProductPhoto::query()
                ->where('product_id', $product->id)
                ->whereIn('id', $deleteIds)
                ->get();

            foreach ($photosToDelete as $photo) {
                Storage::disk('public')->delete($photo->path);
                $photo->delete();
            }
        }

        // Завантаження нових фото
        if ($request->hasFile('photos')) {
            $existingMaxOrder = $product->photos()->max('order') ?? -1;
            $newMainIndex = $request->input('main_new_index');

            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('products', 'public');
                $isMainNew = $newMainIndex !== null && $newMainIndex !== '' ? ((int) $newMainIndex === $index) : false;
                ProductPhoto::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_main' => $isMainNew,
                    'order' => $existingMaxOrder + $index + 1,
                ]);
            }
        }

        // Гарантуємо наявність основного фото (після можливого видалення)
        $hasMain = $product->photos()->where('is_main', true)->exists();
        if (!$hasMain) {
            $firstPhoto = $product->photos()->orderBy('order')->first();
            if ($firstPhoto) {
                $firstPhoto->is_main = true;
                $firstPhoto->save();
            }
        }

        // Перезавантажити відсортовано
        $product->photos()->orderBy('order')->get()->each(function ($photo, $idx) {
            $photo->order = $idx;
            $photo->save();
        });

        return redirect()->route('admin.products.index')->with('success', 'Товар оновлено');
    }

    // ===== Видалення товару (з перевірками залежностей) =====
    public function destroy(Product $product)
    {
        if ($product->orderItems()->exists()) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Неможливо видалити товар, оскільки він використовується в замовленнях.');
        }

        if ($product->cartItems()->exists()) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Неможливо видалити товар, оскільки він знаходиться у кошиках користувачів.');
        }

        foreach ($product->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
            $photo->delete();
        }

        $product->categories()->detach();
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Товар видалено');
    }
}