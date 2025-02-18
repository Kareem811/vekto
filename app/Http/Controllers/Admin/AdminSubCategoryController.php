<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subcategories = SubCategory::with('category')->paginate(10); 
        return Response::json(["subcategories" => $subcategories], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subcategories,name',
            'category_id' => 'required|exists:categories,id'
        ]);

        $subcategory = SubCategory::create([
            'name' => $request->name,
            'category_id' => $request->category_id
        ]);

        return Response::json(["message" => "Subcategory created successfully", "subcategory" => $subcategory], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subcategory = SubCategory::with('category')->find($id);

        if (!$subcategory) {
            return response()->json(["message" => "Subcategory not found"], 404);
        }

        return response()->json(["subcategory" => $subcategory], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate request data
        $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:subcategories,name,' . $id,
            'category_id' => 'sometimes|required|exists:categories,id'
        ]);

        $subcategory = SubCategory::find($id);

        if (!$subcategory) {
            return response()->json(["message" => "Subcategory not found"], 404);
        }

        $subcategory->update($request->only(['name', 'category_id']));

        return response()->json(["message" => "Subcategory updated successfully", "subcategory" => $subcategory], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subcategory = SubCategory::find($id);

        if (!$subcategory) {
            return response()->json(["message" => "Subcategory not found"], 404);
        }

        $subcategory->delete(); // Soft delete if enabled

        return response()->json(["message" => "Subcategory deleted successfully"], 200);
    }
}
