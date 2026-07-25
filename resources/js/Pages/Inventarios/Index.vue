<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    inventarios: Array,
    productos: Array,
});
</script>

<template>
    <Head title="Inventario" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Control de Inventario</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <!-- Botón para Registrar Movimiento -->
                <div class="mb-4 flex justify-end">
                    <Link :href="route('inventarios.create')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Registrar Movimiento
                    </Link>
                </div>

                <!-- Tabla de Movimientos -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">ID</th>
                                    <th scope="col" class="px-6 py-3">Producto</th>
                                    <th scope="col" class="px-6 py-3">Tipo Movimiento</th>
                                    <th scope="col" class="px-6 py-3">Cantidad</th>
                                    <th scope="col" class="px-6 py-3">Stock Actual</th>
                                    <th scope="col" class="px-6 py-3">Fecha</th>
                                    <th scope="col" class="px-6 py-3">Descripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="movimiento in inventarios" :key="movimiento.id" class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ movimiento.id }}</td>
                                    <td class="px-6 py-4">{{ movimiento.producto ? movimiento.producto.nombre : 'N/A' }}</td>
                                    <td class="px-6 py-4 uppercase font-semibold" 
                                        :class="{'text-green-600': movimiento.tipo_movimiento === 'entrada', 'text-red-600': movimiento.tipo_movimiento === 'salida', 'text-yellow-600': movimiento.tipo_movimiento === 'ajuste'}">
                                        {{ movimiento.tipo_movimiento }}
                                    </td>
                                    <td class="px-6 py-4">{{ movimiento.cantidad }}</td>
                                    <td class="px-6 py-4 font-bold text-gray-800">{{ movimiento.stock_actual }}</td>
                                    <td class="px-6 py-4">{{ new Date(movimiento.created_at).toLocaleDateString() }}</td>
                                    <td class="px-6 py-4">{{ movimiento.descripcion || 'Sin observación' }}</td>
                                </tr>
                                <tr v-if="inventarios.length === 0">
                                    <td colspan="7" class="px-6 py-4 text-center">No hay movimientos registrados todavía.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>