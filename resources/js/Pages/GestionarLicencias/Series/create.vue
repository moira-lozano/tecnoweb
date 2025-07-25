<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

// Datos iniciales del formulario para Series
const form = useForm({
  serial: '',
  cantidad_equipo: 0,
  estado: 0, // 1: Activo, 0: Inactivo
  precio: 0.00,
  duracion: 0,
  fecha_inicio: '',
  fecha_finalizacion: '',
});

// Enviar formulario
const submitForm = () => {
  form.post(route('admin.series.store'), {
    onSuccess: () => {
      console.log('Serie creada exitosamente');
    },
    onError: (errors) => {
      console.error('Error al crear:', errors);
      alert('Error: ' + JSON.stringify(errors));
    },
  });
};
</script>

<template>
  <Head title="Crear Serie" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Crear Serie
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <form @submit.prevent="submitForm">
              <div class="space-y-4">

                <!-- Serial -->
                <div>
                  <label for="serial" class="block text-sm font-medium text-gray-700">
                    Serial
                  </label>
                  <input
                    v-model="form.serial"
                    type="text"
                    id="serial"
                    name="serial"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    required
                  />
                  <p v-if="form.errors.serial" class="text-sm text-red-500 mt-2">{{ form.errors.serial }}</p>
                </div>

                <!-- Cantidad -->
                <div>
                  <label for="cantidad_equipo" class="block text-sm font-medium text-gray-700">
                    Cantidad de Equipo
                  </label>
                  <input
                    v-model="form.cantidad_equipo"
                    type="number"
                    id="cantidad_equipo"
                    name="cantidad_equipo"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  />
                  <p v-if="form.errors.cantidad_equipo" class="text-sm text-red-500 mt-2">{{ form.errors.cantidad_equipo }}</p>
                </div>

                <!-- Estado -->
                <div>
                  <label for="estado" class="block text-sm font-medium text-gray-700">
                    Estado
                  </label>
                  <select
                    v-model="form.estado"
                    id="estado"
                    name="estado"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  >
                    <option :value="1">Activo</option>
                    <option :value="0">Inactivo</option>
                  </select>
                  <p v-if="form.errors.estado" class="text-sm text-red-500 mt-2">{{ form.errors.estado }}</p>
                </div>

                <!-- Precio -->
                <div>
                  <label for="precio" class="block text-sm font-medium text-gray-700">
                    Precio
                  </label>
                  <input
                    v-model="form.precio"
                    type="number"
                    step="0.01"
                    id="precio"
                    name="precio"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  />
                  <p v-if="form.errors.precio" class="text-sm text-red-500 mt-2">{{ form.errors.precio }}</p>
                </div>

                <!-- Duración -->
                <div>
                  <label for="duracion" class="block text-sm font-medium text-gray-700">
                    Duración
                  </label>
                  <input
                    v-model="form.duracion"
                    type="number"
                    id="duracion"
                    name="duracion"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  />
                  <p v-if="form.errors.duracion" class="text-sm text-red-500 mt-2">{{ form.errors.duracion }}</p>
                </div>

                <!-- Fecha Inicio -->
                <div>
                  <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">
                    Fecha de Inicio
                  </label>
                  <input
                    v-model="form.fecha_inicio"
                    type="date"
                    id="fecha_inicio"
                    name="fecha_inicio"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  />
                  <p v-if="form.errors.fecha_inicio" class="text-sm text-red-500 mt-2">{{ form.errors.fecha_inicio }}</p>
                </div>

                <!-- Fecha Finalización -->
                <div>
                  <label for="fecha_finalizacion" class="block text-sm font-medium text-gray-700">
                    Fecha de Finalización
                  </label>
                  <input
                    v-model="form.fecha_finalizacion"
                    type="date"
                    id="fecha_finalizacion"
                    name="fecha_finalizacion"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  />
                  <p v-if="form.errors.fecha_finalizacion" class="text-sm text-red-500 mt-2">{{ form.errors.fecha_finalizacion }}</p>
                </div>

                <!-- Botón -->
                <div class="mt-6 flex items-center justify-end">
                  <button
                    type="submit"
                    class="bg-green-500 text-white px-4 py-2 rounded-md"
                  >
                    Agregar Serie
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
