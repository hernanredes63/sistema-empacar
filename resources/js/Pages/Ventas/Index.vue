<template>
    <Head title="Gestión de Ventas" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Módulo de Ventas
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-700">Historial de Ventas</h3>
                        <Link :href="route('ventas.create')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                            + Registrar Nueva Venta
                        </Link>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 border">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 border-b">ID</th>
                                    <th class="px-6 py-3 border-b">Fecha</th>
                                    <th class="px-6 py-3 border-b">Cliente</th>
                                    <th class="px-6 py-3 border-b text-center">Tipo</th>
                                    <th class="px-6 py-3 border-b text-right">Total (Bs.)</th>
                                    <th class="px-6 py-3 border-b text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="venta in ventas" :key="venta.id" class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">#{{ venta.id }}</td>
                                    <td class="px-6 py-4">{{ venta.fecha_venta }}</td>
                                    <td class="px-6 py-4">{{ venta.cliente?.nombre || 'Consumidor Final' }}</td>
                                    <td class="px-6 py-4 text-center uppercase text-xs font-bold">{{ venta.tipo_venta }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-700">{{ venta.total }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            {{ venta.estado_venta }}
                                        </span>
                                    </td>
                                </tr>
                                <tr v-if="ventas.length === 0">
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        No hay ventas registradas aún.
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

defineProps({
    ventas: {
        type: Array,
        default: () => []
    }
});
</script>