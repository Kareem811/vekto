<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all products with category, subcategory, and brand
        $products = Product::with(['category', 'subcategory'])
            ->paginate(10); // Pagination for better performance

        return Response::json(["products" => $products], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'brand' => 'required|string|max:255',
            'color' => 'nullable|string|max:50',
            'size' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Validate multiple images
        ]);
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'brand' => $request->brand,
            'color' => $request->color,
            'size' => $request->size,
            'description' => $request->description,
            'stock' => $request->stock
        ]);
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
            $product->images = json_encode($images);
            $product->save();
        }

        return Response::json(["message" => "Product created successfully", "product" => $product], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with(['category', 'subcategory'])->find($id);

        if (!$product) {
            return response()->json(["message" => "Product not found"], 404);
        }

        return response()->json(["product" => $product], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'category_id' => 'sometimes|required|exists:categories,id',
            'subcategory_id' => 'sometimes|nullable|exists:subcategories,id',
            'brand' => 'sometimes|required|string|max:255',
            'color' => 'sometimes|nullable|string|max:50',
            'size' => 'sometimes|nullable|string|max:50',
            'description' => 'sometimes|nullable|string',
            'stock' => 'sometimes|required|integer|min:0',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Validate multiple images
        ]);

        $product = Product::find($id);

        if (!$product) {
            return response()->json(["message" => "Product not found"], 404);
        }

        $product->update($request->only([
            'name', 'price', 'category_id', 'subcategory_id', 'brand', 'color', 'size', 'description', 'stock'
        ]));

        // Handle image update
        if ($request->hasFile('images')) {
            // Delete old images
            if ($product->images) {
                foreach (json_decode($product->images) as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
            $product->images = json_encode($images);
            $product->save();
        }

        return response()->json(["message" => "Product updated successfully", "product" => $product], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(["message" => "Product not found"], 404);
        }

        // Delete images
        if ($product->images) {
            foreach (json_decode($product->images) as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return response()->json(["message" => "Product deleted successfully"], 200);
    }
}
