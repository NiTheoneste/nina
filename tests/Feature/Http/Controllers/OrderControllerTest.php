<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\OrderController
 */
final class OrderControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $orders = Order::factory()->count(3)->create();

        $response = $this->get(route('orders.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\OrderController::class,
            'store',
            \App\Http\Requests\OrderStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $user = User::factory()->create();
        $total_amount = $this->faker->randomFloat(/** decimal_attributes **/);
        $status = $this->faker->word();

        $response = $this->post(route('orders.store'), [
            'user_id' => $user->id,
            'total_amount' => $total_amount,
            'status' => $status,
        ]);

        $orders = Order::query()
            ->where('user_id', $user->id)
            ->where('total_amount', $total_amount)
            ->where('status', $status)
            ->get();
        $this->assertCount(1, $orders);
        $order = $orders->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $order = Order::factory()->create();

        $response = $this->get(route('orders.show', $order));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\OrderController::class,
            'update',
            \App\Http\Requests\OrderUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $order = Order::factory()->create();
        $user = User::factory()->create();
        $total_amount = $this->faker->randomFloat(/** decimal_attributes **/);
        $status = $this->faker->word();

        $response = $this->put(route('orders.update', $order), [
            'user_id' => $user->id,
            'total_amount' => $total_amount,
            'status' => $status,
        ]);

        $order->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals($total_amount, $order->total_amount);
        $this->assertEquals($status, $order->status);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $order = Order::factory()->create();

        $response = $this->delete(route('orders.destroy', $order));

        $response->assertNoContent();

        $this->assertModelMissing($order);
    }
}
