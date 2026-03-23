<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::where('active', true)->orderBy('category')->orderBy('name')->get();
        $counterClient = Client::counterClient();
        return view('pos.index', compact('products', 'counterClient'));
    }

    public function searchClient(Request $request)
    {
        $query = $request->get('q', '');
        $clients = Client::where('is_counter_client', false)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('document_number', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'document_number', 'document_type', 'city', 'phone']);

        return response()->json($clients);
    }

    public function storeSale(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card,transfer',
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;
                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                ];
            }

            $sale = Sale::create([
                'client_id' => $request->client_id,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'sale_date' => now(),
            ]);

            foreach ($itemsData as $item) {
                $item['sale_id'] = $sale->id;
                SaleItem::create($item);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'sale_id' => $sale->id,
                'total' => $total,
                'message' => 'Venta registrada exitosamente',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error al registrar la venta: ' . $e->getMessage()], 500);
        }
    }

    public function storeClient(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:CC,CE,NIT,PP,TI',
            'document_number' => 'required|string|max:30|unique:clients,document_number',
            'name' => 'required|string|max:150',
            'address' => 'nullable|string|max:200',
            'city' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $client = Client::create([
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'phone' => $request->phone,
            'is_counter_client' => false,
        ]);

        return response()->json(['success' => true, 'client' => $client]);
    }

    public function receipt(Sale $sale)
    {
        $sale->load('client', 'items.product');
        return view('pos.receipt', compact('sale'));
    }
}
