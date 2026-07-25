<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    productos: {
        type: Array,
        default: () => [],
    }
});
</script>

<template>
    <Head title="Productos" />

    <AuthenticatedLayout>
        <!-- Título de la página en la barra superior -->
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Productos</h2>
        </template>

        <!-- Contenido principal -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    
                    <!-- Encabezado y botón de crear -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Lista de Productos</h3>
                        <Link href="/productos/create" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                            Nuevo Producto
                        </Link>
                    </div>

                    <!-- Tabla de productos -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="producto in productos" :key="producto.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ producto.codigo }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ producto.nombre }}</td>
                                    
                                    <!-- Aquí aprovechamos la relación con la tabla categorías -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ producto.categoria ? producto.categoria.nombre : 'N/A' }}
                                    </td>
                                    
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ producto.descripcion }}</td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span v-if="producto.state" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Activo
                                        </span>
                                        <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactivo
                                        </span>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <Link :href="`/productos/${producto.id}/edit`" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</Link>
                                        <Link :href="`/productos/${producto.id}`" method="delete" as="button" class="text-red-600 hover:text-red-900">
                                            Eliminar
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mensaje cuando no hay datos -->
                    <div v-if="productos.length === 0" class="text-center py-8 text-gray-500">
                        No hay productos registrados todavía.
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>