<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

// Recibimos la variable 'clientes' que enviamos desde el ClienteController mediante Inertia[cite: 3]
defineProps({
    clientes: Array,
});
</script>

<template>
    <Head title="Clientes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Clientes</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    
                    <!-- Botón para agregar (La funcionalidad la haremos después) -->
                    <div class="flex justify-end mb-4">
                        <Link :href="route('clientes.create')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">
                            + Nuevo Cliente
                        </Link>
                    </div>

                    <!-- Tabla de registros -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-600">Nombre</th>
                                    <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-600">Documento</th>
                                    <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-600">Teléfono</th>
                                    <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-600">Correo</th>
                                    <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-600">Ciudad</th>
                                    <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Iteramos sobre el arreglo de clientes[cite: 3] -->
                                <tr v-for="cliente in clientes" :key="cliente.id" class="hover:bg-gray-100">
                                    <td class="py-2 px-4 border-b text-sm">{{ cliente.nombre }}</td>
                                    <td class="py-2 px-4 border-b text-sm">{{ cliente.documento || '-' }}</td>
                                    <td class="py-2 px-4 border-b text-sm">{{ cliente.telefono || '-' }}</td>
                                    <td class="py-2 px-4 border-b text-sm">{{ cliente.email || '-' }}</td>
                                    <td class="py-2 px-4 border-b text-sm">{{ cliente.ciudad || '-' }}</td>
                                    <td class="py-2 px-4 border-b text-sm text-center space-x-3">
    <!-- Botón Modificar[cite: 2] -->
    <Link :href="route('clientes.edit', cliente.id)" class="text-yellow-600 hover:text-yellow-800 font-semibold">
        Editar
    </Link>
    
    <!-- Botón Eliminar Lógico[cite: 2] -->
    <Link :href="route('clientes.destroy', cliente.id)" method="delete" as="button" class="text-red-600 hover:text-red-800 font-semibold">
        Eliminar
    </Link>
</td>
                                </tr>
                                <tr v-if="clientes.length === 0">
                                    <td colspan="6" class="py-4 text-center text-gray-500">No hay clientes registrados en el sistema.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>