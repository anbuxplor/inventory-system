<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    private $API_AUTH_TOKEN = 'anbuxplor';

    /**
     * call api without bearer token 
     */
    public function test_api_authendication_middleware()
    {
        $response = $this->postJson('/api/category', [
            'name' => 'Test category1',
            'description' => 'Test description'
        ]);

        $response->assertStatus(403);
    }

    /**
     * call store category Api 
     */
    public function test_store_categories_list_api()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->API_AUTH_TOKEN,
        ])->postJson('/api/category', [
            'name' => 'Test category - ' . uniqid(),
            'description' => 'Test description'
        ]);
        $response->assertStatus(201);
    }

    /**
     * Test update a category api
     */
    public function test_update_category_data_api()
    {
        $category = Category::orderBy('id', 'DESC')->first();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->API_AUTH_TOKEN,
        ])->putJson('/api/category/' . $category->id, [
            'name' => 'Test category - ' . uniqid(),
            'description' => 'Test description'
        ]);
        $response->assertStatus(200);
    }

    /**
     * Test update a not found category api
     */
    public function test_update_not_found_category_data_api()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->API_AUTH_TOKEN,
        ])->putJson('/api/category/100000', [
            'name' => 'Test category - ' . uniqid(),
            'description' => 'Test description'
        ]);
        $response->assertStatus(404);
    }

    /**
     * Test get all category list api
     */
    public function test_get_categories_list_api()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->API_AUTH_TOKEN,
        ])->getJson('/api/category');
        $response->assertStatus(200);
    }

    /**
     * Test a category info api
     */
    public function test_get_a_category_details_api()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->API_AUTH_TOKEN,
        ])->getJson('/api/category/1');
        $response->assertStatus(200);
    }

    /**
     * Test get a not found category api
     */
    public function test_get_a_not_found_category_details_api()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->API_AUTH_TOKEN,
        ])->getJson('/api/category/1000000');
        $response->assertStatus(404);
    }

    /**
     * Test delete a category api
     */
    public function test_delete_a_not_found_category_api()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->API_AUTH_TOKEN,
        ])->delete('/api/category/1000000');
        $response->assertStatus(400);
    }

    /**
     * Test delete a not found category api
     */
    public function test_delete_a_category_api()
    {
        $category = Category::orderBy('id', 'DESC')->first();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->API_AUTH_TOKEN,
        ])->delete('/api/category/' . $category->id);
        $response->assertStatus(200);
    }
}
