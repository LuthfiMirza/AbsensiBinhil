<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $items = collect([
            ['name' => 'Sapu', 'unit' => 'pcs', 'minimum_stock' => 5, 'initial' => 18],
            ['name' => 'Bensin', 'unit' => 'liter', 'minimum_stock' => 20, 'initial' => 60],
            ['name' => 'Kantong sampah', 'unit' => 'pack', 'minimum_stock' => 10, 'initial' => 32],
            ['name' => 'Serokan', 'unit' => 'pcs', 'minimum_stock' => 5, 'initial' => 14],
            ['name' => 'Sarung tangan', 'unit' => 'pasang', 'minimum_stock' => 12, 'initial' => 28],
            ['name' => 'Masker', 'unit' => 'box', 'minimum_stock' => 6, 'initial' => 18],
            ['name' => 'Cairan pembersih', 'unit' => 'liter', 'minimum_stock' => 10, 'initial' => 25],
            ['name' => 'Pel', 'unit' => 'pcs', 'minimum_stock' => 4, 'initial' => 11],
            ['name' => 'Ember', 'unit' => 'pcs', 'minimum_stock' => 4, 'initial' => 10],
            ['name' => 'Kanebo/lap', 'unit' => 'pcs', 'minimum_stock' => 8, 'initial' => 22],
            ['name' => 'Plastik sampah besar', 'unit' => 'roll', 'minimum_stock' => 8, 'initial' => 9],
        ])->mapWithKeys(function (array $row) {
            $item = InventoryItem::query()->updateOrCreate(
                ['name' => $row['name']],
                collect($row)->except('initial')->merge(['is_active' => true])->all()
            );

            InventoryTransaction::query()->updateOrCreate(
                [
                    'inventory_item_id' => $item->id,
                    'transaction_date' => now()->startOfMonth()->toDateString(),
                    'type' => InventoryTransaction::TYPE_IN,
                    'notes' => 'Saldo awal demo',
                ],
                ['quantity' => $row['initial'], 'source' => 'Gudang awal']
            );

            return [$row['name'] => $item];
        });

        $employees = Employee::query()->where('is_active', true)->orderBy('id')->limit(5)->get();
        $date = Carbon::now()->startOfMonth()->addDays(2);

        foreach ($employees as $index => $employee) {
            foreach (['Sapu', 'Sarung tangan', 'Masker', 'Kantong sampah'] as $offset => $name) {
                InventoryTransaction::query()->updateOrCreate(
                    [
                        'inventory_item_id' => $items[$name]->id,
                        'employee_id' => $employee->id,
                        'transaction_date' => $date->copy()->addDays($index)->toDateString(),
                        'type' => InventoryTransaction::TYPE_ALLOCATION,
                    ],
                    [
                        'quantity' => $offset === 3 ? 2 : 1,
                        'area' => $employee->area,
                        'source' => 'Koordinator',
                        'notes' => 'Alokasi demo ke '.$employee->area,
                    ]
                );
            }
        }

        foreach ([['Bensin', 18], ['Cairan pembersih', 8], ['Plastik sampah besar', 3]] as [$name, $quantity]) {
            InventoryTransaction::query()->updateOrCreate(
                [
                    'inventory_item_id' => $items[$name]->id,
                    'transaction_date' => $date->copy()->addDays(7)->toDateString(),
                    'type' => InventoryTransaction::TYPE_OUT,
                    'notes' => 'Pemakaian operasional demo',
                ],
                ['quantity' => $quantity, 'area' => 'Operasional Umum', 'source' => 'Lapangan']
            );
        }
    }
}
