<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_item_and_record_stock_transactions(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $employee = Employee::create([
            'name' => 'Petugas Inventaris',
            'employee_code' => 'INV-001',
            'area' => 'Blok A',
            'shift' => 'pagi',
            'is_active' => true,
        ]);

        $this->actingAs($admin)->post(route('inventories.store'), [
            'name' => 'Sapu Test',
            'unit' => 'pcs',
            'minimum_stock' => 3,
            'description' => 'Barang test',
            'is_active' => 1,
        ])->assertRedirect(route('inventories.index'));

        $item = InventoryItem::where('name', 'Sapu Test')->firstOrFail();

        $this->actingAs($admin)->post(route('inventory-transactions.store'), [
            'inventory_item_id' => $item->id,
            'transaction_date' => '2026-05-10',
            'type' => InventoryTransaction::TYPE_IN,
            'quantity' => 10,
            'source' => 'Toko',
        ])->assertRedirect(route('inventory-transactions.index'));

        $this->actingAs($admin)->post(route('inventory-transactions.store'), [
            'inventory_item_id' => $item->id,
            'transaction_date' => '2026-05-11',
            'type' => InventoryTransaction::TYPE_ALLOCATION,
            'quantity' => 4,
            'employee_id' => $employee->id,
            'area' => 'Blok A',
        ])->assertRedirect(route('inventory-transactions.index'));

        $this->assertSame(6, $item->fresh()->current_stock);
        $this->assertDatabaseHas('inventory_transactions', [
            'inventory_item_id' => $item->id,
            'employee_id' => $employee->id,
            'type' => InventoryTransaction::TYPE_ALLOCATION,
            'quantity' => 4,
        ]);
    }

    public function test_stock_out_cannot_exceed_current_stock(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $item = InventoryItem::create(['name' => 'Masker Test', 'unit' => 'box', 'minimum_stock' => 2]);

        $this->actingAs($admin)->post(route('inventory-transactions.store'), [
            'inventory_item_id' => $item->id,
            'transaction_date' => '2026-05-10',
            'type' => InventoryTransaction::TYPE_OUT,
            'quantity' => 1,
        ])->assertSessionHas('error');

        $this->assertSame(0, InventoryTransaction::count());
    }

    public function test_inventory_pages_are_admin_only_and_reports_show_usage(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $employee = Employee::create([
            'name' => 'Petugas Role Inventory',
            'employee_code' => 'INVR-001',
            'area' => 'Blok B',
            'shift' => 'pagi',
            'is_active' => true,
        ]);
        $employeeUser = User::factory()->create(['role' => 'employee', 'employee_id' => $employee->id]);
        $item = InventoryItem::create(['name' => 'Bensin Test', 'unit' => 'liter', 'minimum_stock' => 5]);
        InventoryTransaction::create(['inventory_item_id' => $item->id, 'transaction_date' => '2026-05-10', 'type' => 'in', 'quantity' => 20]);
        InventoryTransaction::create(['inventory_item_id' => $item->id, 'transaction_date' => '2026-05-11', 'type' => 'out', 'quantity' => 7, 'area' => 'Blok B']);

        foreach ([route('inventories.index'), route('inventory-transactions.index'), route('inventory-reports.usage')] as $url) {
            $this->actingAs($employeeUser)->get($url)->assertForbidden();
        }

        $this->actingAs($admin)->get(route('inventories.index'))->assertOk()->assertSee('Bensin Test');
        $this->actingAs($admin)->get(route('inventory-reports.usage', ['month' => 5, 'year' => 2026]))
            ->assertOk()
            ->assertSee('Penggunaan Inventaris')
            ->assertSee('Bensin Test')
            ->assertSee('>20<', false)
            ->assertSee('>7<', false);
    }

    public function test_dashboard_shows_real_low_stock_inventory_alert(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        InventoryItem::create(['name' => 'Plastik Rendah', 'unit' => 'roll', 'minimum_stock' => 5]);

        $this->actingAs($admin)->get('/dashboard')
            ->assertOk()
            ->assertSee('Stok Barang Rendah')
            ->assertSee('Plastik Rendah');
    }
}
