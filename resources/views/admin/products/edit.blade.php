@extends('layouts.admin')
@section('title', 'Editar Producto')

@section('admin-content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-edit text-red"></i> Editar Producto</h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="card" style="max-width:600px">
    <div class="card-header">
        <span class="card-title">{{ $product->name }}</span>
        @if($product->hasSales())
        <span class="badge badge-yellow"><i class="fas fa-lock"></i> Tiene ventas</span>
        @endif
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.products.update', $product) }}">
            @csrf @method('PUT')
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $product->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Categoría *</label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                        <option value="empanada" {{ old('category', $product->category) == 'empanada' ? 'selected' : '' }}>🫓 Empanada</option>
                        <option value="papa_rellena" {{ old('category', $product->category) == 'papa_rellena' ? 'selected' : '' }}>🥔 Papa Rellena</option>
                    </select>
                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Descripción</label>
                <textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Precio (COP) *</label>
                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                           value="{{ old('price', $product->price) }}" min="0" step="100" required>
                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Estado</label>
                    <select name="active" class="form-select">
                        <option value="1" {{ old('active', $product->active ? '1' : '0') == '1' ? 'selected' : '' }}>✅ Activo</option>
                        <option value="0" {{ old('active', $product->active ? '1' : '0') == '0' ? 'selected' : '' }}>❌ Inactivo</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;gap:0.75rem;margin-top:0.5rem">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Actualizar</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
