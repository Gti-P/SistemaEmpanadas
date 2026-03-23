@extends('layouts.admin')
@section('title', 'Gestión de Clientes')

@section('admin-content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-users text-red"></i> Gestión de Clientes</h1>
    <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Nuevo Cliente
    </a>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">Clientes Registrados</span>
        <span class="badge badge-gray">{{ $clients->total() }} clientes</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Documento</th>
                    <th>Nombre</th>
                    <th>Ciudad</th>
                    <th>Teléfono</th>
                    <th>Ventas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                <tr>
                    <td>
                        <span class="badge badge-gray">{{ $client->document_type }}</span>
                        <span style="font-size:0.875rem;margin-left:0.35rem">{{ $client->document_number }}</span>
                    </td>
                    <td style="font-weight:700">{{ $client->name }}</td>
                    <td style="color:var(--text-400)">{{ $client->city ?? '—' }}</td>
                    <td style="color:var(--text-400)">{{ $client->phone ?? '—' }}</td>
                    <td>
                        <span class="badge badge-blue">{{ $client->sales()->count() }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.4rem">
                            <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            @if($client->sales()->count() === 0)
                            <form method="POST" action="{{ route('admin.clients.destroy', $client) }}"
                                  onsubmit="return confirm('¿Eliminar este cliente?')">
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
                        No hay clientes registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($clients->hasPages())
    <div class="card-body">
        {{ $clients->links() }}
    </div>
    @endif
</div>
@endsection
