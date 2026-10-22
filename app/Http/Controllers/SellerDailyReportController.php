<?php

namespace App\Http\Controllers;

use App\Http\Requests\SellerDailyReportRequest;
use App\Exceptions\InventoryException;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\Seller;
use App\Models\SellerDailyReport;
use App\Models\SellerDailyReportDetail;
use App\Services\InventoryService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Throwable;

class SellerDailyReportController extends Controller
{
    public function index()
    {
        $report = SellerDailyReport::with('seller', 'sellerDailyReportDetail:id,total_sales,seller_daily_report_id,user_id')->orderBy('report_date', 'desc')->get();

        $reports_group = $report->groupBy(function ($item) {
            return $item->report_date->format('Y-m-d');
        });

        return Inertia::render('DailyReport/Index', [
            'reports_group' => $reports_group,
            'can_edit' => Auth::user()?->hasRole('Administrador') ?? false,
        ]);
    }
    /*
    Se necesita realizar una vista en la cual se muestre un listado de los vendedores,
    la fecha del reporte, el total vendido por cada vendedor y al final, el total general de las ventas del día.
    Este reporte unicamente se debe filtrar por la report_date, debido a que unicamente se quiere mostrar el reporte
    correspondiente a la fecha en cuestión

    Datos que vamos a ocupar para el reporte:
    - Vendedor
    - Fecha del reporte
    - Total vendido por cada vendedor
    - Total general de ventas del día
    Para obtener el total vendido por cada vendedor, se debe sumar el total_sales de cada detalle
    Para obtener el total general de ventas del día, se debe sumar el total vendido por cada vendedor
    Se debe mostrar un mensaje de error si no se encuentra ningun reporte para la fecha.
*/

    public function reportDate($report_date)
    {
        $report = SellerDailyReport::with('seller', 'sellerDailyReportDetail:id,total_sales,seller_daily_report_id,user_id', 'sellerDailyReportDetail.user:id,name,surname')
            ->whereDate('report_date', $report_date)->orderBy('grand_total', 'asc')->get();

        $total_sale = $report->sum('grand_total');

        return Inertia::render('DailyReport/ReportDate', [
            'report_date' => $report_date,
            'total_sale' => $total_sale,
            'reports' => $report,
            'can_edit' => Auth::user()?->hasRole('Administrador') ?? false,
        ]);
    }

    public function create()
    {
        $daily_reports = new SellerDailyReport;
        $daily_report_details = new SellerDailyReportDetail;
        $products = Product::all();
        $sellers = Seller::all();

        return Inertia::render('DailyReport/Create', ['daily_reports' => $daily_reports, 'daily_report_details' => $daily_report_details, 'products' => $products, 'sellers' => $sellers]);
    }

    public function store(SellerDailyReportRequest $request, InventoryService $inventoryService)
    {
        try {
            DB::transaction(function () use ($request, $inventoryService) {
                $reportGroups = $request->input('seller_reports');

                if (empty($reportGroups) && $request->filled('seller_id') && ! empty($request->input('seller_daily_reports', []))) {
                    $reportGroups = [[
                        'seller_id' => $request->seller_id,
                        'products' => $request->input('seller_daily_reports', []),
                    ]];
                }

                foreach ($reportGroups ?? [] as $reportGroup) {
                    $morningCheckup = $reportGroup['morning_checkup'] ?? $request->morning_checkup;
                    $eveningCheckup = $reportGroup['evening_checkup'] ?? $request->evening_checkup;

                    $dailyReport = SellerDailyReport::create([
                        'report_date' => $request->report_date,
                        'morning_checkup' => $morningCheckup,
                        'evening_checkup' => $eveningCheckup,
                        'seller_id' => $reportGroup['seller_id'],
                    ]);

                    $grandTotal = 0;

                    foreach ($reportGroup['products'] ?? [] as $dailyReportProduct) {
                        $quantityOut = (int) ($dailyReportProduct['quantity_out'] ?? 0);
                        $quantityReturn = (int) ($dailyReportProduct['quantity_return'] ?? 0);
                        $wholesalePrice = (float) ($dailyReportProduct['wholesale_price'] ?? 0);

                        if ($quantityReturn > $quantityOut) {
                            throw ValidationException::withMessages([
                                'quantity_return' => 'La cantidad devuelta no puede ser mayor a la cantidad que el vendedor llevó',
                            ]);
                        }

                        $productSold = $quantityOut - $quantityReturn;
                        $lineTotal = $productSold * $wholesalePrice;
                        $grandTotal += $lineTotal;

                        $detail = SellerDailyReportDetail::create([
                            'quantity_out' => $quantityOut,
                            'quantity_return' => $quantityReturn,
                            'quantity_sold' => $productSold,
                            'total_sales' => $lineTotal,
                            'wholesale_price' => $wholesalePrice,
                            'seller_daily_report_id' => $dailyReport->id,
                            'product_id' => $dailyReportProduct['id'],
                            'user_id' => Auth::id(),
                        ]);

                        if ($quantityOut > 0) {
                            $inventoryService->commit(
                                productId: $detail->product_id,
                                quantity: $quantityOut,
                                referenceType: SellerDailyReportDetail::class,
                                referenceId: $detail->id,
                                movementKey: "daily-report-detail:{$detail->id}:commitment",
                                notes: "Compromiso del reporte diario {$dailyReport->id}.",
                            );
                        }
                    }

                    $dailyReport->update([
                        'grand_total' => $grandTotal,
                    ]);
                }
            });

            return redirect()->route('seller_daily_reports.index')->with('success', 'Registro creado exitosamente');
        } catch (Throwable $e) {
            report($e);

            if ($e instanceof ValidationException) {
                return back()
                    ->withInput()
                    ->withErrors($e->errors());
            }

            if ($e instanceof InventoryException) {
                return back()
                    ->withInput()
                    ->withErrors(['seller_reports' => $e->getMessage()]);
            }

            return back()
                ->withInput()
                ->withErrors(['seller_reports' => 'No fue posible guardar el reporte. Verifique los datos e inténtelo nuevamente.']);
        }
    }

    public function confirm(string $id, InventoryService $inventoryService)
    {
        try {
            DB::transaction(function () use ($id, $inventoryService): void {
                $report = SellerDailyReport::query()
                    ->with('sellerDailyReportDetail')
                    ->lockForUpdate()
                    ->findOrFail($id);

                if ($report->status === 'Confirmed') {
                    return;
                }

                if ($report->status === 'Cancelled') {
                    throw ValidationException::withMessages([
                        'status' => 'Un reporte cancelado no puede confirmarse.',
                    ]);
                }

                foreach ($report->sellerDailyReportDetail as $detail) {
                    $quantity = (int) $detail->quantity_sold;

                    if ($quantity > 0) {
                        $inventoryService->issueCommitted(
                            productId: $detail->product_id,
                            quantity: $quantity,
                            referenceType: SellerDailyReportDetail::class,
                            referenceId: $detail->id,
                            movementKey: "daily-report-detail:{$detail->id}:issue",
                            notes: "Confirmación del reporte diario {$report->id}.",
                        );
                    }

                    $returned = (int) $detail->quantity_return;

                    if ($returned > 0) {
                        $inventoryService->releaseCommitment(
                            productId: $detail->product_id,
                            quantity: $returned,
                            referenceType: SellerDailyReportDetail::class,
                            referenceId: $detail->id,
                            movementKey: "daily-report-detail:{$detail->id}:return-release",
                            notes: "Liberación de devoluciones del reporte diario {$report->id}.",
                        );
                    }
                }

                $report->update(['status' => 'Confirmed']);
            });

            return redirect()
                ->route('seller_daily_reports.index')
                ->with('success', 'Reporte confirmado y existencias actualizadas.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            report($e);

            return back()->with('error', 'No fue posible confirmar el reporte diario.');
        }
    }

    public function show(string $id)
    {
        $daily_report = SellerDailyReport::with(['seller', 'sellerDailyReportDetail'])->findOrFail($id);
        $daily_report_detail = SellerDailyReportDetail::with('product', 'user')->where('seller_daily_report_id', $daily_report->id)->get();
        $product = Product::all();
        $creator_user = $daily_report_detail->first()?->user;

        return Inertia::render('DailyReport/Show', ['daily_report' => $daily_report, 'daily_report_detail' => $daily_report_detail, 'product' => $product, 'creator_user' => $creator_user]);
    }

    public function edit(string $id)
    {
        try {
            $daily_report = SellerDailyReport::with('sellerDailyReportDetail')->findOrFail($id);

            if ($daily_report->status === 'Confirmed') {
                abort_unless(Auth::user()?->hasRole('Administrador'), 403);
            }

            $daily_report_details = SellerDailyReportDetail::with('product', 'user')->where('seller_daily_report_id', $daily_report->id)->get();
            $products = Product::all();
            $sellers = Seller::all();

            return Inertia::render('DailyReport/Edit', ['daily_report' => $daily_report, 'daily_report_details' => $daily_report_details, 'products' => $products, 'sellers' => $sellers]);
        } catch (Throwable $e) {
            return back()->with('error', 'Ocurrió un error al cargar el registro');
        }
    }

    public function update(SellerDailyReportRequest $request, string $id, InventoryService $inventoryService)
    {
        try {
            DB::transaction(function () use ($request, $id, $inventoryService) {

                $daily_reports = SellerDailyReport::with('sellerDailyReportDetail')->findOrFail($id);

                if ($daily_reports->status === 'Confirmed') {
                    abort_unless(Auth::user()?->hasRole('Administrador'), 403);
                    $this->reverseConfirmedReportMovements($daily_reports, $inventoryService);
                }

                foreach ($daily_reports->sellerDailyReportDetail as $detail) {
                    if ((int) $detail->quantity_out > 0) {
                        $inventoryService->releaseCommitment(
                            productId: $detail->product_id,
                            quantity: (int) $detail->quantity_out,
                            referenceType: SellerDailyReportDetail::class,
                            referenceId: $detail->id,
                            movementKey: "daily-report-detail:{$detail->id}:edit-release",
                            notes: "Liberación por edición del reporte diario {$daily_reports->id}.",
                        );
                    }
                }

                $daily_reports->update([
                    'report_date' => $request->report_date,
                    'morning_checkup' => $request->morning_checkup,
                    'evening_checkup' => $request->evening_checkup,
                    'seller_id' => $request->seller_id,
                ]);

                $daily_reports->sellerDailyReportDetail()->delete();

                $grand_total = 0;

                foreach ($request->input('seller_daily_reports', []) as $daily_report) {
                    $quantityOut = (int) ($daily_report['quantity_out'] ?? 0);
                    $quantityReturn = (int) ($daily_report['quantity_return'] ?? 0);
                    $wholesalePrice = (float) ($daily_report['wholesale_price'] ?? 0);

                    if ($quantityReturn > $quantityOut) {
                        throw ValidationException::withMessages([
                            'quantity_return' => 'La cantidad devuelta no puede ser mayor a la cantidad que el vendedor llevó',
                        ]);
                    }

                    $product_sold = $quantityOut - $quantityReturn;

                    $line_total = $product_sold * $wholesalePrice;
                    $grand_total += $line_total;

                    $detail = SellerDailyReportDetail::create([
                        'quantity_out' => $quantityOut,
                        'quantity_return' => $quantityReturn,
                        'quantity_sold' => $product_sold,
                        'total_sales' => $line_total,
                        'wholesale_price' => $wholesalePrice,
                        'seller_daily_report_id' => $daily_reports->id,
                        'product_id' => $daily_report['id'],
                        'user_id' => Auth::id(),
                    ]);

                    if ($quantityOut > 0) {
                        $inventoryService->commit(
                            productId: $detail->product_id,
                            quantity: $quantityOut,
                            referenceType: SellerDailyReportDetail::class,
                            referenceId: $detail->id,
                            movementKey: "daily-report-detail:{$detail->id}:commitment",
                            notes: "Compromiso del reporte diario {$daily_reports->id}.",
                        );
                    }
                }

                $daily_reports->update([
                    'grand_total' => $grand_total,
                    'status' => 'Draft',
                ]);
            });

            return redirect()->route('seller_daily_reports.index')->with('success', 'Registro actualizado exitosamente');
        } catch (Throwable $e) {
            report($e);

            if ($e instanceof ValidationException) {
                return back()
                    ->withInput()
                    ->withErrors($e->errors());
            }

            if ($e instanceof InventoryException) {
                return back()
                    ->withInput()
                    ->withErrors(['seller_daily_reports' => $e->getMessage()]);
            }

            return back()
                ->withInput()
                ->withErrors(['seller_daily_reports' => 'No fue posible actualizar el reporte. Verifique los datos e inténtelo nuevamente.']);
        }
    }

    public function destroy(string $id, InventoryService $inventoryService)
    {
        try {
            abort_unless(Auth::user()?->hasRole('Administrador'), 403);

            DB::transaction(function () use ($id, $inventoryService) {
                $daily_report_delete = SellerDailyReport::with('sellerDailyReportDetail')->findOrFail($id);

                if ($daily_report_delete->status === 'Confirmed') {
                    $this->reverseConfirmedReportMovements($daily_report_delete, $inventoryService);
                }

                foreach ($daily_report_delete->sellerDailyReportDetail as $detail) {
                    if ((int) $detail->quantity_out > 0) {
                        $inventoryService->releaseCommitment(
                            productId: $detail->product_id,
                            quantity: (int) $detail->quantity_out,
                            referenceType: SellerDailyReportDetail::class,
                            referenceId: $detail->id,
                            movementKey: "daily-report-detail:{$detail->id}:delete-release",
                            notes: "Liberación por eliminación del reporte diario {$daily_report_delete->id}.",
                        );
                    }
                }

                $daily_report_delete->sellerDailyReportDetail()->delete();
                $daily_report_delete->delete();
            });
        } catch (Throwable $e) {
            return back()->with('error', 'Ocurrió un error al eliminar el registro');
        }
    }

    private function reverseConfirmedReportMovements(
        SellerDailyReport $report,
        InventoryService $inventoryService,
    ): void {
        foreach ($report->sellerDailyReportDetail as $detail) {
            $movementKeys = [
                "daily-report-detail:{$detail->id}:issue",
                "daily-report-detail:{$detail->id}:return-release",
                "daily-report-detail:{$detail->id}:commitment",
            ];

            foreach ($movementKeys as $index => $movementKey) {
                $movement = InventoryTransaction::query()
                    ->where('movement_key', $movementKey)
                    ->first();

                if ($movement) {
                    $inventoryService->reverse(
                        transaction: $movement,
                        movementKey: "daily-report-detail:{$detail->id}:admin-reversal:{$index}",
                        notes: "Reversión administrativa del reporte diario {$report->id}.",
                    );
                }
            }
        }
    }
}
