<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
{
    public function index()
    {
        $items = InventoryItem::query()
            ->with('transactions')
            ->orderBy('name')
            ->get();

        $lowStockItems = $items->filter->is_low_stock;

        return view('inventories.index', compact('items', 'lowStockItems'));
    }

    public function create()
    {
        return view('inventories.create');
    }

    public function store(Request $request)
    {
        InventoryItem::create($this->validated($request));

        return redirect()->route('inventories.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(InventoryItem $inventory)
    {
        return view('inventories.edit', ['item' => $inventory]);
    }

    public function update(Request $request, InventoryItem $inventory)
    {
        $inventory->update($this->validated($request, $inventory));

        return redirect()->route('inventories.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(InventoryItem $inventory)
    {
        $inventory->update(['is_active' => false]);

        return redirect()->route('inventories.index')->with('success', 'Barang dinonaktifkan.');
    }

    private function validated(Request $request, ?InventoryItem $item = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:inventory_items,name'.($item ? ','.$item->id : '')],
            'unit' => ['required', 'string', 'max:30'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active', true)];
    }
}
