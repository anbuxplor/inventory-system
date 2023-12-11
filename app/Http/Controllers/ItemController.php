<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\CategoryItem;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\CreateItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Mail\ItemCreated;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get and display all the items with associated categories
        $items = Item::with(['category.info'])->get();
        return Response::json([
            'data' => $items,
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

        $catItemAssoc = new CategoryItem;
        $catItemAssoc->item_id = $item->id;
        $catItemAssoc->category_id = $request->category_id;
        $catItemAssoc->save();

        $toEmail = env('INVENTORY_ADMIN_EMAIL');
        try {
            Mail::to($toEmail)->send(new ItemCreated($item));
            $message = 'Item created and Email sent successfully!';
        } catch (\Exception $e) {
            $message = 'Item created But Email failed, reason: '.$e->getMessage();
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
        return Response::json([
            'data' => $item,
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
        $item->name = $request->name;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->quantity = $request->quantity;
        $item->save();
        
        $catItemAssoc = CategoryItem::where('item_id', $item->id)->first();
        $catItemAssoc->item_id = $item->id;
        $catItemAssoc->category_id = $request->category_id;
        $catItemAssoc->save();
                
        return Response::json([
            'data' => $item,
            'success' => true,
            'message' => 'Item updated successfully'
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
            $item->delete();
            return Response::json([
                'message' => 'Item deleted successfully',
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
