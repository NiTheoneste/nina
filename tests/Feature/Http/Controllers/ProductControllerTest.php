<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ProductController
 */
final class ProductControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $products = Product::factory()->count(3)->create();

        $response = $this->get(route('products.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ProductController::class,
            'store',
            \App\Http\Requests\ProductStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $name = $this->faker->name();
        $description = $this->faker->text();
        $price = $this->faker->randomFloat(/** decimal_attributes **/);
        $stock_quantity = $this->faker->numberBetween(-10000, 10000);
        $category = Category::factory()->create();

        $response = $this->post(route('products.store'), [
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'stock_quantity' => $stock_quantity,
            'category_id' => $category->id,
        ]);

        $products = Product::query()
            ->where('name', $name)
            ->where('description', $description)
            ->where('price', $price)
            ->where('stock_quantity', $stock_quantity)
            ->where('category_id', $category->id)
            ->get();
        $this->assertCount(1, $products);
        $product = $products->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $product = Product::factory()->create();

        $response = $this->get(route('products.show', $product));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ProductController::class,
            'update',
            \App\Http\Requests\ProductUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $product = Product::factory()->create();
        $name = $this->faker->name();
        $description = $this->faker->text();
        $price = $this->faker->randomFloat(/** decimal_attributes **/);
        $stock_quantity = $this->faker->numberBetween(-10000, 10000);
        $category = Category::factory()->create();

        $response = $this->put(route('products.update', $product), [
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'stock_quantity' => $stock_quantity,
            'category_id' => $category->id,
        ]);

        $product->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($name, $product->name);
        $this->assertEquals($description, $product->description);
        $this->assertEquals($price, $product->price);
        $this->assertEquals($stock_quantity, $product->stock_quantity);
        $this->assertEquals($category->id, $product->category_id);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $product = Product::factory()->create();

        $response = $this->delete(route('products.destroy', $product));

        $response->assertNoContent();

        $this->assertModelMissing($product);
    }
}
