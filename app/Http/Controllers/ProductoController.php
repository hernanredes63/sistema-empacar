<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Categoria;

class ProductoController extends Controller
{
    /**
     * Muestra la lista de productos.
     */
    public function index()
    {
        // Obtenemos todos los productos. 
        // Usamos with('categoria') para traer también los datos de la categoría relacionada.
        $productos = Producto::with('categoria')->get();

        // Retornamos la vista Index.vue que creamos, pasándole los productos
        return Inertia::render('Productos/Index', [
            'productos' => $productos
        ]);
    }

    /**
     * Muestra el formulario para crear un nuevo producto.
     */
    /**
     * Muestra el formulario para crear un nuevo producto.
     */
    public function create()
    {
        // Traemos todas las categorías para mostrarlas en el <select> del formulario
        $categorias = Categoria::all();

        return Inertia::render('Productos/Create', [
            'categorias' => $categorias
        ]);
    }

    /**
     * Guarda el nuevo producto en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validamos los datos que llegan del formulario
        $request->validate([
            'codigo' => 'required|string|max:50|unique:productos,codigo',
            'nombre' => 'required|string|max:255',
            'id_categoria' => 'required|exists:categorias,id',
            'descripcion' => 'nullable|string',
            'state' => 'boolean',
        ]);

        // 2. Guardamos en la base de datos
        Producto::create([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'id_categoria' => $request->id_categoria,
            'descripcion' => $request->descripcion,
            'state' => $request->state ?? true, // Por defecto activo
        ]);

        // 3. Redireccionamos de vuelta a la lista de productos
        return redirect()->route('productos.index');
    }

    /**
     * Muestra el formulario para editar un producto.
     */
    public function edit(string $id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::all();

        return Inertia::render('Productos/Edit', [
            'producto' => $producto,
            'categorias' => $categorias
        ]);
    }

    /**
     * Actualiza el producto en la base de datos.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            // Ignoramos el ID actual para la regla unique del código
            'codigo' => 'required|string|max:50|unique:productos,codigo,' . $id,
            'nombre' => 'required|string|max:255',
            'id_categoria' => 'required|exists:categorias,id',
            'descripcion' => 'nullable|string',
            'state' => 'boolean',
        ]);

        $producto = Producto::findOrFail($id);
        
        $producto->update([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'id_categoria' => $request->id_categoria,
            'descripcion' => $request->descripcion,
            // Guardamos el estado explícitamente como booleano
            'state' => $request->boolean('state'), 
        ]);

        return redirect()->route('productos.index');
    }

    /**
     * Elimina el producto de la base de datos.
     */
    public function destroy(string $id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('productos.index');
    }
}