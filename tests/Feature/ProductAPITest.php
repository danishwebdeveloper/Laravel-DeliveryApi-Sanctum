<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductAPITest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_can_create_product()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['create-product']
        );

        $formData = [
            'ItemName' => 'NewName',
            'ItemPrice' => '2.99',
            'ItemProperties' => json_encode(['Matan','Chicken']),
        ];

        $response = $this->postJson('/api/product', $formData);
        $response
        ->assertStatus(201)
        ->assertJson($formData);
    }


    public function test_can_show_product()
    {
        $task = Product::factory()->create();
        $task->save();
        Sanctum::actingAs(
            User::factory()->create(),
            ['view-product']
        );

        $response = $this->getJson('/api/product');
        $response->assertOk();
    }



    public function test_can_update_product()
    {
        $Product = Product::factory()->create();
        $Product->save();
        Sanctum::actingAs(
            User::factory()->create(),
            ['update-product']
        );

        $updateData = [
            'ItemName' => 'UpdateName',
            'ItemPrice' => '2.99',
            'ItemProperties' => json_encode(['Matan','Chicken']),
        ];

        $response = $this->putJson(('/api/product/' . $Product->id), $updateData);
        $response
        ->assertStatus(200)
        ->assertJson($updateData);
    }



    public function test_can_delete_product()
    {
        $Product = Product::factory()->create();
        $Product->save();
        Sanctum::actingAs(
            User::factory()->create(),
            ['delete-product']
        );

        $response = $this->deleteJson('/api/product/'. $Product->id);
        $response->assertStatus(200);
    }

}
