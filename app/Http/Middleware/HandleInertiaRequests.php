<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\DB;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $privilegios = [];
        
        // Si el usuario está autenticado, buscamos sus privilegios en la BD
        if ($request->user()) {
            $privilegios = DB::table('privilegios')
                ->where('id_rol', $request->user()->id_rol)
                ->where('state', 'a') // Solo privilegios activos
                ->get()
                ->keyBy('funcionalidad') // Organizamos el array por el nombre del módulo
                ->toArray();
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'privilegios' => $privilegios, // Pasamos los privilegios al frontend
            ],
        ];
    }
}
