<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

const page = usePage();

// Formulario para crear Comisión
const form = useForm({
  licencia_id: '',
  porcentaje: '',
});

// Enviar formulario
const submitForm = () => {
  form.post(route('admin.comisiones.store'), {
    onSuccess: () => {
      console.log('Comisión creada exitosamente');
    },
    onError: (errors) => {
      console.error('Error al crear:', errors);
      alert('Error: ' + JSON.stringify(errors));
    },
  });
};
</script>

<template>
  <Head title="Crear Comisión" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Crear Comisión
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <form @submit.prevent="submitForm" class="space-y-4">
              <!-- Licencia -->
              <div>
                <label for="licencia_id" class="block text-sm font-medium text-gray-700">Licencia</label>
                <select
                  v-model="form.licencia_id"
                  id="licencia_id"
                  name="licencia_id"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  required
                >
                  <option value="" disabled>Seleccione una licencia</option>
                  <option v-for="licencia in page.props.licencias" :key="licencia.id" :value="licencia.id">
                    {{ licencia.nombre }}
                  </option>
                </select>
                <p v-if="form.errors.licencia_id" class="text-sm text-red-500 mt-2">{{ form.errors.licencia_id }}</p>
              </div>

              <!-- Porcentaje -->
              <div>
                <label for="porcentaje" class="block text-sm font-medium text-gray-700">Porcentaje (%)</label>
                <input
                  v-model="form.porcentaje"
                  type="number"
                  step="0.01"
                  id="porcentaje"
                  name="porcentaje"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  required
                />
                <p v-if="form.errors.porcentaje" class="text-sm text-red-500 mt-2">{{ form.errors.porcentaje }}</p>
              </div>

              <!-- Botón -->
              <div class="mt-6 flex items-center justify-end">
                <button
                  type="submit"
                  class="bg-green-500 text-white px-4 py-2 rounded-md"
                >
                  Agregar Comisión
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
/* Puedes agregar estilos personalizados aquí si lo deseas */
</style>
