<template>
    <Head title="Gestión de Compras" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Módulo de Compras
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <!-- Tarjeta Principal -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    
                    <!-- Encabezado y Botón de Acción -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-700">Historial de Compras</h3>
                        
                        <Link :href="route('compras.create')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                            + Registrar Nueva Compra
                        </Link>
                    </div>

                    <!-- Tabla de Datos -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 border">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 border-b">ID</th>
                                    <th scope="col" class="px-6 py-3 border-b">Fecha</th>
                                    <th scope="col" class="px-6 py-3 border-b">Proveedor</th>
                                    <th scope="col" class="px-6 py-3 border-b text-right">Total (Bs.)</th>
                                    <th scope="col" class="px-6 py-3 border-b text-center">Estado</th>
                                    <th scope="col" class="px-6 py-3 border-b text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Iteramos sobre las compras usando v-for -->
                                <tr v-for="compra in compras" :key="compra.id" class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">#{{ compra.id }}</td>
                                    <td class="px-6 py-4">{{ compra.fecha_compra }}</td>
                                    <!-- Mostramos el nombre del proveedor gracias a la relación de Eloquent -->
                                    <td class="px-6 py-4">{{ compra.proveedor?.nombre || 'Desconocido' }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-700">{{ compra.total }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            {{ compra.estado_compra }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <!-- Botón para ver detalles (Opcional para futuro) -->
                                        <button class="text-blue-600 hover:underline mr-3">Detalles</button>
                                        
                                        <!-- Botón para anular/eliminar lógicamente -->
                                        <Link :href="route('compras.destroy', compra.id)" method="delete" as="button" class="text-red-600 hover:underline">
                                            Anular
                                        </Link>
                                    </td>
                                </tr>
                                
                                <!-- Mensaje si la tabla está vacía -->
                                <tr v-if="compras.length === 0">
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        No hay compras registradas aún. Haz clic en "Registrar Nueva Compra" para comenzar.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

// Recibimos la variable 'compras' que nos envió el CompraController
defineProps({
    compras: {
        type: Array,
        default: () => []
    }
});
</script>