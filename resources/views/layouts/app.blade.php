<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Empanadas POS') - Sistema de Ventas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --red: #e63012;
            --red-dark: #b5230d;
            --red-light: #ff4d2e;
            --bg-900: #0f0f0f;
            --bg-800: #1a1a1a;
            --bg-700: #242424;
            --bg-600: #2e2e2e;
            --bg-500: #3a3a3a;
            --text-100: #f5f5f5;
            --text-200: #d4d4d4;
            --text-400: #888;
            --border: #333;
            --success: #22c55e;
            --warning: #f59e0b;
            --info: #3b82f6;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Nunito', sans-serif;
            background: var(--bg-900);
            color: var(--text-100);
            min-height: 100vh;
        }
        a { color: inherit; text-decoration: none; }

        /* NAVBAR */
        .navbar {
            background: var(--bg-800);
            border-bottom: 2px solid var(--red);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            height: 60px;
            position: sticky; top: 0; z-index: 100;
        }
        .navbar-brand {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.6rem;
            color: var(--red);
            letter-spacing: 1px;
            white-space: nowrap;
        }
        .navbar-brand span { color: var(--text-100); }
        .navbar-nav {
            display: flex; gap: 0.25rem; margin-left: auto;
        }
        .nav-link {
            padding: 0.45rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-200);
            transition: all 0.2s;
            display: flex; align-items: center; gap: 0.4rem;
        }
        .nav-link:hover, .nav-link.active {
            background: var(--red);
            color: #fff;
        }

        /* SIDEBAR ADMIN */
        .admin-layout {
            display: flex;
            min-height: calc(100vh - 60px);
        }
        .sidebar {
            width: 230px;
            background: var(--bg-800);
            border-right: 1px solid var(--border);
            padding: 1.5rem 0;
            flex-shrink: 0;
        }
        .sidebar-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 0.75rem;
            letter-spacing: 2px;
            color: var(--text-400);
            padding: 0 1.25rem 0.75rem;
            text-transform: uppercase;
        }
        .sidebar-link {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.7rem 1.25rem;
            color: var(--text-200);
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(230,48,18,0.12);
            color: var(--red-light);
            border-left-color: var(--red);
        }
        .sidebar-link i { width: 18px; text-align: center; }
        .main-content { flex: 1; padding: 2rem; overflow-x: auto; }

        /* CARDS */
        .card {
            background: var(--bg-800);
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
        }
        .card-header {
            background: var(--bg-700);
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            gap: 1rem;
        }
        .card-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.2rem;
            letter-spacing: 1px;
            color: var(--text-100);
        }
        .card-body { padding: 1.25rem; }

        /* BUTTONS */
        .btn {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.5rem 1.1rem;
            border-radius: 6px;
            font-family: 'Nunito', sans-serif;
            font-weight: 700;
            font-size: 0.875rem;
            cursor: pointer;
            border: none;
            transition: all 0.18s;
            white-space: nowrap;
        }
        .btn-primary { background: var(--red); color: #fff; }
        .btn-primary:hover { background: var(--red-dark); }
        .btn-secondary { background: var(--bg-600); color: var(--text-200); }
        .btn-secondary:hover { background: var(--bg-500); }
        .btn-success { background: var(--success); color: #fff; }
        .btn-success:hover { background: #16a34a; }
        .btn-warning { background: var(--warning); color: #000; }
        .btn-warning:hover { background: #d97706; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.8rem; }
        .btn-lg { padding: 0.75rem 1.5rem; font-size: 1rem; }
        .btn-block { width: 100%; justify-content: center; }

        /* FORMS */
        .form-group { margin-bottom: 1.1rem; }
        .form-label {
            display: block; margin-bottom: 0.4rem;
            font-weight: 600; font-size: 0.875rem; color: var(--text-200);
        }
        .form-control, .form-select {
            width: 100%;
            padding: 0.55rem 0.85rem;
            background: var(--bg-700);
            border: 1px solid var(--border);
            border-radius: 6px;
            color: var(--text-100);
            font-family: 'Nunito', sans-serif;
            font-size: 0.9rem;
            transition: border-color 0.2s;
        }
        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--red);
            box-shadow: 0 0 0 3px rgba(230,48,18,0.15);
        }
        .form-control::placeholder { color: var(--text-400); }
        textarea.form-control { resize: vertical; min-height: 80px; }
        .form-select option { background: var(--bg-700); }
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
        .invalid-feedback { color: #f87171; font-size: 0.8rem; margin-top: 0.25rem; }
        .is-invalid { border-color: #f87171 !important; }

        /* TABLE */
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: var(--bg-700); }
        th {
            padding: 0.75rem 1rem; text-align: left;
            font-size: 0.8rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.5px;
            color: var(--text-400);
            border-bottom: 1px solid var(--border);
        }
        td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border);
            font-size: 0.9rem;
        }
        tbody tr:hover { background: rgba(255,255,255,0.03); }
        tbody tr:last-child td { border-bottom: none; }

        /* BADGES */
        .badge {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .badge-red { background: rgba(230,48,18,0.2); color: var(--red-light); }
        .badge-green { background: rgba(34,197,94,0.2); color: #4ade80; }
        .badge-yellow { background: rgba(245,158,11,0.2); color: #fbbf24; }
        .badge-blue { background: rgba(59,130,246,0.2); color: #60a5fa; }
        .badge-gray { background: var(--bg-600); color: var(--text-400); }

        /* ALERTS */
        .alert {
            padding: 0.85rem 1.1rem;
            border-radius: 8px;
            margin-bottom: 1.25rem;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex; align-items: center; gap: 0.6rem;
        }
        .alert-success { background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); color: #4ade80; }
        .alert-error { background: rgba(220,38,38,0.15); border: 1px solid rgba(220,38,38,0.3); color: #f87171; }
        .alert-warning { background: rgba(245,158,11,0.15); border: 1px solid rgba(245,158,11,0.3); color: #fbbf24; }

        /* PAGINATION */
        .pagination { display: flex; gap: 0.35rem; justify-content: center; padding: 1rem 0 0; flex-wrap: wrap; }
        .pagination a, .pagination span {
            padding: 0.4rem 0.75rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            background: var(--bg-700);
            color: var(--text-200);
            border: 1px solid var(--border);
            transition: all 0.2s;
        }
        .pagination a:hover { background: var(--red); color: #fff; border-color: var(--red); }
        .pagination .active span { background: var(--red); color: #fff; border-color: var(--red); }

        /* MODAL */
        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.75); z-index: 1000;
            align-items: center; justify-content: center;
        }
        .modal-overlay.open { display: flex; }
        .modal {
            background: var(--bg-800);
            border: 1px solid var(--border);
            border-radius: 12px;
            width: 90%; max-width: 520px;
            max-height: 90vh; overflow-y: auto;
        }
        .modal-header {
            padding: 1.1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .modal-title { font-family: 'Bebas Neue', sans-serif; font-size: 1.2rem; letter-spacing: 1px; }
        .modal-close { background: none; border: none; color: var(--text-400); font-size: 1.2rem; cursor: pointer; padding: 0.25rem; }
        .modal-close:hover { color: var(--red); }
        .modal-body { padding: 1.25rem; }
        .modal-footer { padding: 1rem 1.25rem; border-top: 1px solid var(--border); display: flex; gap: 0.75rem; justify-content: flex-end; }

        /* MISC */
        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.5rem; gap: 1rem;
        }
        .page-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.8rem;
            letter-spacing: 1px;
        }
        .divider { border: none; border-top: 1px solid var(--border); margin: 1.25rem 0; }
        .text-red { color: var(--red-light); }
        .text-muted { color: var(--text-400); }
        .text-success { color: #4ade80; }
        .text-warning { color: #fbbf24; }
        .fw-bold { font-weight: 700; }
        .mt-1 { margin-top: 0.5rem; }
        .mt-2 { margin-top: 1rem; }
        .mb-1 { margin-bottom: 0.5rem; }
        .mb-2 { margin-bottom: 1rem; }
        .d-flex { display: flex; }
        .align-center { align-items: center; }
        .gap-1 { gap: 0.5rem; }
        .gap-2 { gap: 1rem; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .w-100 { width: 100%; }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar">
    <div class="navbar-brand">🫓 <span>Empanadas</span> POS</div>
    <div class="navbar-nav">
        <a href="/pos" class="nav-link {{ request()->is('pos*') ? 'active' : '' }}">
            <i class="fas fa-cash-register"></i> Punto de Venta
        </a>
        <a href="/admin" class="nav-link {{ request()->is('admin*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i> Administración
        </a>
    </div>
</nav>

@yield('content')

@stack('scripts')
</body>
</html>
