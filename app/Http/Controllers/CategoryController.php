<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\CategoryCreated;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::get();
        return Response::json([
            'data' => $category,
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
        // $toEmail = env('INVENTORY_ADMIN_EMAIL', 'anbu.ayyakannu@xplortechnologies.com');
        // try {
        //     Mail::to($toEmail)->send(new CategoryCreated($category));
        //     $message = 'Category created and Email sent successfully!';
        // } catch (\Exception $e) {
        //     $message = 'Category created But Email failed, reason: '.$e->getMessage();
        // }
        return Response::json([
            'data' => $category,
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
                'data' => $category,
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
                'data' => $category,
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
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            return Response::json([
                'message' => 'Category deleted successfully',
                'success' => true
            ]);
        } else {
            return Response::json([
                'message' => 'Delete failed, Category not found.',
                'success' => false
            ], 400);
        }
    }
}
