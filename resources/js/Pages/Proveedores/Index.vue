<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    proveedores: Array,
});
</script>

<template>
    <Head title="Proveedores" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Proveedores</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    
                    <div class="flex justify-end mb-4">
                        <Link :href="route('proveedores.create')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">
                            + Nuevo Proveedor
                        </Link>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-600">Nombre</th>
                                    <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-600" translate="no">NIT</th>                                    <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-600">Teléfono</th>
                                    <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-600">Correo</th>
                                    <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="proveedor in proveedores" :key="proveedor.id" class="hover:bg-gray-100">
                                    <td class="py-2 px-4 border-b text-sm font-medium">{{ proveedor.nombre }}</td>
                                    <td class="py-2 px-4 border-b text-sm">{{ proveedor.nit || '-' }}</td>
                                    <td class="py-2 px-4 border-b text-sm">{{ proveedor.telefono || '-' }}</td>
                                    <td class="py-2 px-4 border-b text-sm">{{ proveedor.email || '-' }}</td>
                                    <td class="py-2 px-4 border-b text-sm text-center space-x-3">
                                        <Link :href="route('proveedores.edit', proveedor.id)" class="text-yellow-600 hover:text-yellow-800 font-semibold">
                                            Editar
                                        </Link>
                                        <Link :href="route('proveedores.destroy', proveedor.id)" method="delete" as="button" class="text-red-600 hover:text-red-800 font-semibold">
                                            Eliminar
                                        </Link>
                                    </td>
                                </tr>
                                <tr v-if="proveedores.length === 0">
                                    <td colspan="5" class="py-4 text-center text-gray-500">No hay proveedores registrados en el sistema.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>