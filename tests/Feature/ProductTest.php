<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testList()
    {
        $this->get('/api/products')
            ->assertStatus(200)
            ->assertJsonStructure(["*" => [
                "id",
                "name",
                "photo_id",
                "price"
            ]]);
    }
    public function testGet()
    {
        $this->get('/api/products/1')
            ->assertStatus(200)
            ->assertJsonStructure([
                "id",
                "name",
                "photo_id",
                "price",
                "composition",
                "weight",
                "calories",
                "proteins",
                "fats",
                "carbohydrates"
            ]);
    }
    public function testX()
    {
        $this->assertEquals(True, True);
    }
}
