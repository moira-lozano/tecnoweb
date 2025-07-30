<template>
  <Head title="Productos" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Ventas</h2>
    </template>
    <div>
      <!-- Productos -->
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
              <section id="contenido_principal">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                  <Producto
                    v-for="producto in productos"
                    :key="producto.id"
                    :producto="producto"
                    @agregar-carrito="agregarCarrito"
                  />
                </div>
              </section>
            </div>
          </div>
        </div>
      </div>
      <!-- Carrito -->
      <Carrito
        :carrito="carrito"
        @compra-realizada="vaciarCarrito"
        @aumentar="aumentarCantidad"
        @disminuir="disminuirCantidad"
        @quitar="quitarProducto"
      />
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import Producto from '@/Components/producto.vue';
import Carrito from '@/Components/Carrito.vue';

const page = usePage();
const productos = ref(page.props.productos);
const carrito = ref([]);

const vaciarCarrito = () => {
  carrito.value = [];
};

const agregarCarrito = (producto) => {
  console.log('Agregando producto al carrito:', producto);
  const index = carrito.value.findIndex((p) => p.id === producto.id);
  if (index !== -1) {
    carrito.value[index].cantidad += 1;
    carrito.value[index].total = carrito.value[index].cantidad * getPrice(carrito.value[index]);
  } else {
    const precio = getPrice(producto);
    const nuevoItem = { 
      ...producto, 
      cantidad: 1, 
      total: precio,
      precio: precio // Asegurar que el precio se mantenga consistente
    };
    carrito.value.push(nuevoItem);
    console.log('Nuevo producto agregado:', nuevoItem);
  }
  carrito.value = [...carrito.value]; // Forzar reactividad
};

const aumentarCantidad = (producto) => {
  const item = carrito.value.find(p => p.id === producto.id);
  if (item) item.cantidad++;
};

const disminuirCantidad = (producto) => {
  const item = carrito.value.find(p => p.id === producto.id);
  if (item && item.cantidad > 1) item.cantidad--;
};

const quitarProducto = (producto) => {
  carrito.value = carrito.value.filter(p => p.id !== producto.id);
};

// Función helper para obtener el precio correcto (considerando ofertas)
const getPrice = (producto) => {
  if (producto.isOferta === 1 && producto.descuento) {
    return producto.precio - (producto.precio * producto.descuento) / 100;
  }
  return producto.precio;
};
</script>