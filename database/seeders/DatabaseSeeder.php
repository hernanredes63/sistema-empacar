<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Insertamos los roles obligatorios para el sistema
        DB::table('roles')->insert([
            [
                'id' => 1, 
                'nombre' => 'Administrador',
                'descripcion' => 'Usuario con acceso completo al sistema.',
                'state' => 'a',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2, 
                'nombre' => 'Usuario Standard',
                'descripcion' => 'Usuario estándar',
                'state' => 'a',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 2. Insertamos los privilegios para el Administrador (Rol 1)
        // ¡ESTO ES LO QUE ENCIENDE EL MENÚ DE LA IZQUIERDA!
        DB::table('privilegios')->insert([
            ['funcionalidad' => 'Roles', 'agregar' => true, 'modificar' => true, 'borrar' => true, 'leer' => true, 'id_rol' => 1, 'state' => 'a', 'created_at' => now(), 'updated_at' => now()],
            ['funcionalidad' => 'Usuarios', 'agregar' => true, 'modificar' => true, 'borrar' => true, 'leer' => true, 'id_rol' => 1, 'state' => 'a', 'created_at' => now(), 'updated_at' => now()],
            ['funcionalidad' => 'Bitacora', 'agregar' => true, 'modificar' => true, 'borrar' => true, 'leer' => true, 'id_rol' => 1, 'state' => 'a', 'created_at' => now(), 'updated_at' => now()],
            ['funcionalidad' => 'Clientes', 'agregar' => true, 'modificar' => true, 'borrar' => true, 'leer' => true, 'id_rol' => 1, 'state' => 'a', 'created_at' => now(), 'updated_at' => now()],
            ['funcionalidad' => 'Proveedores', 'agregar' => true, 'modificar' => true, 'borrar' => true, 'leer' => true, 'id_rol' => 1, 'state' => 'a', 'created_at' => now(), 'updated_at' => now()],
            ['funcionalidad' => 'Categorias', 'agregar' => true, 'modificar' => true, 'borrar' => true, 'leer' => true, 'id_rol' => 1, 'state' => 'a', 'created_at' => now(), 'updated_at' => now()],
            ['funcionalidad' => 'Productos', 'agregar' => true, 'modificar' => true, 'borrar' => true, 'leer' => true, 'id_rol' => 1, 'state' => 'a', 'created_at' => now(), 'updated_at' => now()],
            ['funcionalidad' => 'Inventario', 'agregar' => true, 'modificar' => true, 'borrar' => true, 'leer' => true, 'id_rol' => 1, 'state' => 'a', 'created_at' => now(), 'updated_at' => now()],
            ['funcionalidad' => 'Compras', 'agregar' => true, 'modificar' => true, 'borrar' => true, 'leer' => true, 'id_rol' => 1, 'state' => 'a', 'created_at' => now(), 'updated_at' => now()],
            ['funcionalidad' => 'Ventas', 'agregar' => true, 'modificar' => true, 'borrar' => true, 'leer' => true, 'id_rol' => 1, 'state' => 'a', 'created_at' => now(), 'updated_at' => now()],
            ['funcionalidad' => 'Pagos', 'agregar' => true, 'modificar' => true, 'borrar' => true, 'leer' => true, 'id_rol' => 1, 'state' => 'a', 'created_at' => now(), 'updated_at' => now()],
            ['funcionalidad' => 'Plan de Pago', 'agregar' => true, 'modificar' => true, 'borrar' => true, 'leer' => true, 'id_rol' => 1, 'state' => 'a', 'created_at' => now(), 'updated_at' => now()],
            ['funcionalidad' => 'Cuotas', 'agregar' => true, 'modificar' => true, 'borrar' => true, 'leer' => true, 'id_rol' => 1, 'state' => 'a', 'created_at' => now(), 'updated_at' => now()],
            ['funcionalidad' => 'Reportes', 'agregar' => true, 'modificar' => true, 'borrar' => true, 'leer' => true, 'id_rol' => 1, 'state' => 'a', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}