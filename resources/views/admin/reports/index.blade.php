@extends('layouts.admin')
@section('title', 'Informes de Ventas')

@push('styles')
<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.stat-card {
    background: var(--bg-800);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 1.25rem;
    display: flex; flex-direction: column; gap: 0.25rem;
}
.stat-label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-400); }
.stat-value { font-family: 'Bebas Neue', sans-serif; font-size: 2rem; color: var(--text-100); }
.stat-icon { font-size: 1.5rem; margin-bottom: 0.25rem; }
.reports-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
    gap: 1.25rem;
}
.chart-container { position: relative; height: 250px; }
.date-filter {
    display: flex; gap: 0.75rem; align-items: flex-end; flex-wrap: wrap;
    background: var(--bg-800); border: 1px solid var(--border);
    border-radius: 10px; padding: 1rem; margin-bottom: 1.5rem;
}
.date-filter .form-group { margin: 0; }
.legend-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 0.5rem 0; border-bottom: 1px solid var(--border);
    font-size: 0.875rem;
}
.legend-row:last-child { border-bottom: none; }
.legend-bar-wrap { flex: 1; margin: 0 0.75rem; }
.legend-bar { height: 6px; border-radius: 3px; transition: width 0.8s ease; }
</style>
@endpush

@section('admin-content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-chart-bar text-red"></i> Informes de Ventas</h1>
</div>

<!-- Date Filter -->
<form method="GET" action="{{ route('admin.reports.index') }}">
    <div class="date-filter">
        <div class="form-group">
            <label class="form-label">Desde</label>
            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
        </div>
        <div class="form-group">
            <label class="form-label">Hasta</label>
            <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary"><i class="fas fa-sync"></i> Este mes</a>
    </div>
</form>

<!-- Stats Cards -->
@php
    $counterSales = $salesByClientType->firstWhere('is_counter_client', 1);
    $registeredSales = $salesByClientType->firstWhere('is_counter_client', 0);
    $totalSalesCount = $salesByClientType->sum('count');
@endphp

<div class="stats-grid">
    <div class="stat-card" style="border-top:3px solid var(--red)">
        <div class="stat-icon">🧾</div>
        <div class="stat-label">Total Ventas</div>
        <div class="stat-value">{{ number_format($totalSales) }}</div>
    </div>
    <div class="stat-card" style="border-top:3px solid #22c55e">
        <div class="stat-icon">💰</div>
        <div class="stat-label">Ingresos Totales</div>
        <div class="stat-value" style="color:var(--red-light)">$ {{ number_format($totalRevenue, 0, ',', '.') }}</div>
    </div>
    <div class="stat-card" style="border-top:3px solid #3b82f6">
        <div class="stat-icon">🏪</div>
        <div class="stat-label">Ventas Mostrador</div>
        <div class="stat-value">{{ number_format($counterSales->count ?? 0) }}</div>
        @if($totalSalesCount > 0)
        <div style="font-size:0.8rem;color:var(--text-400)">{{ round(($counterSales->count ?? 0) / $totalSalesCount * 100, 1) }}% del total</div>
        @endif
    </div>
    <div class="stat-card" style="border-top:3px solid #f59e0b">
        <div class="stat-icon">👤</div>
        <div class="stat-label">Ventas con Cliente</div>
        <div class="stat-value">{{ number_format($registeredSales->count ?? 0) }}</div>
        @if($totalSalesCount > 0)
        <div style="font-size:0.8rem;color:var(--text-400)">{{ round(($registeredSales->count ?? 0) / $totalSalesCount * 100, 1) }}% del total</div>
        @endif
    </div>
    <div class="stat-card" style="border-top:3px solid #a78bfa">
        <div class="stat-icon">📊</div>
        <div class="stat-label">Ticket Promedio</div>
        <div class="stat-value" style="color:var(--red-light)">$ {{ $totalSales > 0 ? number_format($totalRevenue / $totalSales, 0, ',', '.') : '0' }}</div>
    </div>
</div>

<!-- Charts Grid -->
<div class="reports-grid">

    <!-- Ventas por día -->
    <div class="card">
        <div class="card-header"><span class="card-title">📈 Ingresos por Día</span></div>
        <div class="card-body"><div class="chart-container"><canvas id="chartByDay"></canvas></div></div>
    </div>

    <!-- Ventas por categoría -->
    <div class="card">
        <div class="card-header"><span class="card-title">🥧 Ventas por Categoría</span></div>
        <div class="card-body">
            <div class="chart-container"><canvas id="chartByCategory"></canvas></div>
        </div>
    </div>

    <!-- Tipo de cliente -->
    <div class="card">
        <div class="card-header"><span class="card-title">👥 Tipo de Cliente</span></div>
        <div class="card-body">
            <div class="chart-container"><canvas id="chartClientType"></canvas></div>
        </div>
    </div>

    <!-- Método de pago -->
    <div class="card">
        <div class="card-header"><span class="card-title">💳 Método de Pago</span></div>
        <div class="card-body">
            <div class="chart-container"><canvas id="chartPayment"></canvas></div>
        </div>
    </div>

    <!-- Top Productos -->
    <div class="card">
        <div class="card-header"><span class="card-title">🏆 Top Productos Vendidos</span></div>
        <div class="card-body">
            @php $maxQty = $topProducts->max('total_qty') ?: 1; @endphp
            @forelse($topProducts as $p)
            <div class="legend-row">
                <div style="min-width:140px;font-weight:600;font-size:0.8rem">{{ Str::limit($p->name, 20) }}</div>
                <div class="legend-bar-wrap">
                    <div class="legend-bar" style="width:{{ ($p->total_qty / $maxQty) * 100 }}%;background:{{ $p->category === 'empanada' ? '#f59e0b' : '#a78bfa' }}"></div>
                </div>
                <div style="min-width:70px;text-align:right;font-weight:700;font-size:0.85rem">{{ $p->total_qty }} uds</div>
                <div style="min-width:90px;text-align:right;font-size:0.8rem;color:var(--red-light);font-weight:700">$ {{ number_format($p->total_revenue, 0, ',', '.') }}</div>
            </div>
            @empty
            <div style="text-align:center;padding:2rem;color:var(--text-400)">Sin datos en este periodo</div>
            @endforelse
        </div>
    </div>

    <!-- Ciudades -->
    <div class="card">
        <div class="card-header"><span class="card-title">📍 Ventas por Ciudad</span></div>
        <div class="card-body">
            @php $maxCityRev = $salesByCity->max('revenue') ?: 1; @endphp
            @forelse($salesByCity as $city)
            <div class="legend-row">
                <div style="min-width:120px;font-weight:600;font-size:0.85rem">{{ $city->city }}</div>
                <div class="legend-bar-wrap">
                    <div class="legend-bar" style="width:{{ ($city->revenue / $maxCityRev) * 100 }}%;background:var(--red)"></div>
                </div>
                <div style="min-width:50px;text-align:right;font-weight:700;font-size:0.85rem;color:var(--text-400)">{{ $city->count }}</div>
                <div style="min-width:90px;text-align:right;font-size:0.8rem;color:var(--red-light);font-weight:700">$ {{ number_format($city->revenue, 0, ',', '.') }}</div>
            </div>
            @empty
            <div style="text-align:center;padding:2rem;color:var(--text-400)">Sin datos de ciudades en este periodo</div>
            @endforelse
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
Chart.defaults.color = '#888';
Chart.defaults.borderColor = '#333';
Chart.defaults.font.family = 'Nunito';

// Ventas por día
new Chart(document.getElementById('chartByDay'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($salesByDay->pluck('date')->map(fn($d) => date('d/m', strtotime($d)))) !!},
        datasets: [{
            label: 'Ingresos',
            data: {!! json_encode($salesByDay->pluck('revenue')) !!},
            backgroundColor: 'rgba(230,48,18,0.7)',
            borderColor: '#e63012',
            borderWidth: 1,
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { ticks: { callback: v => '$ ' + new Intl.NumberFormat('es-CO').format(v) } }
        }
    }
});

// Ventas por categoría
const catData = {!! json_encode($salesByCategory) !!};
new Chart(document.getElementById('chartByCategory'), {
    type: 'doughnut',
    data: {
        labels: catData.map(c => c.category === 'empanada' ? '🫓 Empanadas' : '🥔 Papas Rellenas'),
        datasets: [{
            data: catData.map(c => c.total_qty),
            backgroundColor: ['#f59e0b', '#a78bfa'],
            borderColor: '#1a1a1a', borderWidth: 3,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw} unidades` } }
        }
    }
});

// Tipo de cliente
const clientTypeData = {!! json_encode($salesByClientType) !!};
new Chart(document.getElementById('chartClientType'), {
    type: 'pie',
    data: {
        labels: clientTypeData.map(c => c.is_counter_client ? '🏪 Mostrador' : '👤 Registrado'),
        datasets: [{
            data: clientTypeData.map(c => c.count),
            backgroundColor: ['#3b82f6', '#e63012'],
            borderColor: '#1a1a1a', borderWidth: 3,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw} ventas` } }
        }
    }
});

// Método de pago
const payData = {!! json_encode($salesByPayment) !!};
const payLabels = { cash: '💵 Efectivo', card: '💳 Tarjeta', transfer: '🏦 Transferencia' };
new Chart(document.getElementById('chartPayment'), {
    type: 'bar',
    data: {
        labels: payData.map(p => payLabels[p.payment_method] || p.payment_method),
        datasets: [{
            label: 'Ventas',
            data: payData.map(p => p.count),
            backgroundColor: ['#22c55e', '#3b82f6', '#f59e0b'],
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { ticks: { stepSize: 1 } } }
    }
});
</script>
@endpush
