<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\CategoryItem;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\CreateItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Http\Resources\ItemResource;
use App\Mail\ItemCreated;
use App\Mail\ItemDeleted;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get and display all the items with associated categories
        $items = Item::with(['category.info'])->orderBy('id', 'DESC')->get();

        return Response::json([
            'data' => ItemResource::collection($items),
            'success' => true
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * Store request in items table
     * Store last inserted item and category id in associate table (category_items)
     * Call email function to send notification 
     */
    public function store(CreateItemRequest $request)
    {
        $item = new Item;
        $item->name = $request->name;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->quantity = $request->quantity;
        $item->save();

        // Insert/Map multiple categories for the item
        $itemInsertArr = [];
        $categories = $request->category_id;
        foreach ($categories as $category) {
            $itemInsertArr[] = [
                'item_id' => $item->id,
                'category_id' => $category,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        if (count($itemInsertArr)) {
            // multiple insert in a single query
            CategoryItem::insert($itemInsertArr);
        }

        $toEmail = env('INVENTORY_ADMIN_EMAIL');
        $ccUsers = explode(',', env('INVENTORY_TEAM_EMAILS'));
        try {
            $item->is_created = true;
            Mail::to($toEmail)
                ->cc($ccUsers)
                ->send(new ItemCreated($item));
            $message = 'Item created and Email sent successfully!';
            unset($item->is_created);
        } catch (\Exception $e) {
            $message = 'Item created But Email failed, reason: ' . $e->getMessage();
        }
        return Response::json([
            'data' => $item,
            'success' => true,
            'message' => $message
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Get the item info with mapped categories
        $item = Item::with(['category.info'])->find($id);
        if(!$item) {
            return Response::json([
                'data' => $item,
                'success' => false,
                'message' => 'not found'
            ], 404);
        }
        return Response::json([
            'data' => new ItemResource($item),
            'success' => true
        ]);
    }

    /**
     * Update the specified resource in storage.
     * Find and get the item data and update the new input request data
     * Also update the associated table with the given categories
     */
    public function update(UpdateItemRequest $request, string $id)
    {
        $item = Item::find($id);
        if (!$item) {
            return Response::json([
                'data' => $item,
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }
        $item->name = $request->name;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->quantity = $request->quantity;
        $item->save();

        // Delete already mapped categories
        CategoryItem::where('item_id', $item->id)->delete();

        // Insert data in the mapping table(i.e category_items table)
        $itemInsertArr = [];
        $categories = $request->category_id;
        foreach ($categories as $category) {
            $itemInsertArr[] = [
                'item_id' => $item->id,
                'category_id' => $category,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        if (count($itemInsertArr)) {
            CategoryItem::insert($itemInsertArr);
        }
        $toEmail = env('INVENTORY_ADMIN_EMAIL');
        $ccUsers = explode(',', env('INVENTORY_TEAM_EMAILS'));
        try {
            $item->is_created = false;
            Mail::to($toEmail)
                ->cc($ccUsers)
                ->send(new ItemCreated($item));
            $message = 'Item updated and Email sent successfully!';
            unset($item->is_created);
        } catch (\Exception $e) {
            $message = 'Item updated But Email failed, reason: ' . $e->getMessage();
        }
        return Response::json([
            'data' => $item,
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * Delete item table data
     * It will automatically delete data in associated table(category_items)
     * We already used on delete cascade to auto delete child records
     */
    public function destroy(string $id)
    {
        $item = Item::find($id);
        if ($item) {
            $itemDetails = $item;
            $item->delete();

            $toEmail = env('INVENTORY_ADMIN_EMAIL');
            $ccUsers = explode(',', env('INVENTORY_TEAM_EMAILS'));
            try {
                Mail::to($toEmail)
                    ->cc($ccUsers)
                    ->send(new ItemDeleted($itemDetails));
                $message = 'Item deleted and Email sent successfully!';
            } catch (\Exception $e) {
                $message = 'Item deleted But Email failed, reason: ' . $e->getMessage();
            }

            return Response::json([
                'message' => $message,
                'success' => true
            ]);
        } else {
            return Response::json([
                'message' => 'Item delete failed, Item not found.',
                'success' => false
            ], 400);
        }
    }
}
