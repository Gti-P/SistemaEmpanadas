@extends('layouts.app')
@section('title', 'Recibo de Venta #' . $sale->id)

@section('content')
<div style="max-width:480px;margin:2rem auto;padding:0 1rem">
    <div class="card">
        <div class="card-header" style="justify-content:center;flex-direction:column;gap:0.25rem;text-align:center">
            <div style="font-size:2rem">🫓</div>
            <div class="card-title">RECIBO DE VENTA</div>
            <div style="font-size:0.8rem;color:var(--text-400)">#{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div class="card-body">
            <div style="display:flex;justify-content:space-between;margin-bottom:1rem;font-size:0.85rem">
                <div>
                    <div style="color:var(--text-400);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Cliente</div>
                    <div style="font-weight:700">{{ $sale->client->name }}</div>
                    @if(!$sale->client->is_counter_client)
                    <div style="color:var(--text-400);font-size:0.8rem">{{ $sale->client->document_type }}: {{ $sale->client->document_number }}</div>
                    @endif
                </div>
                <div style="text-align:right">
                    <div style="color:var(--text-400);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px">Fecha</div>
                    <div style="font-weight:700">{{ $sale->sale_date->format('d/m/Y') }}</div>
                    <div style="color:var(--text-400);font-size:0.8rem">{{ $sale->sale_date->format('h:i A') }}</div>
                </div>
            </div>

            <hr class="divider">

            <table style="width:100%;font-size:0.875rem">
                <thead>
                    <tr style="color:var(--text-400);font-size:0.75rem;text-transform:uppercase">
                        <th style="padding:0.4rem 0">Producto</th>
                        <th style="padding:0.4rem 0;text-align:center">Cant.</th>
                        <th style="padding:0.4rem 0;text-align:right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $item)
                    <tr>
                        <td style="padding:0.45rem 0">
                            <div style="font-weight:600">{{ $item->product->name }}</div>
                            <div style="color:var(--text-400);font-size:0.75rem">$ {{ number_format($item->unit_price, 0, ',', '.') }} c/u</div>
                        </td>
                        <td style="padding:0.45rem 0;text-align:center;font-weight:700">{{ $item->quantity }}</td>
                        <td style="padding:0.45rem 0;text-align:right;font-weight:700;color:var(--red-light)">$ {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <hr class="divider">

            <div style="display:flex;justify-content:space-between;align-items:center">
                <div>
                    <div style="font-size:0.75rem;color:var(--text-400);text-transform:uppercase;letter-spacing:0.5px">Pago</div>
                    <div style="font-weight:600;font-size:0.875rem">
                        @switch($sale->payment_method)
                            @case('cash') <i class="fas fa-money-bill"></i> Efectivo @break
                            @case('card') <i class="fas fa-credit-card"></i> Tarjeta @break
                            @case('transfer') <i class="fas fa-university"></i> Transferencia @break
                        @endswitch
                    </div>
                </div>
                <div style="text-align:right">
                    <div style="font-size:0.75rem;color:var(--text-400);text-transform:uppercase;letter-spacing:0.5px">Total</div>
                    <div style="font-family:'Bebas Neue',sans-serif;font-size:2rem;color:var(--red-light)">$ {{ number_format($sale->total, 0, ',', '.') }}</div>
                </div>
            </div>

            <hr class="divider">
            <div style="text-align:center;color:var(--text-400);font-size:0.8rem">¡Gracias por tu compra! 🫓</div>
        </div>
    </div>
    <div style="display:flex;gap:0.75rem;justify-content:center;margin-top:1rem">
        <a href="/pos" class="btn btn-primary"><i class="fas fa-cash-register"></i> Volver al POS</a>
        <button onclick="window.print()" class="btn btn-secondary"><i class="fas fa-print"></i> Imprimir</button>
    </div>
</div>
@endsection
