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
              <div class="flex flex-col items-start gap-1">
                <div class="flex items-center gap-2">
                  <!-- En Carrito.vue, en los botones -->
                  <button
                    @click="handleDisminuir(item)"
                    class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-700 transition-colors"
                    :disabled="item.cantidad <= 1"
                  >−</button>

                  <button
                    @click="handleAumentar(item)"
                    class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-700 transition-colors"
                    :disabled="item.cantidad >= item.cantidadDisponible"
                  >+</button>

                  <span class="text-sm">{{ item.cantidad }}</span>  
                </div>

                <!-- Mensaje de error si excede stock -->
                <div v-if="item.mensajeError" class="text-sm text-red-500 bg-red-50 px-2 py-1 rounded">
                  {{ item.mensajeError }}
                </div>
                
                <!-- Info de stock disponible -->
                <p class="text-xs text-gray-500">
                  Disponible: {{ item.cantidadDisponible }} unidades
                </p>
                <!-- Indicador visual de stock bajo -->
                <div v-if="item.cantidadDisponible <= 5" class="text-xs text-orange-600 bg-orange-50 px-2 py-1 rounded">
                  ⚠️ Stock limitado
                </div>
              </div>
            </td>

            <td class="border px-4 py-2 dark:text-white">
              <div class="flex flex-col">
                <span class="font-medium">$ {{ formatearPrecio(getPrecioUnitario(item)) }}</span>
                <span v-if="item.isOferta === 1" class="text-xs text-gray-500 line-through">
                  $ {{ formatearPrecio(item.precio) }}
                </span>
                <span v-if="item.descuento > 0" class="text-xs text-green-600">
                  -{{ item.descuento }}% OFF
                </span>
              </div>
            </td>

            <td class="border px-4 py-2 dark:text-white font-semibold">
              $ {{ formatearPrecio(item.cantidad * getPrecioUnitario(item)) }}
            </td>

            <td class="border px-4 py-2 text-center">
              <button
                @click="emit('quitar', item)"
                class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-800 transition-colors"
                title="Eliminar producto"
              >
                🗑️ Eliminar
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Resumen del carrito -->
      <div class="mt-4 p-4 bg-gray-200 dark:bg-gray-700 rounded-lg">
        <div class="space-y-2">
          <div class="flex justify-between items-center text-sm">
            <span class="text-gray-700 dark:text-gray-300">
              Productos ({{ totalProductos }} items):
            </span>
            <span class="text-gray-900 dark:text-gray-100">
              $ {{ formatearPrecio(subtotal) }}
            </span>
          </div>
          
          <div v-if="totalDescuentos > 0" class="flex justify-between items-center text-sm text-green-600">
            <span>Descuentos aplicados:</span>
            <span>-$ {{ formatearPrecio(totalDescuentos) }}</span>
          </div>
          
          <hr class="border-gray-400 dark:border-gray-600">
          
          <div class="flex justify-between items-center">
            <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">
              Total del Carrito:
            </span>
            <span class="text-xl font-bold text-green-600 dark:text-green-400">
              $ {{ formatearPrecio(calcularTotalCarrito()) }}
            </span>
          </div>
        </div>
      </div>
      
      <!-- Botones de acción -->
      <div class="mt-4 flex justify-between items-center">
        <button 
          @click="emit('vaciar-carrito')" 
          class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition-colors"
        >
          Vaciar Carrito
        </button>
        
        <div class="flex gap-2">
          <button 
            @click="continuarComprando" 
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors"
          >
            Continuar Comprando
          </button>
          
          <button 
            @click="irAPagar()" 
            class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition-colors font-semibold"
            :disabled="!puedeComprar"
            :class="{ 'opacity-50 cursor-not-allowed': !puedeComprar }"
          >
            Proceder al Pago
          </button>
        </div>
      </div>
      
      <!-- Mensaje de advertencia si hay problemas de stock -->
      <div v-if="tieneProblemasStock" class="mt-4 p-3 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
        <div class="flex">
          <div class="flex-shrink-0">
            ⚠️
          </div>
          <div class="ml-3">
            <p class="text-sm">
              Algunos productos en tu carrito tienen stock limitado. Revisa las cantidades antes de continuar.
            </p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Carrito vacío -->
    <div v-else class="text-gray-500 dark:text-gray-400 mt-4 text-center py-12">
      <div class="text-6xl mb-4">🛒</div>
      <h3 class="text-xl font-semibold mb-2">Tu carrito está vacío</h3>
      <p class="text-gray-400 mb-4">¡Agrega algunos productos para comenzar!</p>
      <button 
        @click="continuarComprando"
        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition-colors"
      >
        Explorar Productos
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  carrito: {
    type: Array,
    required: true
  }
});

const emit = defineEmits(['aumentar', 'disminuir', 'quitar', 'vaciar-carrito']);

// Computed properties para el resumen del carrito
const totalProductos = computed(() => {
  return props.carrito.reduce((total, item) => total + item.cantidad, 0);
});

// En Carrito.vue script
const handleAumentar = (item) => {
  console.log('Intentando aumentar:', item);
  emit('aumentar', item);
};

const handleDisminuir = (item) => {
  console.log('Intentando disminuir:', item);
  emit('disminuir', item);
};

const subtotal = computed(() => {
  return props.carrito.reduce((total, item) => {
    return total + (item.cantidad * item.precio);
  }, 0);
});

const totalDescuentos = computed(() => {
  return props.carrito.reduce((total, item) => {
    if (item.isOferta === 1 && item.descuento > 0) {
      const descuentoUnitario = (item.precio * item.descuento) / 100;
      return total + (descuentoUnitario * item.cantidad);
    }
    return total;
  }, 0);
});

const tieneProblemasStock = computed(() => {
  return props.carrito.some(item => 
    item.mensajeError || item.cantidad >= item.cantidadDisponible
  );
});

const puedeComprar = computed(() => {
  return props.carrito.length > 0 && !props.carrito.some(item => item.mensajeError);
});

// Métodos
const getPrecioUnitario = (item) => {
  if (item.isOferta === 1 && item.descuento > 0) {
    return item.precio - (item.precio * item.descuento) / 100;
  }
  return item.precio;
};

const calcularTotalCarrito = () => {
  return props.carrito.reduce((total, item) => {
    return total + (item.cantidad * getPrecioUnitario(item));
  }, 0);
};

const formatearPrecio = (precio) => {
  return Number(precio || 0).toFixed(2);
};

const continuarComprando = () => {
  // Redirigir al catálogo o cerrar el carrito
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const irAPagar = () => {
  if (!puedeComprar.value) {
    alert('Por favor, revisa los productos en tu carrito antes de continuar.');
    return;
  }

  if (props.carrito.length === 0) {
    alert('El carrito está vacío.');
    return;
  }

  // 🔥 PREPARAR datos normalizados para el carrito
  const carritoNormalizado = props.carrito.map(item => ({
    id: item.id,
    nombre: item.nombre,
    cantidad: parseInt(item.cantidad) || 1,
    precio: parseFloat(item.precio) || 0,
    precio_unitario: getPrecioUnitario(item),
    isOferta: item.isOferta || 0,
    descuento: item.descuento || 0,
    total: item.cantidad * getPrecioUnitario(item),
    cantidadDisponible: item.cantidadDisponible || 0
  }));

  console.log('🛒 Carrito normalizado para pago:', carritoNormalizado);
  console.log('💰 Total del carrito:', calcularTotalCarrito());

  // 🔥 GUARDAR en localStorage ANTES de redirigir
  try {
    localStorage.setItem('carrito', JSON.stringify(carritoNormalizado));
    console.log('✅ Carrito guardado en localStorage correctamente');
    
    // Verificar que se guardó correctamente
    const verificacion = localStorage.getItem('carrito');
    console.log('🔍 Verificación localStorage:', verificacion);
    
  } catch (error) {
    console.error('❌ Error guardando en localStorage:', error);
    alert('Hubo un problema al guardar los datos. Inténtalo de nuevo.');
    return;
  }

  // 🔥 REDIRIGIR a la página de pago
  try {
    // Opción 1: Intentar con la ruta de pagos
    router.visit(route('landing.pagos.formulario'));
  } catch {
    try {
      // Opción 2: Intentar ruta alternativa
      router.visit('/pagos/create');
    } catch {
      // Opción 3: Fallback manual
      window.location.href = '/pagos/create';
    }
  }
};
</script>

<style scoped>
.line-through {
  text-decoration: line-through;
}

/* Animaciones sutiles */
.transition-colors {
  transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
}

/* Mejor espaciado en móviles */
@media (max-width: 640px) {
  .table-auto {
    font-size: 0.875rem;
  }
  
  .px-4 {
    padding-left: 0.5rem;
    padding-right: 0.5rem;
  }
}
</style>