<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Seller;
use App\Models\SellerDailyReport;
use App\Models\SellerDailyReportDetail;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SellerDailyReportRequest;
use Illuminate\Validation\ValidationException;
use Throwable;

class SellerDailyReportController extends Controller
{

    public function index()
    {
        $daily_report = SellerDailyReport::with(['seller', 'sellerDailyReportDetail:id,total_sales,seller_daily_report_id,user_id'])->get();

        return Inertia::render('DailyReport/Index', ['daily_report' => $daily_report]);
    }


    public function create()
    {
        $daily_reports = new SellerDailyReport();
        $daily_report_details = new SellerDailyReportDetail();
        $products = Product::all();
        $sellers = Seller::all();

        return Inertia::render('DailyReport/Create', ['daily_reports' => $daily_reports, 'daily_report_details' => $daily_report_details, 'products' => $products, 'sellers' => $sellers]);
    }

    public function store(SellerDailyReportRequest $request) {
        try {
            DB::transaction(function () use ($request) {
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

                        SellerDailyReportDetail::create([
                            'quantity_out' => $quantityOut,
                            'quantity_return' => $quantityReturn,
                            'quantity_sold' => $productSold,
                            'total_sales' => $lineTotal,
                            'wholesale_price' => $wholesalePrice,
                            'seller_daily_report_id' => $dailyReport->id,
                            'product_id' => $dailyReportProduct['id'],
                            'user_id' => Auth::id(),
                        ]);
                    }

                    $dailyReport->update([
                        'grand_total' => $grandTotal,
                    ]);
                }
            });
            return redirect()->route('seller_daily_reports.index')->with('success', 'Registro creado exitosamente');
        } catch (Throwable $e) {

            dd($e->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error al crear el registro');
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
            $daily_report_details = SellerDailyReportDetail::with('product', 'user')->where('seller_daily_report_id', $daily_report->id)->get();
            $products = Product::all();
            $sellers = Seller::all();

            return Inertia::render('DailyReport/Edit', ['daily_report' => $daily_report, 'daily_report_details' => $daily_report_details, 'products' => $products, 'sellers' => $sellers]);
        } catch (Throwable $e) {
            return back()->with('error', 'Ocurrió un error al cargar el registro');
        }
    }


    public function update(SellerDailyReportRequest $request, string $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {

                $daily_reports = SellerDailyReport::findOrFail($id);

                $daily_reports->update([
                    'report_date' => today(),
                    'morning_checkup' => $request->morning_checkup,
                    'evening_checkup' => $request->evening_checkup,
                    'seller_id' => $request->seller_id
                ]);

                $daily_reports->sellerDailyReportDetail()->delete();

                $grand_total = 0;

                foreach ($request->input('seller_daily_reports', []) as $daily_report) {
                    $quantityOut = (int) ($daily_report['quantity_out'] ?? 0);
                    $quantityReturn = (int) ($daily_report['quantity_return'] ?? 0);
                    $wholesalePrice = (float) ($daily_report['wholesale_price'] ?? 0);

                    if ($quantityReturn > $quantityOut) {
                        throw ValidationException::withMessages([
                            'quantity_return' => 'La cantidad devuelta no puede ser mayor a la cantidad que el vendedor llevó'
                        ]);
                    }

                    $product_sold = $quantityOut - $quantityReturn;

                    $line_total = $product_sold * $wholesalePrice;
                    $grand_total += $line_total;

                    SellerDailyReportDetail::create([
                        'quantity_out' => $quantityOut,
                        'quantity_return' => $quantityReturn,
                        'quantity_sold' => $product_sold,
                        'total_sales' => $line_total,
                        'wholesale_price' => $wholesalePrice,
                        'seller_daily_report_id' => $daily_reports->id,
                        'product_id' => $daily_report['id'],
                        'user_id' => Auth::id(),
                    ]);
                }

                $daily_reports->update([
                    'grand_total' => $grand_total
                ]);
            });
            return redirect()->route('seller_daily_reports.index')->with('success', 'Registro actualizado exitosamente');
        } catch (Throwable $e) {

            dd($e->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error al actualizar el registro');
        }
    }


    public function destroy(string $id)
    {
        try {
            DB::transaction(function () use ($id) {
                $daily_report_delete = SellerDailyReport::findOrFail($id);
                $daily_report_delete->sellerDailyReportDetail()->delete();
                $daily_report_delete->delete();
            });
        } catch (Throwable $e) {
            return back()->with('error', 'Ocurrió un error al eliminar el registro');
        }
    }
}
