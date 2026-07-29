<template>
    <Head title="Generar Plan de Pago" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Generar Nuevo Plan de Pago
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg p-6 max-w-2xl mx-auto">
                    
                    <form @submit.prevent="generarPlan">
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Venta al Crédito</label>
                            <select v-model="form.id_venta" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="" disabled>Seleccione una venta pendiente...</option>
                                <option v-for="venta in ventas" :key="venta.id" :value="venta.id">
                                    Venta #{{ venta.id }} - {{ venta.cliente?.nombre }} (Deuda: Bs. {{ venta.total }})
                                </option>
                            </select>
                            <p v-if="ventas.length === 0" class="text-sm text-red-500 mt-2">No hay ventas al crédito pendientes de plan de pago.</p>
                        </div>

                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cantidad de Cuotas (Meses)</label>
                                <input type="number" min="1" max="72" v-model="form.cantidad_cuotas" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Primera Cuota</label>
                                <input type="date" v-model="form.fecha_inicio" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-md mb-6" v-if="form.id_venta && form.cantidad_cuotas > 0">
                            <p class="text-sm font-semibold">Resumen de la Proyección:</p>
                            <p class="text-sm mt-1">El sistema dividirá la deuda total de la venta seleccionada en <strong>{{ form.cantidad_cuotas }}</strong> cuotas consecutivas mensuales, comenzando el <strong>{{ form.fecha_inicio }}</strong>.</p>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <Link :href="route('plan_pagos.index')" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">Cancelar</Link>
                            <button type="submit" :disabled="form.processing || ventas.length === 0" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded disabled:opacity-50">
                                Generar Cuotas
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    ventas: Array
});

const form = useForm({
    id_venta: '',
    cantidad_cuotas: 1,
    fecha_inicio: new Date().toISOString().slice(0, 10),
});

const generarPlan = () => {
    form.post(route('plan_pagos.store'));
};
</script>