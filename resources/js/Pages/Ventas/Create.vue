<template>
    <Head title="Registrar Venta" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Punto de Venta
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    
                    <form @submit.prevent="guardarVenta">
                        
                        <!-- 1. Cabecera de la Venta -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fecha de Venta</label>
                                <input type="date" v-model="form.fecha_venta" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cliente</label>
                                <select v-model="form.id_cliente" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="" disabled>Seleccione cliente...</option>
                                    <option v-for="cliente in clientes" :key="cliente.id" :value="cliente.id">
                                        {{ cliente.nombre }} ({{ cliente.documento }})
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tipo de Venta</label>
                                <select v-model="form.tipo_venta" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="contado">Al Contado</option>
                                    <option value="credito">Al Crédito</option>
                                </select>
                            </div>
                        </div>

                        <!-- 2. Agregar Productos -->
                        <div class="bg-gray-50 p-4 rounded-md border mb-6">
                            <h4 class="text-md font-semibold text-gray-700 mb-4">Agregar Producto</h4>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Producto (Stock Disponible)</label>
                                    <select v-model="productoSeleccionado" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="" disabled>Seleccione producto...</option>
                                        <option v-for="prod in productos" :key="prod.id" :value="prod">
                                            {{ prod.codigo }} - {{ prod.nombre }} (Stock: {{ prod.stock_actual }})
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                                    <input type="number" v-model.number="cantidad" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <button type="button" @click="agregarAlCarrito" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors">
                                        Agregar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Detalle (Carrito) -->
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-sm text-left text-gray-500 border">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                                    <tr>
                                        <th class="px-4 py-2">Código</th>
                                        <th class="px-4 py-2">Producto</th>
                                        <th class="px-4 py-2 text-center">Cantidad</th>
                                        <th class="px-4 py-2 text-right">Precio U. (Bs)</th>
                                        <th class="px-4 py-2 text-right">Subtotal (Bs)</th>
                                        <th class="px-4 py-2 text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in form.productos" :key="index" class="bg-white border-b">
                                        <td class="px-4 py-2">{{ item.codigo }}</td>
                                        <td class="px-4 py-2">{{ item.nombre }}</td>
                                        <td class="px-4 py-2 text-center">{{ item.cantidad }}</td>
                                        <td class="px-4 py-2 text-right">{{ item.precio_venta }}</td>
                                        <td class="px-4 py-2 text-right font-bold">{{ (item.cantidad * item.precio_venta).toFixed(2) }}</td>
                                        <td class="px-4 py-2 text-center">
                                            <button type="button" @click="quitarDelCarrito(index)" class="text-red-600 hover:underline font-bold">X</button>
                                        </td>
                                    </tr>
                                    <tr v-if="form.productos.length === 0">
                                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">Carrito vacío. Agrega productos arriba.</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-50">
                                        <td colspan="4" class="px-4 py-3 text-right font-bold text-gray-900 uppercase">Total a Cobrar:</td>
                                        <td class="px-4 py-3 text-right font-bold text-lg text-indigo-600">Bs. {{ totalVenta.toFixed(2) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- 4. Botones de Acción -->
                        <div class="flex justify-end space-x-3">
                            <Link :href="route('ventas.index')" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">Cancelar</Link>
                            <button type="submit" :disabled="form.processing || form.productos.length === 0" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded disabled:opacity-50">
                                Procesar Venta
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    clientes: Array,
    productos: Array
});

// Variables locales para el selector de productos
const productoSeleccionado = ref('');
const cantidad = ref(1);

// Formulario que se enviará a Laravel
const form = useForm({
    fecha_venta: new Date().toISOString().slice(0, 10),
    id_cliente: '',
    tipo_venta: 'contado',
    observacion: '',
    productos: [] // Aquí se guardará el carrito
});

// Computed para calcular el Total dinámicamente
const totalVenta = computed(() => {
    return form.productos.reduce((total, item) => total + (item.cantidad * item.precio_venta), 0);
});

// Funciones del Carrito
const agregarAlCarrito = () => {
    if (!productoSeleccionado.value || cantidad.value < 1) {
        alert("Selecciona un producto y una cantidad válida.");
        return;
    }

    if (cantidad.value > productoSeleccionado.value.stock_actual) {
        alert("No hay suficiente stock. Disponible: " + productoSeleccionado.value.stock_actual);
        return;
    }

    // Verificar si el producto ya está en el carrito para sumar cantidad
    const existe = form.productos.find(p => p.id_producto === productoSeleccionado.value.id);
    
    if (existe) {
        if ((existe.cantidad + cantidad.value) > productoSeleccionado.value.stock_actual) {
            alert("La cantidad total supera el stock disponible.");
            return;
        }
        existe.cantidad += cantidad.value;
    } else {
        form.productos.push({
            id_producto: productoSeleccionado.value.id,
            codigo: productoSeleccionado.value.codigo,
            nombre: productoSeleccionado.value.nombre,
            precio_venta: productoSeleccionado.value.precio_venta,
            cantidad: cantidad.value
        });
    }

    // Reiniciar selectores
    productoSeleccionado.value = '';
    cantidad.value = 1;
};

const quitarDelCarrito = (index) => {
    form.productos.splice(index, 1);
};

// Enviar a Laravel
const guardarVenta = () => {
    if (form.productos.length === 0) {
        alert("Debes agregar al menos un producto a la venta.");
        return;
    }
    form.post(route('ventas.store'));
};
</script>