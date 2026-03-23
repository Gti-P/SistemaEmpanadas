@extends('layouts.app')

@section('content')
<div class="admin-layout">
    <aside class="sidebar">
        <div class="sidebar-title">Administración</div>
        <a href="{{ route('admin.products.index') }}"
           class="sidebar-link {{ request()->is('admin/products*') ? 'active' : '' }}">
            <i class="fas fa-box-open"></i> Gestión de Productos
        </a>
        <a href="{{ route('admin.clients.index') }}"
           class="sidebar-link {{ request()->is('admin/clients*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Gestión de Clientes
        </a>
        <a href="{{ route('admin.reports.index') }}"
           class="sidebar-link {{ request()->is('admin/reports*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i> Informes de Ventas
        </a>
        <hr class="divider" style="margin: 1rem 1.25rem;">
        <a href="{{ route('pos.index') }}" class="sidebar-link">
            <i class="fas fa-cash-register"></i> Ir al POS
        </a>
    </aside>
    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif
        @yield('admin-content')
    </main>
</div>
@endsection
