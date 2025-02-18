<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return Response::json(["categories" => $categories], 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name'
        ]);
        $category = Category::create([
            'name' => $request->name
        ]);

        return Response::json(["message" => "Created successfully", "category" => $category], 201);
    }
    public function show(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(["message" => "Category not found"], 404);
        }

        return response()->json(["category" => $category], 200);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id
        ]);

        $category = Category::find($id);

        if (!$category) {
            return response()->json(["message" => "Category not found"], 404);
        }
        $category->update([
            'name' => $request->name
        ]);
        return response()->json(["message" => "Updated successfully", "category" => $category], 200);
    }
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(["message" => "Category not found"], 404);
        }

        $category->delete();

        return response()->json(["message" => "Deleted successfully"], 200);
    }
}
