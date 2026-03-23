@extends('layouts.admin')
@section('title', 'Gestión de Productos')

@section('admin-content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-box-open text-red"></i> Gestión de Productos</h1>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nuevo Producto
    </a>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">Productos Registrados</span>
        <span class="badge badge-gray">{{ $products->total() }} productos</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td style="color:var(--text-400)">{{ $product->id }}</td>
                    <td>
                        <div style="font-weight:700">{{ $product->name }}</div>
                        @if($product->description)
                        <div style="font-size:0.78rem;color:var(--text-400)">{{ Str::limit($product->description, 60) }}</div>
                        @endif
                    </td>
                    <td>
                        @if($product->category === 'empanada')
                            <span class="badge badge-yellow">🫓 Empanada</span>
                        @else
                            <span class="badge badge-blue">🥔 Papa Rellena</span>
                        @endif
                    </td>
                    <td style="font-family:'Bebas Neue',sans-serif;font-size:1.1rem;color:var(--red-light)">
                        $ {{ number_format($product->price, 0, ',', '.') }}
                    </td>
                    <td>
                        @if($product->active)
                            <span class="badge badge-green"><i class="fas fa-circle" style="font-size:0.5rem"></i> Activo</span>
                        @else
                            <span class="badge badge-gray"><i class="fas fa-circle" style="font-size:0.5rem"></i> Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:0.4rem">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            @if(!$product->hasSales())
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                  onsubmit="return confirm('¿Eliminar este producto?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @else
                            <button class="btn btn-secondary btn-sm" disabled title="Tiene ventas registradas">
                                <i class="fas fa-lock"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:2rem;color:var(--text-400)">
                        No hay productos registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div class="card-body">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
