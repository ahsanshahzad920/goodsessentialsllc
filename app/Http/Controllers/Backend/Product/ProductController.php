<?php

namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private const MAX_IMAGES = 10;

    public function index()
    {
        $products = Product::with('category')->withCount('images')->get();

        return view('backend.products.index', compact('products'));
    }

    // Show form to create new product
    public function create()
    {
        $categories = Category::all();

        return view('backend.products.create', compact('categories'));
    }

    // Store new product
    public function store(Request $request)
    {
        $request->validate($this->rules());

        DB::transaction(function () use ($request) {
            $product = Product::create([
                'product_title'       => $request->product_title,
                'product_description' => $request->product_description,
                'product_image'       => null, // set below from the main gallery image
                'category_id'         => $request->category_id,
                'price'               => $request->price,
                'dis_price'           => $request->dis_price,
                'colors'              => $this->cleanColors($request),
                'created_by'          => Auth::id(),
                'updated_by'          => Auth::id(),
            ]);

            $this->storeImages($product, $request->file('images', []), 0);
            $this->syncMainImage($product);
        });

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    // Show a single product
    public function show(Product $product)
    {
        return view('backend.products.show', compact('product'));
    }

    // Show form to edit product
    public function edit(Product $product)
    {
        $product->load('images');
        $categories = Category::all();

        return view('backend.products.edit', compact('product', 'categories'));
    }

    // Update a product
    public function update(Request $request, Product $product)
    {
        $request->validate($this->rules() + [
            'remove_images'   => 'nullable|array',
            'remove_images.*' => 'integer',
            'main_image'      => 'nullable|integer',
        ]);

        $removeIds = array_map('intval', (array) $request->input('remove_images', []));
        $newFiles  = $request->file('images', []);

        // Total images after this save must stay within the limit
        $remaining = $product->images()->whereNotIn('id', $removeIds)->count();

        if ($remaining + count($newFiles) > self::MAX_IMAGES) {
            return back()->withInput()->withErrors([
                'images' => 'A product can have at most ' . self::MAX_IMAGES . ' images.',
            ]);
        }

        $deletePaths = [];

        DB::transaction(function () use ($request, $product, $removeIds, $newFiles, &$deletePaths) {
            // Products created before galleries existed: bring their old image into the gallery first
            if ($product->product_image && $product->images()->doesntExist()) {
                $product->images()->create(['path' => $product->product_image, 'sort_order' => 0]);
            }

            $product->update([
                'product_title'       => $request->product_title,
                'product_description' => $request->product_description,
                'category_id'         => $request->category_id,
                'price'               => $request->price,
                'dis_price'           => $request->dis_price,
                'colors'              => $this->cleanColors($request),
                'updated_by'          => Auth::id(),
            ]);

            // Remove ticked images (files are deleted after the transaction succeeds)
            if ($removeIds) {
                $product->images()->whereIn('id', $removeIds)->get()->each(function (ProductImage $image) use (&$deletePaths) {
                    $deletePaths[] = $image->path;
                    $image->delete();
                });
            }

            // Add newly uploaded images after the existing ones
            $next = (int) ($product->images()->max('sort_order') ?? -1) + 1;
            $this->storeImages($product, $newFiles, $next);

            // Chosen main image (must belong to this product, otherwise the first image is used)
            $this->syncMainImage(
                $product,
                $request->filled('main_image') ? (int) $request->input('main_image') : null
            );
        });

        if ($deletePaths) {
            Storage::disk('public')->delete($deletePaths);
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    // Delete a product
    public function destroy(Product $product)
    {
        $paths = $product->images()->pluck('path')
            ->push($product->product_image)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $product->delete(); // gallery rows are removed by the database (cascade)

        if ($paths) {
            Storage::disk('public')->delete($paths);
        }

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    private function rules(): array
    {
        return [
            'product_title'       => 'required',
            'product_description' => 'required',
            'category_id'         => 'required|exists:categories,id',
            'price'               => 'required|numeric',
            'dis_price'           => 'required|numeric',
            'images'              => 'nullable|array|max:' . self::MAX_IMAGES,
            'images.*'            => 'file|mimes:jpeg,png,jpg,gif,bmp,svg,webp,avif|max:10240',
            'colors'              => 'nullable|array|max:12',
            'colors.*.name'       => 'required|string|max:30',
            'colors.*.hex'        => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ];
    }

    /** Colors as saved on the product: [['name' => 'Red', 'hex' => '#ef4444'], ...] or null. */
    private function cleanColors(Request $request): ?array
    {
        $colors = collect($request->input('colors', []))
            ->map(fn ($color) => [
                'name' => trim($color['name']),
                'hex'  => strtolower($color['hex']),
            ])
            ->unique('hex')
            ->values()
            ->all();

        return $colors ?: null;
    }

    /** Store uploaded files in storage/app/public/products and create their gallery rows. */
    private function storeImages(Product $product, array $files, int $startOrder): void
    {
        foreach (array_values($files) as $i => $file) {
            $product->images()->create([
                'path'       => $file->store('products', 'public'),
                'sort_order' => $startOrder + $i,
            ]);
        }
    }

    /**
     * Put the main image first and mirror it into products.product_image,
     * so product cards, the cart and orders keep using a single "cover" image.
     */
    private function syncMainImage(Product $product, ?int $preferredId = null): void
    {
        $images = $product->images()->get();

        if ($images->isEmpty()) {
            $product->update(['product_image' => null]);

            return;
        }

        $main = $preferredId ? $images->firstWhere('id', $preferredId) : null;
        $main ??= $images->first();

        $ordered = collect([$main])->merge($images->where('id', '!=', $main->id))->values();

        foreach ($ordered as $position => $image) {
            if ((int) $image->sort_order !== $position) {
                $image->update(['sort_order' => $position]);
            }
        }

        $product->update(['product_image' => $main->path]);
    }
}
