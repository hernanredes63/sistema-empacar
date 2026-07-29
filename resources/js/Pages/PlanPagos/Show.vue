<template>
    <Head title="Detalles del Plan de Pago" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalle del Plan #{{ plan.id }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <!-- Tarjeta de Resumen del Plan -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6 flex justify-between items-center border-l-4 border-indigo-500">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Cliente: {{ plan.venta?.cliente?.nombre }}</h3>
                        <p class="text-sm text-gray-600">Asociado a la Venta #{{ plan.id_venta }} | Total Deuda: Bs. {{ plan.total_deuda }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 uppercase font-bold tracking-widest">Saldo Pendiente</p>
                        <p class="text-2xl font-black text-red-600">Bs. {{ plan.saldo_pendiente }}</p>
                    </div>
                </div>

                <!-- Tabla de Cuotas -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-md font-bold text-gray-700 mb-4">Cronograma de Pagos (Cuotas)</h4>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 border">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 border-b text-center">N° Cuota</th>
                                    <th class="px-6 py-3 border-b">Vencimiento</th>
                                    <th class="px-6 py-3 border-b text-right">Monto (Bs)</th>
                                    <th class="px-6 py-3 border-b text-center">Estado</th>
                                    <th class="px-6 py-3 border-b text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="cuota in plan.cuotas" :key="cuota.id" class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-center font-bold text-gray-900">{{ cuota.numero_cuota }} de {{ plan.cantidad_cuotas }}</td>
                                    <td class="px-6 py-4 font-medium" :class="{'text-red-600': new Date(cuota.fecha_vencimiento) < new Date() && cuota.estado_cuota !== 'Pagada'}">
                                        {{ cuota.fecha_vencimiento }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold">Bs. {{ cuota.monto }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span v-if="cuota.estado_cuota === 'Pendiente'" class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Pendiente</span>
                                        <span v-else-if="cuota.estado_cuota === 'Pagada'" class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Pagada</span>
                                        <span v-else class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ cuota.estado_cuota }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        
                                        
                                        
                                        <!-- Cambia el botón existente por este -->
                                    <button v-if="cuota.estado_cuota === 'Pendiente'" @click="abrirModalQr(cuota)" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs transition-colors">
                                         Cobrar con QR
                                    </button>



                                        <span v-else class="text-gray-400 text-xs italic">Completada</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6 flex justify-start">
                        <Link :href="route('plan_pagos.index')" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded font-medium">
                            &larr; Volver al Historial
                        </Link>
                    </div>

                </div>
            </div>
        </div>






        <!-- Modal para mostrar el QR -->
<div v-if="mostrarModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-sm text-center">
        <h3 class="text-lg font-bold text-gray-900 mb-2">Escanea para Pagar</h3>
        <p class="text-sm text-gray-500 mb-4">Cuota #{{ cuotaSeleccionada?.numero_cuota }} - Bs. {{ cuotaSeleccionada?.monto }}</p>
        
        <div class="flex justify-center items-center min-h-[250px] bg-gray-50 border rounded-lg mb-4">
            <div v-if="cargandoQr" class="text-indigo-600 font-semibold animate-pulse">
                Generando QR seguro...
            </div>
            <img v-else-if="qrImagen" :src="'data:image/png;base64,' + qrImagen" alt="Código QR de Pago" class="w-64 h-64 object-contain">
            <div v-else class="text-red-500 text-sm">
                Hubo un error al generar el código.
            </div>
        </div>

        <button @click="cerrarModal" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded transition-colors">
            Cerrar Ventana
        </button>
    </div>
</div>




    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

defineProps({
    plan: Object
});

// Variables reactivas para controlar el Modal y el QR
const mostrarModal = ref(false);
const cargandoQr = ref(false);
const qrImagen = ref('');
const cuotaSeleccionada = ref(null);

// Función para abrir el modal y solicitar el QR a Laravel
const abrirModalQr = async (cuota) => {
    cuotaSeleccionada.value = cuota;
    mostrarModal.value = true;
    cargandoQr.value = true;
    qrImagen.value = '';

    try {
        // Pedimos el QR a nuestra ruta de Laravel
        const response = await axios.post(route('cuotas.generar_qr', cuota.id));
        
        if (response.data.success) {
            qrImagen.value = response.data.qrImage; // Guardamos el Base64
        }
    } catch (error) {
        alert("Error de comunicación con PagoFácil. Verifica las credenciales.");
        console.error(error);
    } finally {
        cargandoQr.value = false;
    }
};

const cerrarModal = () => {
    mostrarModal.value = false;
    cuotaSeleccionada.value = null;
};
</script>