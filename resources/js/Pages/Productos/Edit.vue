<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    producto: Object,
    categorias: Array
});

// Inicializamos el formulario con los datos que vienen de la base de datos
const form = useForm({
    codigo: props.producto.codigo,
    nombre: props.producto.nombre,
    id_categoria: props.producto.id_categoria,
    descripcion: props.producto.descripcion,
    // Aseguramos que sea interpretado como booleano (true/false)
    state: !!props.producto.state, 
});

const submit = () => {
    // En Laravel/Inertia, las actualizaciones se envían usando PUT o PATCH
    form.put(route('productos.update', props.producto.id));
};
</script>

<template>
    <Head title="Editar Producto" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Producto: {{ producto.nombre }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    
                    <form @submit.prevent="submit" class="max-w-2xl mx-auto">
                        
                        <!-- Código -->
                        <div class="mb-4">
                            <label for="codigo" class="block text-sm font-medium text-gray-700">Código del Producto</label>
                            <input type="text" id="codigo" v-model="form.codigo" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>
                            <div v-if="form.errors.codigo" class="text-red-600 text-sm mt-1">{{ form.errors.codigo }}</div>
                        </div>

                        <!-- Nombre -->
                        <div class="mb-4">
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" id="nombre" v-model="form.nombre" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>
                            <div v-if="form.errors.nombre" class="text-red-600 text-sm mt-1">{{ form.errors.nombre }}</div>
                        </div>

                        <!-- Categoría -->
                        <div class="mb-4">
                            <label for="id_categoria" class="block text-sm font-medium text-gray-700">Categoría</label>
                            <select id="id_categoria" v-model="form.id_categoria" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>
                                <option value="" disabled>Seleccione una categoría</option>
                                <option v-for="categoria in categorias" :key="categoria.id" :value="categoria.id">
                                    {{ categoria.nombre }}
                                </option>
                            </select>
                            <div v-if="form.errors.id_categoria" class="text-red-600 text-sm mt-1">{{ form.errors.id_categoria }}</div>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea id="descripcion" v-model="form.descripcion" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                            <div v-if="form.errors.descripcion" class="text-red-600 text-sm mt-1">{{ form.errors.descripcion }}</div>
                        </div>

                        <!-- Estado -->
                        <div class="mb-6 flex items-center">
                            <input type="checkbox" id="state" v-model="form.state" 
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="state" class="ml-2 block text-sm text-gray-900">
                                Producto Activo
                            </label>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end space-x-3">
                            <Link :href="route('productos.index')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded transition">
                                Cancelar
                            </Link>
                            <button type="submit" :disabled="form.processing" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition disabled:opacity-50">
                                Actualizar Producto
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>