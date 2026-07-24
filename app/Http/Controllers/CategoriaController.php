<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoriaController extends Controller
{
    /**
     * Permite consultar o listar datos activos[cite: 3].
     */
    public function index()
    {
        $categorias = Categoria::where('state', 'a')->get();
        return Inertia::render('Categorias/Index', [
            'categorias' => $categorias
        ]);
    }

    /**
     * Muestra el formulario para crear una categoría[cite: 3].
     */
    public function create()
    {
        return Inertia::render('Categorias/Create');
    }

    /**
     * Valida y guarda una nueva categoría[cite: 3].
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string|max:255',
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
        ]);

        Categoria::create($validated);
        return redirect()->route('categorias.index');
    }

    /**
     * Muestra el formulario de edición[cite: 3].
     */
    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        return Inertia::render('Categorias/Edit', [
            'categoria' => $categoria
        ]);
    }

    /**
     * Valida y actualiza los datos de la categoría[cite: 3].
     */
    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string|max:255',
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
        ]);

        $categoria->update($validated);
        return redirect()->route('categorias.index');
    }

    /**
     * Eliminación lógica: cambia el estado a inactivo ('i')[cite: 3].
     */
    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->update(['state' => 'i']);
        return redirect()->route('categorias.index');
    }
}