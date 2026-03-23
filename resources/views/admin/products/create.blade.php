@extends('layouts.admin')
@section('title', 'Nuevo Producto')

@section('admin-content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-plus-circle text-red"></i> Nuevo Producto</h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="card" style="max-width:600px">
    <div class="card-header">
        <span class="card-title">Información del Producto</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.products.store') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="Ej: Empanada de Pollo" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Categoría *</label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                        <option value="">Seleccionar...</option>
                        <option value="empanada" {{ old('category') == 'empanada' ? 'selected' : '' }}>🫓 Empanada</option>
                        <option value="papa_rellena" {{ old('category') == 'papa_rellena' ? 'selected' : '' }}>🥔 Papa Rellena</option>
                    </select>
                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Descripción</label>
                <textarea name="description" class="form-control" placeholder="Describe el relleno y características...">{{ old('description') }}</textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Precio (COP) *</label>
                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                           value="{{ old('price') }}" placeholder="2500" min="0" step="100" required>
                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Estado</label>
                    <select name="active" class="form-select">
                        <option value="1" {{ old('active', '1') == '1' ? 'selected' : '' }}>✅ Activo</option>
                        <option value="0" {{ old('active') == '0' ? 'selected' : '' }}>❌ Inactivo</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;gap:0.75rem;margin-top:0.5rem">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Producto</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
