<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Inertia\Inertia; // Puente entre Laravel y Vue.js

class ClienteController extends Controller
{
    /**
     * Permite consultar o listar datos.
     */
    public function index()
    {
        // Solo traemos los clientes activos para respetar la eliminación lógica[cite: 3]
        $clientes = Cliente::where('state', 'a')->get(); 
        
        // Enviamos los datos al componente Vue 'Clientes/Index' mediante Inertia[cite: 3]
        return Inertia::render('Clientes/Index', [
            'clientes' => $clientes
        ]);
    }

    /**
     * Muestra el formulario para crear un nuevo cliente.
     */
    public function create()
    {
        return Inertia::render('Clientes/Create');
    }

    /**
     * Valida y guarda la información del nuevo cliente.
     */
    public function store(Request $request)
    {
        // 1. El sistema valida los datos con mensajes en español[cite: 3]
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'documento' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:100',
        ], [
            'nombre.required' => 'El nombre o razón social es obligatorio.',
            'nombre.max' => 'El nombre no debe exceder los 150 caracteres.',
            'email.email' => 'El formato del correo electrónico no es válido.',
        ]);

        // 2. Guarda la información[cite: 3]
        Cliente::create($validated);

        // 3. Redirige a la lista y muestra un mensaje de confirmación[cite: 3]
        return redirect()->route('clientes.index');
    }




    /**
     * Muestra el formulario para editar un cliente[cite: 3].
     */
    public function edit(Cliente $cliente)
    {
        return Inertia::render('Clientes/Edit', [
            'cliente' => $cliente
        ]);
    }

    /**
     * Valida y actualiza la información del cliente[cite: 3].
     */
    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'documento' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:100',
        ]);

        $cliente->update($validated);

        return redirect()->route('clientes.index');
    }

    /**
     * Realiza la eliminación lógica del cliente[cite: 3].
     */
    public function destroy(Cliente $cliente)
    {
        // Cambiamos el estado a inactivo ('i') en lugar de borrarlo[cite: 3]
        $cliente->update(['state' => 'i']);

        return redirect()->route('clientes.index');
    }


    // Más adelante agregaremos las funciones create, store, edit, update y destroy 
    // siguiendo el detalle procedimental del CU3: Gestión de Clientes[cite: 3].
}