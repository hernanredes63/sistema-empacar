<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    categorias: Array,
});
</script>

<template>
    <Head title="Categorías" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Categorías</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    
                    <div class="flex justify-end mb-4">
                        <Link :href="route('categorias.create')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">
                            + Nueva Categoría
                        </Link>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-600">Nombre</th>
                                    <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-600">Descripción</th>
                                    <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-600">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="categoria in categorias" :key="categoria.id" class="hover:bg-gray-100">
                                    <td class="py-2 px-4 border-b text-sm font-medium">{{ categoria.nombre }}</td>
                                    <td class="py-2 px-4 border-b text-sm">{{ categoria.descripcion || '-' }}</td>
                                    <td class="py-2 px-4 border-b text-sm text-center space-x-3">
                                        <Link :href="route('categorias.edit', categoria.id)" class="text-yellow-600 hover:text-yellow-800 font-semibold">
                                            Editar
                                        </Link>
                                        <Link :href="route('categorias.destroy', categoria.id)" method="delete" as="button" class="text-red-600 hover:text-red-800 font-semibold">
                                            Eliminar
                                        </Link>
                                    </td>
                                </tr>
                                <tr v-if="categorias.length === 0">
                                    <td colspan="3" class="py-4 text-center text-gray-500">No hay categorías registradas en el sistema.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>