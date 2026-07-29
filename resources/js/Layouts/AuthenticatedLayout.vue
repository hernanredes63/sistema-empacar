<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="flex h-screen bg-gray-100 font-sans">
        
        <!-- MENÚ LATERAL (SIDEBAR) -->
        <aside class="w-64 bg-gray-800 text-white flex flex-col">
            <!-- Logo / Título -->
            <div class="h-16 flex items-center justify-center border-b border-gray-700">
                <h1 class="text-xl font-bold uppercase tracking-wider">EMPACAR S.A.</h1>
            </div>

            <!-- Navegación según Diagrama -->
            <!-- Navegación según Diagrama -->
            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1">
                    <!-- Dashboard siempre visible -->
                    <li>
                        <Link :href="route('dashboard')" class="block px-6 py-2 hover:bg-gray-700 transition-colors">
                            Panel Principal
                        </Link>
                    </li>

                    <!-- ADMINISTRACIÓN -->
                    <template v-if="$page.props.auth.privilegios['Roles']?.leer || $page.props.auth.privilegios['Usuarios']?.leer || $page.props.auth.privilegios['Bitacora']?.leer">
                        <li class="px-6 py-2 mt-4 text-xs uppercase tracking-wider text-gray-400 font-semibold">
                            Administración
                        </li>
                        <li v-if="$page.props.auth.privilegios['Roles']?.leer">
                            <a href="#" class="block px-6 py-2 hover:bg-gray-700 transition-colors">Roles y Privilegios</a>
                        </li>
                        <li v-if="$page.props.auth.privilegios['Usuarios']?.leer">
                            <a href="#" class="block px-6 py-2 hover:bg-gray-700 transition-colors">Usuarios</a>
                        </li>
                        <li v-if="$page.props.auth.privilegios['Bitacora']?.leer">
                            <a href="#" class="block px-6 py-2 hover:bg-gray-700 transition-colors">Bitácora de Accesos</a>
                        </li>
                    </template>

                    <!-- GESTIÓN COMERCIAL -->
                    <template v-if="$page.props.auth.privilegios['Clientes']?.leer || $page.props.auth.privilegios['Proveedores']?.leer || $page.props.auth.privilegios['Categorias']?.leer || $page.props.auth.privilegios['Productos']?.leer">
                        <li class="px-6 py-2 mt-4 text-xs uppercase tracking-wider text-gray-400 font-semibold">
                            Gestión Comercial
                        </li>
                       <li v-if="$page.props.auth.privilegios['Clientes']?.leer">
                            <Link :href="route('clientes.index')" class="block px-6 py-2 hover:bg-gray-700 transition-colors">
                                Clientes
                            </Link>
                        </li>
                        <li v-if="$page.props.auth.privilegios['Proveedores']?.leer">
                           <Link :href="route('proveedores.index')" class="block px-6 py-2 hover:bg-gray-700 transition-colors">
                                Proveedores
                            </Link>
                        </li>
                        <li v-if="$page.props.auth.privilegios['Categorias']?.leer">
                            <Link :href="route('categorias.index')" class="block px-6 py-2 hover:bg-gray-700 transition-colors">
                                Categorías
                            </Link>
                        </li>
                        <li v-if="$page.props.auth.privilegios['Productos']?.leer">
                            <Link :href="route('productos.index')" class="block px-6 py-2 hover:bg-gray-700 transition-colors">
                                Productos
                            </Link>
                        </li>
                    </template>

 <!-- COMPRAS E INVENTARIO -->
<template v-if="$page.props.auth.privilegios['Compras']?.leer || $page.props.auth.privilegios['Inventario']?.leer">
    <li class="px-6 py-2 mt-4 text-xs uppercase tracking-wider text-gray-400 font-semibold">
        Compras e Inventario
    </li>
    
    <!-- ENLACE ACTUALIZADO -->
    <li v-if="$page.props.auth.privilegios['Compras']?.leer">
        <Link :href="route('compras.index')" class="block px-6 py-2 hover:bg-gray-700 transition-colors">
            Solicitudes de Compra
        </Link>
    </li>

    <li v-if="$page.props.auth.privilegios['Inventario']?.leer">
        <!-- AQUÍ ESTÁ EL BOTÓN CORRECTO -->
        <Link href="/inventarios" class="block px-6 py-2 hover:bg-gray-700 transition-colors">
            Inventario
        </Link>
    </li>
</template>

                    <!-- VENTAS Y PAGOS -->
                    <template v-if="$page.props.auth.privilegios['Ventas']?.leer || $page.props.auth.privilegios['Pagos']?.leer || $page.props.auth.privilegios['Plan de Pago']?.leer || $page.props.auth.privilegios['Cuotas']?.leer">
                        <li class="px-6 py-2 mt-4 text-xs uppercase tracking-wider text-gray-400 font-semibold">
                            Ventas y Pagos
                        </li>
                        <li v-if="$page.props.auth.privilegios['Ventas']?.leer">
    <Link :href="route('ventas.index')" class="block px-6 py-2 hover:bg-gray-700 transition-colors">
        Ventas
    </Link>
</li>
                        <li v-if="$page.props.auth.privilegios['Pagos']?.leer">
                            <a href="#" class="block px-6 py-2 hover:bg-gray-700 transition-colors">Pagos y QR</a>
                        </li>
                        <li v-if="$page.props.auth.privilegios['Plan de Pago']?.leer">
    <Link :href="route('plan_pagos.index')" class="block px-6 py-2 hover:bg-gray-700 transition-colors">
        Planes de Pago
    </Link>
</li>
                        <li v-if="$page.props.auth.privilegios['Cuotas']?.leer">
                            <a href="#" class="block px-6 py-2 hover:bg-gray-700 transition-colors">Cuotas</a>
                        </li>
                    </template>

                    <!-- REPORTES -->
                    <template v-if="$page.props.auth.privilegios['Reportes']?.leer">
                        <li class="px-6 py-2 mt-4 text-xs uppercase tracking-wider text-gray-400 font-semibold">
                            Reportes
                        </li>
                        <li v-if="$page.props.auth.privilegios['Reportes']?.leer">
                            <a href="#" class="block px-6 py-2 hover:bg-gray-700 transition-colors">Estadísticas y Reportes</a>
                        </li>
                    </template>
                </ul>
            </nav>
        </aside>

        <!-- ÁREA PRINCIPAL -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- ENCABEZADO -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm">
                <!-- Buscador Global -->
                <div class="flex-1 flex">
                    <input 
                        type="text" 
                        placeholder="Buscador global..." 
                        class="w-64 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                    >
                </div>

                <!-- Menú de Usuario -->
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-gray-700">
                        {{ $page.props.auth.user.nombre }} {{ $page.props.auth.user.apellido }}
                    </span>
                    <Link :href="route('logout')" method="post" as="button" class="text-sm text-red-600 hover:text-red-800 font-semibold">
                        Cerrar Sesión
                    </Link>
                </div>
            </header>

            <!-- CONTENIDO DINÁMICO DE LAS PÁGINAS -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <!-- Aquí se inyectan las vistas de Inertia -->
                <slot />
            </main>

            <!-- PIE DE PÁGINA (CONTADOR DE VISITAS) -->
            <footer class="bg-white border-t border-gray-200 p-4 text-center text-sm text-gray-500 flex justify-between">
                <span>&copy; 2026 EMPACAR S.A.</span>
                <span class="font-semibold text-indigo-600">Visitas de esta página: 10</span> <!-- Estático por ahora -->
            </footer>
        </div>

    </div>
</template>