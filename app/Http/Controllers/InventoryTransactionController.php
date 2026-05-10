<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InventoryTransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = InventoryTransaction::query()
            ->with(['item', 'employee'])
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->type))
            ->when($request->filled('item_id'), fn ($query) => $query->where('inventory_item_id', $request->item_id))
            ->when($request->filled('area'), fn ($query) => $query->where('area', $request->area))
            ->when($request->filled('month'), fn ($query) => $query->whereMonth('transaction_date', (int) $request->month))
            ->when($request->filled('year'), fn ($query) => $query->whereYear('transaction_date', (int) $request->year))
            ->latest('transaction_date')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('inventories.transactions.index', [
            'transactions' => $transactions,
            'items' => InventoryItem::query()->where('is_active', true)->orderBy('name')->get(),
            'areas' => Employee::query()->whereNotNull('area')->distinct()->orderBy('area')->pluck('area'),
            'typeLabels' => InventoryTransaction::typeLabels(),
        ]);
    }

    public function create()
    {
        return view('inventories.transactions.create', [
            'items' => InventoryItem::query()->where('is_active', true)->orderBy('name')->get(),
            'employees' => Employee::query()->where('is_active', true)->orderBy('name')->get(),
            'typeLabels' => InventoryTransaction::typeLabels(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'inventory_item_id' => ['required', 'exists:inventory_items,id'],
            'transaction_date' => ['required', 'date'],
            'type' => ['required', Rule::in(array_keys(InventoryTransaction::typeLabels()))],
            'quantity' => ['required', 'integer', 'min:1'],
            'employee_id' => ['nullable', 'exists:employees,id'],
            'area' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $item = InventoryItem::query()->with('transactions')->findOrFail($data['inventory_item_id']);
        if (in_array($data['type'], [InventoryTransaction::TYPE_OUT, InventoryTransaction::TYPE_ALLOCATION], true) && $data['quantity'] > $item->current_stock) {
            return back()->withInput()->with('error', 'Stok tidak mencukupi untuk transaksi ini.');
        }

        InventoryTransaction::create($data);

        return redirect()->route('inventory-transactions.index')->with('success', 'Transaksi inventaris berhasil dicatat.');
    }
}
