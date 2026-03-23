@extends('layouts.admin')
@section('title', 'Editar Cliente')

@section('admin-content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-user-edit text-red"></i> Editar Cliente</h1>
    <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="card" style="max-width:640px">
    <div class="card-header">
        <span class="card-title">{{ $client->name }}</span>
        <span class="badge badge-blue">{{ $client->sales()->count() }} ventas</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.clients.update', $client) }}">
            @csrf @method('PUT')
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tipo de Documento *</label>
                    <select name="document_type" class="form-select @error('document_type') is-invalid @enderror" required>
                        <option value="CC" {{ old('document_type', $client->document_type) == 'CC' ? 'selected' : '' }}>CC - Cédula de Ciudadanía</option>
                        <option value="CE" {{ old('document_type', $client->document_type) == 'CE' ? 'selected' : '' }}>CE - Cédula de Extranjería</option>
                        <option value="NIT" {{ old('document_type', $client->document_type) == 'NIT' ? 'selected' : '' }}>NIT</option>
                        <option value="PP" {{ old('document_type', $client->document_type) == 'PP' ? 'selected' : '' }}>PP - Pasaporte</option>
                        <option value="TI" {{ old('document_type', $client->document_type) == 'TI' ? 'selected' : '' }}>TI - Tarjeta de Identidad</option>
                    </select>
                    @error('document_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Número de Documento *</label>
                    <input type="text" name="document_number" class="form-control @error('document_number') is-invalid @enderror"
                           value="{{ old('document_number', $client->document_number) }}" required>
                    @error('document_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Nombre Completo *</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $client->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Ciudad</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city', $client->city) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $client->phone) }}">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Dirección</label>
                <input type="text" name="address" class="form-control" value="{{ old('address', $client->address) }}">
            </div>
            <div style="display:flex;gap:0.75rem;margin-top:0.5rem">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Actualizar</button>
                <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
