<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

const page = usePage();

// Listas de series y marcas desde props
const seriesOptions = page.props.series || [];
const marcasOptions = page.props.marcas || [];

const form = useForm({
  nombre: '',
  serie_id: '',
  marca_id: '',
  precio: 0.00,
  precio_mayorista: 0.00,
  precio_renovacion: 0.00,
  compartida: '0',
  trasladable: '0',
  caducable: '0',
  formateable: '0',
  compra_asistida: '0',
});

const submitForm = () => {
  form.post(route('admin.licencias.store'), {
    onSuccess: () => {
      console.log('Licencia creada exitosamente');
    },
    onError: (errors) => {
      console.error('Error al crear:', errors);
      alert('Error: ' + JSON.stringify(errors));
    },
  });
};
</script>

<template>
  <Head title="Crear Licencia" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Crear Licencia
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <form @submit.prevent="submitForm">
              <div class="space-y-4">

                <!-- Nombre -->
                <div>
                  <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                  <input
                    v-model="form.nombre"
                    type="text"
                    id="nombre"
                    name="nombre"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    required
                  />
                  <p v-if="form.errors.nombre" class="text-sm text-red-500 mt-2">{{ form.errors.nombre }}</p>
                </div>

                <!-- Serie -->
                <div>
                  <label for="serie_id" class="block text-sm font-medium text-gray-700">Serie</label>
                  <select
                    v-model="form.serie_id"
                    id="serie_id"
                    name="serie_id"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    required
                  >
                    <option value="" disabled>-- Seleccionar Serie --</option>
                    <option v-for="serie in seriesOptions" :key="serie.id" :value="serie.id">
                      {{ serie.serial }}
                    </option>
                  </select>
                  <p v-if="form.errors.serie_id" class="text-sm text-red-500 mt-2">{{ form.errors.serie_id }}</p>
                </div>

                <!-- Marca -->
                <div>
                  <label for="marca_id" class="block text-sm font-medium text-gray-700">Marca</label>
                  <select
                    v-model="form.marca_id"
                    id="marca_id"
                    name="marca_id"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    required
                  >
                    <option value="" disabled>-- Seleccionar Marca --</option>
                    <option v-for="marca in marcasOptions" :key="marca.id" :value="marca.id">
                      {{ marca.nombre }}
                    </option>
                  </select>
                  <p v-if="form.errors.marca_id" class="text-sm text-red-500 mt-2">{{ form.errors.marca_id }}</p>
                </div>

                <!-- Precio -->
                <div>
                  <label for="precio" class="block text-sm font-medium text-gray-700">Precio</label>
                  <input
                    v-model="form.precio"
                    type="number"
                    step="0.01"
                    id="precio"
                    name="precio"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    required
                  />
                  <p v-if="form.errors.precio" class="text-sm text-red-500 mt-2">{{ form.errors.precio }}</p>
                </div>

                <!-- Precio Mayorista -->
                <div>
                  <label for="precio_mayorista" class="block text-sm font-medium text-gray-700">Precio Mayorista</label>
                  <input
                    v-model="form.precio_mayorista"
                    type="number"
                    step="0.01"
                    id="precio_mayorista"
                    name="precio_mayorista"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  />
                  <p v-if="form.errors.precio_mayorista" class="text-sm text-red-500 mt-2">{{ form.errors.precio_mayorista }}</p>
                </div>

                <!-- Precio Renovación -->
                <div>
                  <label for="precio_renovacion" class="block text-sm font-medium text-gray-700">Precio Renovación</label>
                  <input
                    v-model="form.precio_renovacion"
                    type="number"
                    step="0.01"
                    id="precio_renovacion"
                    name="precio_renovacion"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  />
                  <p v-if="form.errors.precio_renovacion" class="text-sm text-red-500 mt-2">{{ form.errors.precio_renovacion }}</p>
                </div>

                <!-- Compartida -->
                <div>
                  <label for="compartida" class="block text-sm font-medium text-gray-700">Compartida</label>
                  <select
                    v-model="form.compartida"
                    id="compartida"
                    name="compartida"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  >
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                  </select>
                  <p v-if="form.errors.compartida" class="text-sm text-red-500 mt-2">{{ form.errors.compartida }}</p>
                </div>

                <!-- Trasladable -->
                <div>
                  <label for="trasladable" class="block text-sm font-medium text-gray-700">Trasladable</label>
                  <select
                    v-model="form.trasladable"
                    id="trasladable"
                    name="trasladable"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  >
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                  </select>
                  <p v-if="form.errors.trasladable" class="text-sm text-red-500 mt-2">{{ form.errors.trasladable }}</p>
                </div>

                <!-- Caducable -->
                <div>
                  <label for="caducable" class="block text-sm font-medium text-gray-700">Caducable</label>
                  <select
                    v-model="form.caducable"
                    id="caducable"
                    name="caducable"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  >
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                  </select>
                  <p v-if="form.errors.caducable" class="text-sm text-red-500 mt-2">{{ form.errors.caducable }}</p>
                </div>

                <!-- Formateable -->
                <div>
                  <label for="formateable" class="block text-sm font-medium text-gray-700">Formateable</label>
                  <select
                    v-model="form.formateable"
                    id="formateable"
                    name="formateable"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  >
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                  </select>
                  <p v-if="form.errors.formateable" class="text-sm text-red-500 mt-2">{{ form.errors.formateable }}</p>
                </div>

                <!-- Compra Asistida -->
                <div>
                  <label for="compra_asistida" class="block text-sm font-medium text-gray-700">Compra Asistida</label>
                  <select
                    v-model="form.compra_asistida"
                    id="compra_asistida"
                    name="compra_asistida"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  >
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                  </select>
                  <p v-if="form.errors.compra_asistida" class="text-sm text-red-500 mt-2">{{ form.errors.compra_asistida }}</p>
                </div>

                <!-- Botón -->
                <div class="mt-6 flex items-center justify-end">
                  <button
                    type="submit"
                    class="bg-green-500 text-white px-4 py-2 rounded-md"
                  >
                    Agregar Licencia
                  </button>
                </div>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Estilos personalizados opcionales */
</style>
