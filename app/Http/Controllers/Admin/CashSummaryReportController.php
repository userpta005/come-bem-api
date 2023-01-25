<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cashier;
use App\Models\OpenCashier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CashSummaryReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:cash-summary-report_view', ['only' => ['cashSummary']]);
    }

    
    public function __invoke(Request $request)
    {
        if ($request->type == 1) {
            $data = OpenCashier::query()
                ->whereDate('date_operation', $request->date_operation)
                ->where('store_id', session('store')['id'])
                ->with(['cashMovements' => function ($query) use ($request) {
                    $query->select(
                        'cash_movements.open_cashier_id',
                        'cash_movements.movement_type_id',
                        'cash_movements.payment_method_id',
                        DB::raw('sum(amount) as amount')
                    )
                        ->whereDate('date_operation', $request->date_operation)
                        ->groupBy(
                            'cash_movements.open_cashier_id',
                            'cash_movements.movement_type_id',
                            'cash_movements.payment_method_id'
                        )
                        ->with('movementType', 'paymentMethod');
                }, 'store', 'cashier', 'user.people'])
                ->whereHas('cashier', function ($query) use ($request) {
                    $query->when(!empty($request->cashier_id), function ($q) use ($request) {
                        $q->where('id', $request->cashier_id);
                    });
                })
                ->whereHas('cashMovements', function ($query) use ($request) {
                    $query->whereDate('date_operation', $request->date_operation);
                })
                ->get()
                ->map(function ($item) {
                    $item->total_entries = $item->cashMovements->filter(fn($cashMovements) => $cashMovements->movementType->class == \App\Enums\MovementClass::ENTRY)->sum('amount');
                    $item->total_outgoing = $item->cashMovements->filter(fn($cashMovements) => $cashMovements->movementType->class == \App\Enums\MovementClass::OUTGOING)->sum('amount');
                    return $item;
                });

            return view('reports.cash-summary.synthetic-report', compact('data'));
        } else {

            $data = OpenCashier::query()
                ->with('cashMovements', 'store', 'cashier')
                ->whereDate('date_operation', $request->date_operation)
                ->where('store_id', session('store')['id'])
                ->with(['cashMovements' => function ($query) use ($request) {
                    $query->whereDate('date_operation', $request->date_operation)
                        ->with('movementType', 'paymentMethod');
                }, 'store', 'cashier', 'user.people'])
                ->whereHas('cashier', function ($query) use ($request) {
                    $query->when(!empty($request->cashier_id), function ($q) use ($request) {
                        $q->where('id', $request->cashier_id);
                    });
                })
                ->whereHas('cashMovements', function ($query) use ($request) {
                    $query->whereDate('date_operation', $request->date_operation);
                })
                ->get()
                ->map(function ($item) {
                    $item->total_entries = $item->cashMovements->filter(fn($cashMovements) => $cashMovements->movementType->class == \App\Enums\MovementClass::ENTRY)->sum('amount');
                    $item->total_outgoing = $item->cashMovements->filter(fn($cashMovements) => $cashMovements->movementType->class == \App\Enums\MovementClass::OUTGOING)->sum('amount');
                    return $item;
                });

            return view('reports.cash-summary.report', compact('data'));
        }

    }
}