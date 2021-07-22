<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    protected $endpoint = '/companies';

    /**
     * Get all companies
     *
     * @test
     * @return void
     */
    public function test_get_all_companies()
    {
        Company::factory()->count(6)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(200)
            ->assertJsonCount(6, 'data');
    }

    /**
     * Error when geting a company that doesn't exist
     * 
     * @test
     */
    public function test_error_get_single_company()
    {
        $fake_uuid = 'fake-uuid';
        $response = $this->getJson($this->endpoint.'/'.$fake_uuid);

        $response->assertStatus(404);
    }

    /**
     * Get single company
     *
     * @test
     */
    public function test_get_single_company()
    {
        $company = Company::factory()->create();
        $uuid = $company->uuid;

        $response = $this->getJson($this->endpoint.'/'.$uuid);

        $response->assertStatus(200);
    }

    /**
     * Validation Store Company
     *
     * @test
     */
    public function test_validation_store_company()
    {
        $response = $this->postJson($this->endpoint, []);

        $response->assertStatus(422);
    }

    /**
     * Store Company
     *
     * @test
     */
    public function test_store_company()
    {
        $category = Category::factory()->create();

        $response = $this->postJson($this->endpoint, [
            'category_id' => $category->id,
            'name' => 'Colmeia Tecnologia',
            'email' => 'rogerio@colmeiatecnologia.com.br',
            'whatsapp' => '+1 111 111 1111',
        ]);

        $response->assertStatus(201);
    }

    /**
     * Validation Update Company
     *
     * @test
     */
    public function test_validation_update_company()
    {
        $category = Category::factory()->create();
        $company = Company::factory()->create();
        $uuid = $company->uuid;
        
        //Validation fail
        $response = $this->putJson($this->endpoint.'/'.$uuid, []);
        $response->assertStatus(422);

        //404
        $data = [
            'category_id' => $category->id,
            'name' => 'Colmeia Tecnologia',
            'email' => 'rogerio@colmeiatecnologia.com.br',
            'whatsapp' => '+1 111 111 1111',
        ];
        $response = $this->putJson($this->endpoint.'/wrong-uuid', $data);
        $response->assertStatus(404);
    }

    /**
     * Update Company
     *
     * @test
     */
    public function test_update_company()
    {
        $category = Category::factory()->create();
        $company = Company::factory()->create();
        $uuid = $company->uuid;
        $data = [
            'category_id' => $category->id,
            'name' => 'Colmeia Tecnologia',
            'email' => 'rogerio@colmeiatecnologia.com.br',
            'whatsapp' => '+1 111 111 1111',
        ];
        
        $response = $this->putJson($this->endpoint.'/'.$uuid, $data);
        $response->assertStatus(200);
    }

    /**
     * Delete Company
     *
     * @test
     */
    public function test_delete_company()
    {
        $company = Company::factory()->create();
        $uuid = $company->uuid;
        
        //404
        $response = $this->deleteJson($this->endpoint.'/fake-uuid');
        $response->assertStatus(404);

        //delete
        $response = $this->deleteJson($this->endpoint.'/'.$uuid);
        $response->assertStatus(204);
    }
}
