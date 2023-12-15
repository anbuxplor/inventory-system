<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\CategoryItem;
use App\Models\Item;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::orderBy('id', 'DESC')->paginate(2);
        return Response::json([
            'data' => CategoryResource::collection($category)->response()->getData(true),
            'success' => true
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request)
    {
        $category = new Category;
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();
        return Response::json([
            'data' => new CategoryResource($category),
            'success' => true,
            'message' => "Category created successfully"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);

        if ($category) {
            return Response::json([
                'data' => new CategoryResource($category),
                'success' => true
            ]);
        } else {
            return Response::json([
                'data' => $category,
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->name = $request->name;
            $category->description = $request->description;
            $category->save();

            return Response::json([
                'data' => new CategoryResource($category),
                'success' => true,
                'message' => 'Category updated successfully'
            ]);
        } else {
            return Response::json([
                'data' => $category,
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // check if the category is associated with any items
        $associatedItems = CategoryItem::where('category_id', $id)->first();
        $category = Category::find($id);
        if (!$associatedItems && $category) {
            $category->delete();
            return Response::json([
                'message' => 'Category deleted successfully',
                'success' => true
            ]);
        } else {
            $message = 'Delete failed, ';
            if($associatedItems) {
                $message .= 'Category is associated with a category.';
            } else {
                $message .= 'Category not found.';
            }
            return Response::json([
                'message' => $message,
                'success' => false
            ], 400);
        }
    }
}
