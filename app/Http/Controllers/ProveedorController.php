<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Inertia\Inertia; // Puente de comunicación

class ProveedorController extends Controller
{
    /**
     * Lista los proveedores activos[cite: 3].
     */
    public function index()
    {
        $proveedores = Proveedor::where('state', 'a')->get();
        return Inertia::render('Proveedores/Index', [
            'proveedores' => $proveedores
        ]);
    }

    /**
     * Muestra el formulario para crear un proveedor[cite: 3].
     */
    public function create()
    {
        return Inertia::render('Proveedores/Create');
    }

    /**
     * Valida y guarda un nuevo proveedor[cite: 3].
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'nit' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'direccion' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
        ], [
            'nombre.required' => 'El nombre o razón social es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
        ]);

        Proveedor::create($validated);
        return redirect()->route('proveedores.index');
    }

    /**
     * Muestra el formulario de edición[cite: 3].
     */
    public function edit($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return Inertia::render('Proveedores/Edit', [
            'proveedor' => $proveedor
        ]);
    }

    /**
     * Valida y actualiza los datos del proveedor[cite: 3].
     */
    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'nit' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'direccion' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $proveedor->update($validated);
        return redirect()->route('proveedores.index');
    }

    /**
     * Eliminación lógica: cambia el estado a inactivo ('i')[cite: 3].
     */
    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->update(['state' => 'i']);
        return redirect()->route('proveedores.index');
    }
}