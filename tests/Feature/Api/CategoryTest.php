<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    protected $endpoint = '/categories';

    /**
     * Get all categories
     *
     * @test
     * @return void
     */
    public function test_get_all_categories()
    {
        Category::factory()->count(6)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(200)
            ->assertJsonCount(6, 'data');
    }

    /**
     * Error when geting a category that doesn't exist
     * 
     * @test
     */
    public function test_error_get_single_category()
    {
        $fake_url = 'fake-url';
        $response = $this->getJson($this->endpoint.'/'.$fake_url);

        $response->assertStatus(404);
    }

    /**
     * Get single category
     *
     * @test
     */
    public function test_get_single_category()
    {
        $category = Category::factory()->create();
        $url = $category->url;

        $response = $this->getJson($this->endpoint.'/'.$url);

        $response->assertStatus(200);
    }

    /**
     * Validation Store Category
     *
     * @test
     */
    public function test_validation_store_category()
    {
        $response = $this->postJson($this->endpoint, [
            'title' => '',
            'description' => '',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Store Category
     *
     * @test
     */
    public function test_store_category()
    {
        $response = $this->postJson($this->endpoint, [
            'title' => 'title',
            'description' => 'description',
        ]);

        $response->assertStatus(201);
    }

    /**
     * Validation Update Category
     *
     * @test
     */
    public function test_validation_update_category()
    {
        $category = Category::factory()->create();
        $url = $category->url;
        
        //Validation fail
        $response = $this->putJson($this->endpoint.'/'.$url, []);
        $response->assertStatus(422);

        //404
        $data = [
            'title' => 'title',
            'description' => 'description',
        ];
        $response = $this->putJson($this->endpoint.'/wrong-url', $data);
        $response->assertStatus(404);
    }

    /**
     * Update Category
     *
     * @test
     */
    public function test_update_category()
    {
        $category = Category::factory()->create();
        $url = $category->url;
        $data = [
            'title' => 'title',
            'description' => 'description',
        ];
        
        $response = $this->putJson($this->endpoint.'/'.$url, $data);
        $response->assertStatus(200);
    }

    /**
     * Delete Category
     *
     * @test
     */
    public function test_delete_category()
    {
        $category = Category::factory()->create();
        $url = $category->url;
        
        //404
        $response = $this->deleteJson($this->endpoint.'/fake-url');
        $response->assertStatus(404);

        //delete
        $response = $this->deleteJson($this->endpoint.'/'.$url);
        $response->assertStatus(204);
    }
}
