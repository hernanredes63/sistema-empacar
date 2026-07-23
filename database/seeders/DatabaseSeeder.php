<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Roles según el documento
        DB::table('roles')->insert([
            ['nombre' => 'Administrador', 'descripcion' => 'Usuario con acceso completo al sistema.', 'state' => 'a', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Vendedor', 'descripcion' => 'Usuario encargado de registrar clientes y ventas.', 'state' => 'a', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Cajero', 'descripcion' => 'Usuario encargado de pagos, cuotas y planes de pago.', 'state' => 'a', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Encargado de Inventario', 'descripcion' => 'Usuario encargado de productos, compras e inventario.', 'state' => 'a', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Cliente', 'descripcion' => 'Usuario relacionado con operaciones comerciales de compra.', 'state' => 'a', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'Secretaria', 'descripcion' => 'Usuario de apoyo administrativo.', 'state' => 'a', 'created_at' => $now, 'updated_at' => $now]
        ]);

        // 2. Usuario administrador inicial según el documento
        DB::table('users')->insert([
            'nombre' => 'Administrador',
            'apellido' => 'EMPACAR',
            'ci' => '0000000',
            'telefono' => '70000001',
            'email' => 'admin@empacar.local',
            'username' => 'admin',
            // Cifrado desde Laravel como exige el documento
            'password' => Hash::make('admin123'), 
            'id_rol' => 1,
            'state' => 'a',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        // 3. Privilegios básicos de ejemplo para el rol Administrador (id_rol = 1)
        $modulos = [
            'Roles', 'Usuarios', 'Clientes', 'Proveedores', 'Categorias', 
            'Productos', 'Inventario', 'Compras', 'Ventas', 'Pagos', 
            'Plan de Pago', 'Cuotas', 'Reportes', 'Bitacora'
        ];

        $privilegios = [];
        foreach ($modulos as $modulo) {
            $privilegios[] = [
                'funcionalidad' => $modulo,
                'agregar' => true,
                'modificar' => true,
                'borrar' => true,
                'leer' => true,
                'id_rol' => 1,
                'state' => 'a',
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        DB::table('privilegios')->insert($privilegios);
        
        // 4. Contadores iniciales
        $paginas = ['Landing', 'Login', 'Dashboard'];
        $modulos_cont = [
            'Usuario', 'Rol', 'Privilegio', 'Cliente', 'Proveedor', 'Categoria', 
            'Producto', 'Inventario', 'Compra', 'Venta', 'Pago', 'PlanPago', 
            'Cuota', 'Reportes', 'Auditoria'
        ];

        $contadores = [];
        foreach ($paginas as $pagina) {
            $contadores[] = ['nombre' => $pagina, 'visitas' => 0, 'tipo' => 'pagina', 'created_at' => $now, 'updated_at' => $now];
        }
        foreach ($modulos_cont as $modulo_cont) {
            $contadores[] = ['nombre' => $modulo_cont, 'visitas' => 0, 'tipo' => 'modulo', 'created_at' => $now, 'updated_at' => $now];
        }

        DB::table('contadors')->insert($contadores);
    }
}