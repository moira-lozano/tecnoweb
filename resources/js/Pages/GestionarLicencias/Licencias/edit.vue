<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

const page = usePage();
const serie = ref(page.props.serie); // Se obtiene la serie a editar

// Crear el formulario con los datos actuales de la serie
const form = useForm({
  serial: serie.value.serial,
  cantidad_equipo: serie.value.cantidad_equipo,
  estado: serie.value.estado,
  precio: serie.value.precio,
  duracion: serie.value.duracion,
  fecha_inicio: serie.value.fecha_inicio,
  fecha_finalizacion: serie.value.fecha_finalizacion,
});

// Enviar el formulario con la información actualizada de la serie
const submitForm = () => {
  form.put(route('admin.series.update', { serie: serie.value.id }), {
    onSuccess: () => {
      console.log('Serie actualizada exitosamente');
    },
    onError: (errors) => {
      console.error('Error al actualizar:', errors);
    },
  });
};
</script>

<template>
  <Head title="Editar Serie" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Editar Serie</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <form @submit.prevent="submitForm">
              <div class="space-y-4">
                <!-- Serial -->
                <div>
                  <label for="serial" class="block text-sm font-medium text-gray-700">Serial</label>
                  <input
                    v-model="form.serial"
                    type="text"
                    id="serial"
                    name="serial"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    required
                  />
                </div>

                <!-- Cantidad de equipo -->
                <div>
                  <label for="cantidad_equipo" class="block text-sm font-medium text-gray-700">Cantidad de equipo</label>
                  <input
                    v-model="form.cantidad_equipo"
                    type="number"
                    id="cantidad_equipo"
                    name="cantidad_equipo"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  />
                </div>

                <!-- Estado -->
                <div>
                  <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                  <select
                    v-model="form.estado"
                    id="estado"
                    name="estado"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  >
                    <option :value="1">Activo</option>
                    <option :value="0">Inactivo</option>
                  </select>
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
                  />
                </div>

                <!-- Duración -->
                <div>
                  <label for="duracion" class="block text-sm font-medium text-gray-700">Duración</label>
                  <input
                    v-model="form.duracion"
                    type="number"
                    id="duracion"
                    name="duracion"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  />
                </div>

                <!-- Fecha inicio -->
                <div>
                  <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha de inicio</label>
                  <input
                    v-model="form.fecha_inicio"
                    type="date"
                    id="fecha_inicio"
                    name="fecha_inicio"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  />
                </div>

                <!-- Fecha finalización -->
                <div>
                  <label for="fecha_finalizacion" class="block text-sm font-medium text-gray-700">Fecha de finalización</label>
                  <input
                    v-model="form.fecha_finalizacion"
                    type="date"
                    id="fecha_finalizacion"
                    name="fecha_finalizacion"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  />
                </div>
              </div>

              <div class="mt-6 flex items-center justify-end">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md">
                  Editar Serie
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Estilos opcionales */
</style>
