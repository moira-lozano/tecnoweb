<template>
  <div>
    <Header />
    
    <!-- Sección de productos -->
    <div class="dark:bg-gray-500 contenido-principal">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            
            <!-- Título y contador -->
            <div class="mb-6">
              <h1 class="text-2xl font-bold mb-2">Catálogo de Productos</h1>
              <p class="text-gray-600 dark:text-gray-400">
                {{ props.productos.length }} productos disponibles
                <span v-if="carrito.length > 0" class="ml-4 text-blue-600">
                  | {{ carrito.length }} en el carrito
                </span>
              </p>
            </div>

            <!-- Grid de productos -->
            <div class="contenido-principal mt-16">
              <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <Producto
                  v-for="producto in props.productos"
                  :key="producto.id"
                  :producto="producto"
                  :en-carrito="getProductoEnCarrito(producto.id)"
                  @agregar-carrito="agregarCarrito"
                />
              </div>
            </div>
          </div>
        </div>

        <!-- Componente Carrito -->
        <div class="mt-8">
          <Carrito
            :carrito="carrito"
            @aumentar="aumentar"
            @disminuir="disminuir"
            @quitar="quitar"
            @vaciar-carrito="vaciarCarrito"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import Header from '@/Components/Header.vue'; // Ajusta la ruta según tu estructura
import Producto from '@/Pages/LandingPage/Components/producto.vue';
import Carrito from '@/Pages/LandingPage/Components/Carrito.vue';

const props = defineProps({
  productos: {
    type: Array,
    required: true,
  },
});

const carrito = ref([]);

// 🔥 WATCH para guardar automáticamente en localStorage
watch(carrito, (nuevoCarrito) => {
  console.log('🔄 Carrito actualizado, guardando en localStorage:', nuevoCarrito);
  localStorage.setItem('carrito', JSON.stringify(nuevoCarrito));
}, { deep: true });

// 🔥 CARGAR carrito desde localStorage al montar el componente

onMounted(() => {
  const carritoGuardado = localStorage.getItem('carrito');
  if (carritoGuardado) {
    try {
      carrito.value = JSON.parse(carritoGuardado);
      console.log('📦 Carrito cargado desde localStorage:', carrito.value);
    } catch (error) {
      console.error('❌ Error al cargar carrito:', error);
      localStorage.removeItem('carrito'); // Limpiar localStorage corrupto
    }
  }
});

// Computed para obtener un producto específico del carrito
const getProductoEnCarrito = (productoId) => {
  return carrito.value.find(item => item.id === productoId);
};

// Agregar producto al carrito
const agregarCarrito = (producto) => {
  const itemExistente = carrito.value.find((p) => p.id === producto.id);
  
  if (itemExistente) {
    // Si ya existe, aumentar cantidad
    aumentar(itemExistente);
  } else {
    // Si no existe, agregar nuevo item
    carrito.value.push({
      id: producto.id,
      nombre: producto.nombre,
      cantidad: producto.cantidad || 1,
      precio: producto.precio,
      cantidadDisponible: producto.cantidad_disponible || producto.stock || 0,
      isOferta: producto.isOferta || 0,
      descuento: producto.descuento || 0,
      mensajeError: ''
    });
  }
};

// Aumentar cantidad con validación de stock
// Aumentar cantidad con mejor manejo de reactividad
const aumentar = (item) => {
  console.log('Aumentar ejecutado:', item);
  
  // Encontrar el item en el carrito
  const itemEnCarrito = carrito.value.find(p => p.id === item.id);
  
  if (itemEnCarrito && itemEnCarrito.cantidad < itemEnCarrito.cantidadDisponible) {
    itemEnCarrito.cantidad++;
    itemEnCarrito.mensajeError = '';
    console.log('Cantidad aumentada:', itemEnCarrito.cantidad);
  } else if (itemEnCarrito) {
    itemEnCarrito.mensajeError = `Solo hay ${itemEnCarrito.cantidadDisponible} unidades disponibles.`;
    setTimeout(() => {
      itemEnCarrito.mensajeError = '';
    }, 3000);
  }
};

const disminuir = (item) => {
  console.log('Disminuir ejecutado:', item);
  
  const itemEnCarrito = carrito.value.find(p => p.id === item.id);
  
  if (itemEnCarrito && itemEnCarrito.cantidad > 1) {
    itemEnCarrito.cantidad--;
    itemEnCarrito.mensajeError = '';
    console.log('Cantidad disminuida:', itemEnCarrito.cantidad);
  }
};

// Quitar producto del carrito
const quitar = (item) => {
  const index = carrito.value.findIndex(p => p.id === item.id);
  if (index !== -1) {
    carrito.value.splice(index, 1);
  }
};

// Vaciar todo el carrito
const vaciarCarrito = () => {
  if (confirm('¿Estás seguro de que quieres vaciar el carrito?')) {
    carrito.value = [];
  }
};

// Computed properties para estadísticas
const totalItems = computed(() => {
  return carrito.value.reduce((total, item) => total + item.cantidad, 0);
});

const totalCarrito = computed(() => {
  return carrito.value.reduce((total, item) => {
    const precioUnitario = item.isOferta === 1 && item.descuento > 0 
      ? item.precio - (item.precio * item.descuento / 100)
      : item.precio;
    return total + (item.cantidad * precioUnitario);
  }, 0);
});
</script>

<style scoped>
.contenido-principal {
  min-height: 400px;
}

/* Responsividad mejorada */
@media (max-width: 640px) {
  .grid {
    grid-template-columns: repeat(1, minmax(0, 1fr));
    gap: 1rem;
  }
}

@media (min-width: 641px) and (max-width: 768px) {
  .grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}
</style>  