<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Debt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\DebtController
 */
final class DebtControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $debts = Debt::factory()->count(3)->create();

        $response = $this->get(route('debts.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\DebtController::class,
            'store',
            \App\Http\Requests\DebtStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $user = User::factory()->create();
        $amount = $this->faker->randomFloat(/** decimal_attributes **/);
        $due_date = Carbon::parse($this->faker->date());
        $status = $this->faker->word();

        $response = $this->post(route('debts.store'), [
            'user_id' => $user->id,
            'amount' => $amount,
            'due_date' => $due_date->toDateString(),
            'status' => $status,
        ]);

        $debts = Debt::query()
            ->where('user_id', $user->id)
            ->where('amount', $amount)
            ->where('due_date', $due_date)
            ->where('status', $status)
            ->get();
        $this->assertCount(1, $debts);
        $debt = $debts->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $debt = Debt::factory()->create();

        $response = $this->get(route('debts.show', $debt));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\DebtController::class,
            'update',
            \App\Http\Requests\DebtUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $debt = Debt::factory()->create();
        $user = User::factory()->create();
        $amount = $this->faker->randomFloat(/** decimal_attributes **/);
        $due_date = Carbon::parse($this->faker->date());
        $status = $this->faker->word();

        $response = $this->put(route('debts.update', $debt), [
            'user_id' => $user->id,
            'amount' => $amount,
            'due_date' => $due_date->toDateString(),
            'status' => $status,
        ]);

        $debt->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($user->id, $debt->user_id);
        $this->assertEquals($amount, $debt->amount);
        $this->assertEquals($due_date, $debt->due_date);
        $this->assertEquals($status, $debt->status);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $debt = Debt::factory()->create();

        $response = $this->delete(route('debts.destroy', $debt));

        $response->assertNoContent();

        $this->assertModelMissing($debt);
    }
}
