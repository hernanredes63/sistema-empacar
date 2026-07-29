<template>
    <Head title="Planes de Pago" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Módulo de Planes de Pago
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-700">Historial de Financiamientos</h3>
                        <Link :href="route('plan_pagos.create')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                            + Generar Plan de Pago
                        </Link>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 border">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 border-b">ID Plan</th>
                                    <th class="px-6 py-3 border-b">Venta / Cliente</th>
                                    <th class="px-6 py-3 border-b text-center">Cuotas</th>
                                    <th class="px-6 py-3 border-b text-right">Monto Cuota (Bs)</th>
                                    <th class="px-6 py-3 border-b text-right">Saldo Pendiente</th>
                                    <th class="px-6 py-3 border-b text-center">Estado</th>
                                    <th class="px-6 py-3 border-b text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="plan in planes" :key="plan.id" class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">#{{ plan.id }}</td>
                                    <td class="px-6 py-4">Venta #{{ plan.id_venta }} - {{ plan.venta?.cliente?.nombre || 'Desconocido' }}</td>
                                    <td class="px-6 py-4 text-center font-bold">{{ plan.cantidad_cuotas }}</td>
                                    <td class="px-6 py-4 text-right">{{ plan.monto_cuota }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-red-600">Bs. {{ plan.saldo_pendiente }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            {{ plan.estado_plan }}
                                        </span>
                                    </td>


                                    <td class="px-6 py-4 text-center">
                                        <Link :href="route('plan_pagos.show', plan.id)" class="text-blue-600 hover:text-blue-900 font-bold hover:underline">
                                            Ver Cuotas
                                        </Link>
                                    </td>   


                                </tr>
                                <tr v-if="planes.length === 0">
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        No hay planes de pago registrados.
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
    planes: {
        type: Array,
        default: () => []
    }
});
</script>