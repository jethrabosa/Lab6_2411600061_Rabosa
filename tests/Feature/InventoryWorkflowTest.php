<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_in_and_stock_out_update_inventory(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(quantity: 10);
        $this->actingAs($user);

        $this->post(route('transactions.store'), [
            'product_id' => $product->id,
            'type' => 'stock_in',
            'quantity' => 5,
        ])->assertRedirect(route('transactions.index'));

        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 15]);

        $this->post(route('transactions.store'), [
            'product_id' => $product->id,
            'type' => 'stock_out',
            'quantity' => 4,
        ])->assertRedirect(route('transactions.index'));

        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 11]);
        $this->assertDatabaseCount('inventory_transactions', 2);
    }

    public function test_product_creation_records_initial_stock_as_an_opening_transaction(): void
    {
        $this->actingAs(User::factory()->create())->post(route('products.store'), [
            'name' => 'New Product',
            'sku' => 'NEW-001',
            'category' => 'Materials',
            'quantity' => 8,
            'reorder_level' => 2,
            'unit_cost' => 3.50,
        ])->assertRedirect(route('products.index'));

        $product = Product::where('sku', 'NEW-001')->firstOrFail();
        $this->assertSame(8, $product->quantity);
        $this->assertDatabaseHas('inventory_transactions', [
            'product_id' => $product->id,
            'type' => 'stock_in',
            'quantity' => 8,
            'reference_number' => 'OPENING-NEW-001',
        ]);
    }

    public function test_stock_out_cannot_exceed_available_inventory(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(quantity: 3);
        $this->actingAs($user);

        $this->from(route('transactions.create'))
            ->post(route('transactions.store'), [
                'product_id' => $product->id,
                'type' => 'stock_out',
                'quantity' => 4,
            ])
            ->assertRedirect(route('transactions.create'))
            ->assertSessionHasErrors('quantity');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 3]);
        $this->assertDatabaseCount('inventory_transactions', 0);
    }

    public function test_adjustment_records_the_difference_from_physical_count(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(quantity: 10);

        $this->actingAs($user)->post(route('transactions.adjustment.store'), [
            'product_id' => $product->id,
            'physical_count' => 6,
            'reason' => 'damaged',
            'notes' => 'Two items damaged.',
        ])->assertRedirect(route('transactions.index'));

        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 6]);
        $this->assertDatabaseHas('inventory_transactions', [
            'product_id' => $product->id,
            'type' => 'adjustment',
            'quantity' => -4,
            'user_id' => $user->id,
        ]);
    }

    public function test_low_stock_notification_is_created_when_stock_crosses_reorder_level(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $product = $this->createProduct(quantity: 10, reorderLevel: 4);

        $this->actingAs($user)->post(route('transactions.store'), [
            'product_id' => $product->id,
            'type' => 'stock_out',
            'quantity' => 6,
        ])->assertRedirect(route('transactions.index'));

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $admin->id,
            'notifiable_type' => User::class,
        ]);
    }

    public function test_inventory_pages_render_for_authenticated_users(): void
    {
        $this->actingAs(User::factory()->create());
        $product = $this->createProduct(quantity: 7);

        $this->get(route('dashboard'))->assertOk();
        $this->get(route('products.show', $product))->assertOk();
        $this->get(route('transactions.index'))->assertOk();
        $this->get(route('transactions.adjustment.create'))->assertOk();
        $this->get(route('reports.index'))->assertOk();
        $this->get(route('notifications.index'))->assertOk();
    }

    public function test_public_users_cannot_delete_a_transaction_and_its_audit_record(): void
    {
        $this->delete('/transactions/1')->assertMethodNotAllowed();
    }

    public function test_dashboard_data_endpoints_are_public_and_return_inventory_data(): void
    {
        $this->getJson('/api/dashboard/stats')->assertOk();

        $this->createProduct(quantity: 7);

        $this->getJson('/api/dashboard/stats')
            ->assertOk()
            ->assertJsonPath('total_products', 1)
            ->assertJsonPath('total_quantity', 7);
        $this->getJson('/api/products/low-stock')->assertOk()->assertJsonCount(0);
        $this->getJson('/api/transactions/recent')->assertOk()->assertJsonCount(0);
    }

    public function test_main_inventory_pages_are_available_without_sign_in(): void
    {
        $product = $this->createProduct(quantity: 7);

        $this->get('/')->assertOk();
        $this->get(route('dashboard'))->assertOk();
        $this->get(route('products.index'))->assertOk();
        $this->get(route('products.show', $product))->assertOk();
        $this->get(route('transactions.index'))->assertOk();
        $this->get(route('transactions.adjustment.create'))->assertOk();
        $this->get(route('reports.index'))->assertOk();
        $this->get(route('notifications.index'))->assertOk();
    }

    private function createProduct(int $quantity = 0, int $reorderLevel = 2): Product
    {
        return Product::create([
            'name' => 'Test Cement',
            'sku' => uniqid('SKU-'),
            'category' => 'Building Materials',
            'quantity' => $quantity,
            'reorder_level' => $reorderLevel,
            'unit_cost' => 5.25,
        ]);
    }
}
