<template>
    <Head title="Detalle de Compra" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalle de la Solicitud de Compra #{{ compra.id }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <!-- Tarjeta Principal -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    
                    <!-- Botón Volver -->
                    <div class="mb-6">
                        <Link :href="route('compras.index')" class="text-indigo-600 hover:text-indigo-900 font-medium">
                            &larr; Volver al Historial
                        </Link>
                    </div>

                    <!-- Información de la Cabecera (Compra) -->
                    <div class="grid grid-cols-2 gap-4 mb-8 bg-gray-50 p-4 rounded-md border">
                        <div>
                            <p class="text-sm text-gray-500 font-semibold">Proveedor:</p>
                            <p class="text-lg text-gray-900">{{ compra.proveedor?.nombre || 'Desconocido' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-semibold">Fecha de Compra:</p>
                            <p class="text-lg text-gray-900">{{ compra.fecha_compra }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-semibold">Estado:</p>
                            <p class="text-md text-gray-900">
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ compra.estado_compra }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-semibold">Observación:</p>
                            <p class="text-md text-gray-900">{{ compra.observacion || 'Ninguna' }}</p>
                        </div>
                    </div>

                    <h3 class="text-lg font-bold text-gray-700 mb-4">Productos Adquiridos</h3>

                    <!-- Tabla de Detalles -->
                    <div class="overflow-x-auto mb-6">
                        <table class="w-full text-sm text-left text-gray-500 border">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 border-b">Código</th>
                                    <th scope="col" class="px-6 py-3 border-b">Producto</th>
                                    <th scope="col" class="px-6 py-3 border-b text-center">Cantidad</th>
                                    <th scope="col" class="px-6 py-3 border-b text-right">Precio Unit. (Bs)</th>
                                    <th scope="col" class="px-6 py-3 border-b text-right">Subtotal (Bs)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in detalles" :key="item.id" class="bg-white border-b">
                                    <td class="px-6 py-4 font-medium">{{ item.codigo || 'S/C' }}</td>
                                    <td class="px-6 py-4">{{ item.nombre }}</td>
                                    <td class="px-6 py-4 text-center">{{ item.cantidad }}</td>
                                    <td class="px-6 py-4 text-right">{{ item.precio_compra }}</td>
                                    <td class="px-6 py-4 text-right font-semibold">{{ item.subtotal }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-50">
                                    <td colspan="4" class="px-6 py-4 text-right font-bold text-gray-900 uppercase">
                                        Total General:
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-lg text-gray-900">
                                        Bs. {{ compra.total }}
                                    </td>
                                </tr>
                            </tfoot>
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

// Recibimos la compra y los detalles desde el CompraController
defineProps({
    compra: {
        type: Object,
        required: true
    },
    detalles: {
        type: Array,
        required: true
    }
});
</script>