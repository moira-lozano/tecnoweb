<template>
  <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg">
    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Carrito</h2>
    <div v-if="carrito && carrito.length > 0">
      <table class="w-full table-auto border-collapse border border-gray-300 dark:border-gray-700">
        <thead>
          <tr class="bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            <th class="border px-4 py-2 text-left">Producto</th>
            <th class="border px-4 py-2 text-left">Cantidad</th>
            <th class="border px-4 py-2 text-left">Precio</th>
            <th class="border px-4 py-2 text-left">Total</th>
            <th class="border px-4 py-2 text-left">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="item in carrito"
            :key="item.id"
            class="border-b border-gray-300 dark:border-gray-700"
          >
            <td class="border px-4 py-2 dark:text-white">{{ item.nombre }}</td>
            <td class="border px-4 py-2 dark:text-white">
              <div class="flex items-center gap-2">
                <button
                  @click="disminuir(item)"
                  class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-700 transition-colors"
                  :disabled="item.cantidad <= 1"
                  :class="{ 'opacity-50 cursor-not-allowed': item.cantidad <= 1 }"
                >−</button>
                <span class="mx-2 min-w-[30px] text-center">{{ item.cantidad }}</span>
                <button
                  @click="aumentar(item)"
                  class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-700 transition-colors"
                >+</button>
              </div>
            </td>
            <td class="border px-4 py-2 dark:text-white">$ {{ formatearPrecio(getPrecioUnitario(item)) }}</td>
            <td class="border px-4 py-2 dark:text-white">
              $ {{ formatearPrecio(item.cantidad * getPrecioUnitario(item)) }}
            </td>
            <td class="border px-4 py-2 text-center">
              <button
                @click="quitar(item)"
                class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-800 transition-colors"
                title="Eliminar producto"
              >🗑️</button>
            </td>
          </tr>
        </tbody>
      </table>
      
      <!-- Total del carrito -->
      <div class="mt-4 p-4 bg-gray-200 dark:bg-gray-700 rounded-lg">
        <div class="flex justify-between items-center">
          <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            Total del Carrito:
          </span>
          <span class="text-xl font-bold text-green-600 dark:text-green-400">
            $ {{ formatearPrecio(calcularTotalCarrito()) }}
          </span>
        </div>
      </div>
      
      <!-- Botón de pagar -->
      <div class="mt-4 flex justify-end">
        <button @click="irAPagar()" class="bg-blue-600 text-white px-4 py-2 rounded">
          Pagar
        </button>
      </div>
    </div>
    <div v-else class="text-gray-500 dark:text-gray-400 mt-4 text-center py-8">
      <div class="text-4xl mb-2">🛒</div>
      <p>El carrito está vacío.</p>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  carrito: {
    type: Array,
    required: true
  }
});

const emit = defineEmits(['aumentar', 'disminuir', 'quitar', 'comprar']);

const aumentar = (item) => emit('aumentar', item);
const disminuir = (item) => emit('disminuir', item);
const quitar = (item) => emit('quitar', item);

const getPrecioUnitario = (item) => {
  if (item.isOferta === 1 && item.descuento) {
    return item.precio - (item.precio * item.descuento) / 100;
  }
  return item.precio;
};

const calcularTotalCarrito = () => {
  return props.carrito.reduce((total, item) => {
    return total + item.cantidad * getPrecioUnitario(item);
  }, 0);
};

const formatearPrecio = (precio) => Number(precio).toFixed(2);

const irAPagar = () => {
  if (props.carrito.length > 0) {
    // ✅ NORMALIZAR DATOS antes de guardar en localStorage
    const carritoNormalizado = props.carrito.map(item => ({
      id: item.id,
      nombre: item.nombre,
      cantidad: item.cantidad,
      precio: item.precio,
      precio_unitario: getPrecioUnitario(item), // 👈 Calculamos y guardamos el precio unitario
      isOferta: item.isOferta || 0,
      descuento: item.descuento || 0
    }));
    
    localStorage.setItem("carrito", JSON.stringify(carritoNormalizado));
    
    // ✅ Opciones de rutas - usa la que corresponda a tu aplicación
    try {
      // Opción 1: Si usas route() helper
      router.visit(route('pagos.create'));
    } catch (error) {
      try {
        // Opción 2: Si está dentro del grupo 'landing'
        router.visit(route('landing.pagos.create'));
      } catch (error2) {
        // Opción 3: Ruta directa como fallback
        router.visit('/pagos/create');
      }
    }
  } else {
    alert('El carrito está vacío.');
  }
};
</script>