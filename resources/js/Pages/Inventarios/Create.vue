<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    productos: Array,
});

const form = useForm({
    id_producto: '',
    tipo_movimiento: 'entrada',
    cantidad: 1,
    descripcion: '',
});

const submit = () => {
    form.post(route('inventarios.store'));
};
</script>

<template>
    <Head title="Registrar Movimiento" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar Movimiento de Inventario</h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit">
                        
                        <!-- Selección de Producto -->
                        <div class="mb-4">
                            <label for="id_producto" class="block text-sm font-medium text-gray-700">Producto</label>
                            <select id="id_producto" v-model="form.id_producto" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="" disabled>Seleccione un producto</option>
                                <option v-for="producto in productos" :key="producto.id" :value="producto.id">
                                    {{ producto.nombre }} (Stock actual: {{ producto.stock_actual }})
                                </option>
                            </select>
                        </div>

                        <!-- Tipo de Movimiento -->
                        <div class="mb-4">
                            <label for="tipo_movimiento" class="block text-sm font-medium text-gray-700">Tipo de Movimiento</label>
                            <select id="tipo_movimiento" v-model="form.tipo_movimiento" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="entrada">Entrada</option>
                                <option value="salida">Salida</option>
                                <option value="ajuste">Ajuste</option>
                            </select>
                        </div>

                        <!-- Cantidad Numérica -->
                        <div class="mb-4">
                            <label for="cantidad" class="block text-sm font-medium text-gray-700">Cantidad</label>
                            <input type="number" id="cantidad" v-model="form.cantidad" required min="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <!-- Observación Textual -->
                        <div class="mb-6">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción / Motivo</label>
                            <textarea id="descripcion" v-model="form.descripcion" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex items-center justify-end space-x-3">
                            <Link :href="route('inventarios.index')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancelar
                            </Link>
                            <button type="submit" :disabled="form.processing" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Guardar Movimiento
                            </button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>