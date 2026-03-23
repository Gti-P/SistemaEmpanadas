<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        // Totales generales del periodo
        $totalSales = Sale::whereBetween('sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])->count();
        $totalRevenue = Sale::whereBetween('sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])->sum('total');

        // Ventas por día
        $salesByDay = Sale::whereBetween('sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->selectRaw('DATE(sale_date) as date, COUNT(*) as count, SUM(total) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Ventas por tipo de producto (categoría)
        $salesByCategory = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->selectRaw('products.category, SUM(sale_items.quantity) as total_qty, SUM(sale_items.subtotal) as total_revenue')
            ->groupBy('products.category')
            ->get();

        // Top productos más vendidos
        $topProducts = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->selectRaw('products.name, products.category, SUM(sale_items.quantity) as total_qty, SUM(sale_items.subtotal) as total_revenue')
            ->groupBy('products.id', 'products.name', 'products.category')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // Ventas por tipo de cliente (mostrador vs registrado)
        $salesByClientType = Sale::join('clients', 'sales.client_id', '=', 'clients.id')
            ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->selectRaw('clients.is_counter_client, COUNT(*) as count, SUM(sales.total) as revenue')
            ->groupBy('clients.is_counter_client')
            ->get();

        // Ventas por ciudad
        $salesByCity = Sale::join('clients', 'sales.client_id', '=', 'clients.id')
            ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->whereNotNull('clients.city')
            ->where('clients.is_counter_client', false)
            ->selectRaw('clients.city, COUNT(*) as count, SUM(sales.total) as revenue')
            ->groupBy('clients.city')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        // Ventas por método de pago
        $salesByPayment = Sale::whereBetween('sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total) as revenue')
            ->groupBy('payment_method')
            ->get();

        return view('admin.reports.index', compact(
            'dateFrom', 'dateTo',
            'totalSales', 'totalRevenue',
            'salesByDay', 'salesByCategory', 'topProducts',
            'salesByClientType', 'salesByCity', 'salesByPayment'
        ));
    }
}
