<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

const page = usePage();
const marca = ref(page.props.marca); // Se obtiene la marca a editar
const form = useForm({
  nombre: marca.value.nombre,        // nombre de la marca
  descripcion: marca.value.descripcion, // descripción de la marca
});

// Enviar el formulario con la información actualizada de la marca
const submitForm = () => {
  form.post(route('admin.marcas.update', { marca: marca.value.id }), {
    onSuccess: () => {
      console.log('Marca actualizada exitosamente');
    },
    onError: (errors) => {
      console.error('Error al actualizar:', errors);
    },
  });
};
</script>

<template>
  <Head title="Editar Marca" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Editar Marca</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <form @submit.prevent="submitForm">
              <div class="space-y-4">
                <div>
                  <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre de la Marca</label>
                  <input
                    v-model="form.nombre"
                    type="text"
                    id="nombre"
                    name="nombre"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    required
                  />
                </div>

                <div>
                  <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                  <input
                    v-model="form.descripcion"
                    type="text"
                    id="descripcion"
                    name="descripcion"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    required
                  />
                </div>
              </div>

              <div class="mt-6 flex items-center justify-end">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md">Editar Marca</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Puedes agregar estilos adicionales aquí */
</style>
