<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::where('is_counter_client', false)->orderBy('name')->paginate(15);
        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:CC,CE,NIT,PP,TI',
            'document_number' => 'required|string|max:30|unique:clients,document_number',
            'name' => 'required|string|max:150',
            'address' => 'nullable|string|max:200',
            'city' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        Client::create($request->only(['document_type', 'document_number', 'name', 'address', 'city', 'phone']));

        return redirect()->route('admin.clients.index')->with('success', 'Cliente creado exitosamente.');
    }

    public function edit(Client $client)
    {
        if ($client->is_counter_client) {
            return redirect()->route('admin.clients.index')->with('error', 'No se puede editar el cliente de mostrador.');
        }
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        if ($client->is_counter_client) {
            return redirect()->route('admin.clients.index')->with('error', 'No se puede editar el cliente de mostrador.');
        }

        $request->validate([
            'document_type' => 'required|in:CC,CE,NIT,PP,TI',
            'document_number' => 'required|string|max:30|unique:clients,document_number,' . $client->id,
            'name' => 'required|string|max:150',
            'address' => 'nullable|string|max:200',
            'city' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $client->update($request->only(['document_type', 'document_number', 'name', 'address', 'city', 'phone']));

        return redirect()->route('admin.clients.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Client $client)
    {
        if ($client->is_counter_client) {
            return redirect()->route('admin.clients.index')->with('error', 'No se puede eliminar el cliente de mostrador.');
        }

        if ($client->sales()->exists()) {
            return redirect()->route('admin.clients.index')
                ->with('error', 'No se puede eliminar el cliente porque tiene ventas registradas.');
        }

        $client->delete();
        return redirect()->route('admin.clients.index')->with('success', 'Cliente eliminado exitosamente.');
    }
}
