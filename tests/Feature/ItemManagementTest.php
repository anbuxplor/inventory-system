<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemManagementTest extends TestCase
{
    // private $API_HEADERS = 'anbuxplor';

    private $API_HEADERS = [
        'Authorization' => 'Bearer anbuxplor',
        'Accept' => 'application/json'
    ];

    /**
     * call item list Api 
     */
    public function test_item_list_api()
    {
        $response = $this->withHeaders($this->API_HEADERS)->getJson('/api/item');
        $response->assertStatus(200);
    }

    /**
     * call item store Api 
     */
    public function test_item_store_api()
    {
        $categories = Category::pluck('id');
        $response = $this->withHeaders($this->API_HEADERS)
            ->postJson('/api/item', [
                'name' => 'Test item - ' . uniqid(),
                'description' => 'Test description',
                'price' => 200.00,
                'quantity' => 50,
                'category_id' => $categories
            ]);
        $response->assertStatus(201);
    }

    /**
     * Test update an item api
     */
    public function test_update_item_api()
    {
        // get a valid item from database
        $item = Item::orderBy('id', 'ASC')->first();
        $categories = Category::pluck('id')->first();
        $response = $this->withHeaders($this->API_HEADERS)
            ->put('/api/item/' . $item->id, [
                'name' => 'Test category - ' . uniqid(),
                'description' => 'Test description updated',
                'price' => 200.00,
                'quantity' => 501,
                'category_id' => [$categories]
            ]);
        $response->assertStatus(200);
    }

    /**
     * Test update a not found item api
     */
    public function test_update_not_found_item_data_api()
    {
        $response = $this->withHeaders($this->API_HEADERS)
            ->put('/api/item/100000', [
            'name' => 'Test category - ' . uniqid(),
            'description' => 'Test description updated',
            'price' => 200.00,
            'quantity' => 50,
            'category_id' => [1]
        ]);
        $response->assertStatus(404);
    }

    /**
     * Test a item info api
     */
    public function test_get_a_item_details_api()
    {
        $item = Item::orderBy('id', 'DESC')->first();
        $response = $this->withHeaders($this->API_HEADERS)->getJson('/api/item/'.$item->id);
        $response->assertStatus(200);
    }

    /**
     * Test delete a item api
     */
    public function test_delete_a_not_found_item_api()
    {
        $response = $this->withHeaders($this->API_HEADERS)
            ->delete('/api/item/1000000');
        $response->assertStatus(400);
    }

    /**
     * Test delete a not found item api
     */
    public function test_delete_a_item_api()
    {
        $category = Item::orderBy('id', 'DESC')->first();
        $response = $this->withHeaders($this->API_HEADERS)
            ->delete('/api/item/' . $category->id);
        $response->assertStatus(200);
    }

}
