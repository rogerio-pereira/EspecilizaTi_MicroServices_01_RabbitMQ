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
}
