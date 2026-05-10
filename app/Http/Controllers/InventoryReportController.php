<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;

class InventoryReportController extends Controller
{
    public function usage(Request $request)
    {
        $month = (int) ($request->month ?? now()->month);
        $year = (int) ($request->year ?? now()->year);
        $itemId = $request->filled('item_id') ? (int) $request->item_id : null;
        $area = $request->filled('area') ? $request->area : null;

        $items = InventoryItem::query()
            ->with('transactions')
            ->when($itemId, fn ($query) => $query->whereKey($itemId))
            ->orderBy('name')
            ->get()
            ->map(function (InventoryItem $item) use ($month, $year, $area) {
                $periodTransactions = $item->transactions
                    ->filter(fn (InventoryTransaction $transaction) => $transaction->transaction_date->month === $month && $transaction->transaction_date->year === $year)
                    ->when($area, fn ($transactions) => $transactions->where('area', $area));

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'unit' => $item->unit,
                    'stock_in' => $periodTransactions->where('type', InventoryTransaction::TYPE_IN)->sum('quantity'),
                    'stock_out' => $periodTransactions->where('type', InventoryTransaction::TYPE_OUT)->sum('quantity'),
                    'allocated' => $periodTransactions->where('type', InventoryTransaction::TYPE_ALLOCATION)->sum('quantity'),
                    'current_stock' => $item->current_stock,
                    'minimum_stock' => $item->minimum_stock,
                    'is_low_stock' => $item->is_low_stock,
                ];
            });

        return view('inventories.reports.usage', [
            'items' => $items,
            'month' => $month,
            'year' => $year,
            'itemId' => $itemId,
            'area' => $area,
            'masterItems' => InventoryItem::query()->where('is_active', true)->orderBy('name')->get(),
            'areas' => InventoryTransaction::query()->whereNotNull('area')->distinct()->orderBy('area')->pluck('area'),
            'years' => range(now()->year - 2, now()->year + 1),
            'typeLabels' => InventoryTransaction::typeLabels(),
        ]);
    }
}
