<template>
    <Head title="Registrar Compra" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Registrar Nueva Compra
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    
                    <form @submit.prevent="submit">
                        <!-- SECCIÓN 1: DATOS GENERALES -->
                        <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Datos de la Compra</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            
                            <!-- Fecha de Compra -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fecha de Compra</label>
                                <input v-model="form.fecha_compra" type="date" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <div v-if="form.errors.fecha_compra" class="text-red-500 text-xs mt-1">{{ form.errors.fecha_compra }}</div>
                            </div>

                            <!-- Proveedor -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Proveedor</label>
                                <select v-model="form.id_proveedor" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="" disabled>Seleccione un proveedor...</option>
                                    <option v-for="prov in proveedores" :key="prov.id" :value="prov.id">
                                        {{ prov.nombre }} (NIT: {{ prov.nit }})
                                    </option>
                                </select>
                                <div v-if="form.errors.id_proveedor" class="text-red-500 text-xs mt-1">{{ form.errors.id_proveedor }}</div>
                            </div>

                            <!-- Observación -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Observación (Opcional)</label>
                                <input v-model="form.observacion" type="text" placeholder="Ej. Factura #1234"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- SECCIÓN 2: AGREGAR PRODUCTOS -->
                        <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2 mt-8">Detalle de Productos</h3>
                        
                        <!-- Controles para añadir al carrito -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 items-end bg-gray-50 p-4 rounded-lg border">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Producto</label>
                                <select v-model="tempProducto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="" disabled>Seleccione producto...</option>
                                    <option v-for="prod in productos" :key="prod.id" :value="prod">
                                        {{ prod.codigo }} - {{ prod.nombre }} (Stock: {{ prod.stock_actual }})
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                                <input v-model.number="tempCantidad" type="number" min="1"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Costo Unit. (Bs)</label>
                                <div class="flex">
                                    <input v-model.number="tempPrecio" type="number" min="0" step="0.01"
                                        class="mt-1 block w-full rounded-l-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <button @click.prevent="agregarProductoAlDetalle" 
                                        class="mt-1 bg-green-600 hover:bg-green-700 text-white px-4 rounded-r-md transition-colors">
                                        Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div v-if="form.errors.productos" class="text-red-500 text-xs mb-4 text-center font-bold">
                            {{ form.errors.productos }}
                        </div>

                        <!-- Tabla dinámica de detalle -->
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-sm text-left text-gray-500 border">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                                    <tr>
                                        <th class="px-4 py-2 border-b">Código</th>
                                        <th class="px-4 py-2 border-b">Producto</th>
                                        <th class="px-4 py-2 border-b text-center">Cantidad</th>
                                        <th class="px-4 py-2 border-b text-right">Precio U.</th>
                                        <th class="px-4 py-2 border-b text-right">Subtotal</th>
                                        <th class="px-4 py-2 border-b text-center">Quitar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in form.productos" :key="index" class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-4 py-2">{{ item.codigo }}</td>
                                        <td class="px-4 py-2 font-medium text-gray-900">{{ item.nombre }}</td>
                                        <td class="px-4 py-2 text-center">{{ item.cantidad }}</td>
                                        <td class="px-4 py-2 text-right">{{ item.precio_compra.toFixed(2) }}</td>
                                        <td class="px-4 py-2 text-right font-bold text-gray-700">{{ (item.cantidad * item.precio_compra).toFixed(2) }}</td>
                                        <td class="px-4 py-2 text-center">
                                            <button @click.prevent="quitarProducto(index)" class="text-red-600 hover:text-red-800 font-bold">X</button>
                                        </td>
                                    </tr>
                                    <tr v-if="form.productos.length === 0">
                                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">No hay productos agregados al detalle.</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-100 font-bold text-gray-800 text-lg">
                                        <td colspan="4" class="px-4 py-3 text-right">TOTAL A PAGAR:</td>
                                        <td class="px-4 py-3 text-right">Bs. {{ totalCompra.toFixed(2) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex justify-end space-x-3 mt-6">
                            <Link :href="route('compras.index')" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition-colors">
                                Cancelar
                            </Link>
                            <button type="submit" :disabled="form.processing || form.productos.length === 0" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition-colors disabled:opacity-50">
                                Guardar Compra
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

// Props recibidos desde el controlador
const props = defineProps({
    proveedores: Array,
    productos: Array
});

// Inicializamos el formulario con Inertia
const form = useForm({
    fecha_compra: new Date().toISOString().split('T')[0], // Fecha actual por defecto
    id_proveedor: '',
    observacion: '',
    productos: [] // Aquí se guardará el array de detalle
});

// Variables temporales para los inputs del carrito
const tempProducto = ref('');
const tempCantidad = ref(1);
const tempPrecio = ref(0);

// Función para agregar al array dinámico
const agregarProductoAlDetalle = () => {
    if (!tempProducto.value || tempCantidad.value <= 0 || tempPrecio.value < 0) {
        alert("Por favor seleccione un producto, con cantidad mayor a 0 y un precio válido.");
        return;
    }

    // Verificamos si el producto ya está en la lista para no duplicarlo, si está, sumamos la cantidad
    const indexExistente = form.productos.findIndex(p => p.id_producto === tempProducto.value.id);
    
    if (indexExistente !== -1) {
        form.productos[indexExistente].cantidad += tempCantidad.value;
        // Si el precio cambió, actualizamos al nuevo precio
        form.productos[indexExistente].precio_compra = tempPrecio.value; 
    } else {
        form.productos.push({
            id_producto: tempProducto.value.id,
            codigo: tempProducto.value.codigo,
            nombre: tempProducto.value.nombre,
            cantidad: tempCantidad.value,
            precio_compra: tempPrecio.value
        });
    }

    // Limpiar campos temporales
    tempProducto.value = '';
    tempCantidad.value = 1;
    tempPrecio.value = 0;
};

// Función para remover un ítem del array
const quitarProducto = (index) => {
    form.productos.splice(index, 1);
};

// Computed property para calcular el total en tiempo real
const totalCompra = computed(() => {
    return form.productos.reduce((suma, item) => suma + (item.cantidad * item.precio_compra), 0);
});

// Función para enviar los datos al backend
const submit = () => {
    form.post(route('compras.store'));
};
</script>