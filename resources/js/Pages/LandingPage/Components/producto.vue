<template>
  <div class="relative bg-white dark:bg-gray-700 shadow rounded-lg p-4 flex flex-col">
    <!-- Etiqueta de oferta -->
    <div
      v-if="producto.isOferta === 1"
      class="absolute top-0 left-0 w-full bg-red-500 text-white text-xs font-bold text-center py-1 rounded-t-lg"
    >
      ¡Oferta!
    </div>

    <!-- Imagen del producto (placeholder por ahora) -->
    <div class="h-48 bg-gray-200 dark:bg-gray-600 rounded-lg mb-4 flex items-center justify-center" :class="{ 'mt-6': producto.isOferta === 1 }">
      <span class="text-gray-500 dark:text-gray-400">Imagen del producto</span>
    </div>

    <!-- Detalles del producto -->
    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mt-2">{{ producto.nombre }}</h3>
    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 flex-grow">{{ producto.descripcion }}</p>

    <!-- Precios -->
    <div class="mt-4">
      <div v-if="producto.isOferta === 1" class="mb-2">
        <div class="flex items-center gap-2">
          <span class="text-lg font-semibold text-gray-500 dark:text-gray-400 line-through">
            $ {{ formatearPrecio(producto.precio) }}
          </span>
          <span class="text-xl font-bold text-red-500">
            $ {{ formatearPrecio(calcularPrecioConDescuento(producto.precio, producto.descuento)) }}
          </span>
        </div>
        <p class="text-sm text-green-600 dark:text-green-400 font-medium">
          Ahorras {{ producto.descuento }}% ($ {{ formatearPrecio(producto.precio * producto.descuento / 100) }})
        </p>
      </div>
      <div v-else>
        <p class="text-xl font-bold text-gray-800 dark:text-gray-100">
          $ {{ formatearPrecio(producto.precio) }}
        </p>
      </div>
    </div>

    <!-- Botón de agregar al carrito -->
    <button
      @click="agregarCarrito"
      class="mt-4 w-full bg-green-500 hover:bg-green-600 active:bg-green-700 text-white px-4 py-3 rounded-md font-semibold transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50"
    >
      Agregar al Carrito
    </button>

    <!-- Botón para abrir modal de calificación (opcional) -->
    <button
      @click="openModal"
      class="mt-2 w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm transition-colors duration-200"
    >
      Calificar Producto
    </button>
  </div>

  <!-- Modal para ingresar datos -->
  <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-md w-full mx-4">
      <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Califica este Producto</h2>
      <form @submit.prevent="submitForm">
        <div class="mb-4">
          <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
          <input 
            type="text" 
            id="name" 
            v-model="formData.name" 
            class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-md text-gray-900 dark:text-gray-100 dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" 
            required 
          />
        </div>
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo electrónico</label>
          <input 
            type="email" 
            id="email" 
            v-model="formData.email" 
            class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-md text-gray-900 dark:text-gray-100 dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" 
            required 
          />
        </div>
        <div class="mb-4">
          <label for="cedula" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cédula</label>
          <input 
            type="text" 
            id="cedula" 
            v-model="formData.cedula" 
            class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-md text-gray-900 dark:text-gray-100 dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" 
            required 
          />
        </div>
        <div class="mb-4">
          <label for="celular" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Celular</label>
          <input 
            type="text" 
            id="celular" 
            v-model="formData.celular" 
            class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-md text-gray-900 dark:text-gray-100 dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" 
            required 
          />
        </div>
        <div class="mb-4">
          <label for="puntuacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Calificación</label>
          <select 
            id="puntuacion" 
            v-model="formData.puntuacion" 
            class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-md text-gray-900 dark:text-gray-100 dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" 
            required
          >
            <option value="">Selecciona una calificación</option>
            <option value="1">⭐ 1 - Muy malo</option>
            <option value="2">⭐⭐ 2 - Malo</option>
            <option value="3">⭐⭐⭐ 3 - Regular</option>
            <option value="4">⭐⭐⭐⭐ 4 - Bueno</option>
            <option value="5">⭐⭐⭐⭐⭐ 5 - Excelente</option>
          </select>
        </div>
        <div class="mb-4">
          <label for="comentario" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Comentario</label>
          <textarea
            id="comentario"
            v-model="formData.comentario"
            class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-md text-gray-900 dark:text-gray-100 dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
            rows="4"
            placeholder="Comparte tu experiencia con este producto..."
          ></textarea>
        </div>
        <div class="flex justify-end gap-2">
          <button 
            @click="closeModal" 
            type="button" 
            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition-colors"
          >
            Cancelar
          </button>
          <button 
            type="submit" 
            class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition-colors"
          >
            Enviar Calificación
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, computed } from 'vue';
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  producto: {
    type: Object,
    required: true
  },
});

// Asegurar que isOferta sea un número
if (props.producto && typeof props.producto.isOferta === 'string') {
  props.producto.isOferta = parseInt(props.producto.isOferta, 10);
}

const emit = defineEmits(['agregar-carrito']);

const isModalOpen = ref(false);
const formData = ref({
  name: '',
  email: '',
  cedula: '',
  celular: '',
  producto_id: props.producto.id,
  puntuacion: '',
  comentario: '',
});

const openModal = () => {
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
  // Limpiar formulario al cerrar
  formData.value = {
    name: '',
    email: '',
    cedula: '',
    celular: '',
    producto_id: props.producto.id,
    puntuacion: '',
    comentario: '',
  };
};

const submitForm = async () => {
  try {
    console.log('Enviando calificación:', formData.value);
    const response = await axios.post(route('landing.ratings.storerating'), formData.value);
    console.log('Éxito:', response.data);
    
    // Mostrar mensaje de éxito
    alert('¡Gracias por tu calificación!');
    
    closeModal();
    // Opcional: recargar la página o actualizar el componente
    // window.location.reload();
  } catch (error) {
    console.error('Error al enviar calificación:', error.response?.data || error.message);
    alert('Hubo un error al enviar tu calificación. Por favor, intenta nuevamente.');
  }
};

const agregarCarrito = () => {
  console.log('Agregando al carrito:', props.producto);
  emit('agregar-carrito', props.producto);
};

// Función para calcular el precio con descuento
const calcularPrecioConDescuento = (precio, descuento) => {
  return precio - (precio * descuento) / 100;
};

// Función para formatear precios
const formatearPrecio = (precio) => {
  return Number(precio).toFixed(2);
};

// Computed para obtener el precio final del producto
const precioFinal = computed(() => {
  if (props.producto.isOferta === 1 && props.producto.descuento) {
    return calcularPrecioConDescuento(props.producto.precio, props.producto.descuento);
  }
  return props.producto.precio;
});
</script>

<style scoped>
.line-through {
  text-decoration: line-through;
}

/* Animación para el modal */
.fixed {
  animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* Hover effects */
.hover\:bg-green-600:hover {
  background-color: #16a34a;
}

.hover\:bg-blue-600:hover {
  background-color: #2563eb;
}

.hover\:bg-gray-600:hover {
  background-color: #4b5563;
}
</style>