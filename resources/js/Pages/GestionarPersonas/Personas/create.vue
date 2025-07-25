<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
const props = defineProps({
  tipos: Array, // Define que se recibe un array de roles
});
const form = useForm({
  nombre: '',
  cedula: '',
  celular: '',
  direccion: '',
    email: '',
    tipo: '',  // Campo para el tipo
});

const submitForm = () => {
  form.post(route('admin.personas.store'), {
    onSuccess: () => {
      // Redirigir o mostrar un mensaje de éxito
      console.log('personas creado exitosamente');
    },
    onError: () => {
      console.log('Hubo un error al crear  personas');
    },
  });
};
</script>

<template>
  <Head title="Crear Persona" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Crear Persona</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <form @submit.prevent="submitForm">
              <div class="space-y-4">
                <div>
                  <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                  <input v-model="form.nombre" type="text" id="nombre" name="nombre" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"  />
                  <p v-if="form.errors.nombre" class="text-sm text-red-500 mt-2">{{ form.errors.nombre }}</p>
                </div>

                <div>
                  <label for="cedula" class="block text-sm font-medium text-gray-700">Cédula</label>
                  <input v-model="form.cedula" type="text" id="cedula" name="cedula" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                  <p v-if="form.errors.cedula" class="text-sm text-red-500 mt-2">{{ form.errors.cedula }}</p>
                </div>

                <div>
                  <label for="celular" class="block text-sm font-medium text-gray-700">Celular</label>
                  <input v-model="form.celular" type="text" id="celular" name="celular" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"  />
                  <p v-if="form.errors.celular" class="text-sm text-red-500 mt-2">{{ form.errors.celular }}</p>
                </div>

                <div>
                  <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
                  <input v-model="form.direccion" type="text" id="direccion" name="direccion" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                  <p v-if="form.errors.direccion" class="text-sm text-red-500 mt-2">{{ form.errors.direccion }}</p>
                </div>

                <div>
                  <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                  <input v-model="form.email" type="email" id="email" name="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"  />
                  <p v-if="form.errors.email" class="text-sm text-red-500 mt-2">{{ form.errors.email }}</p>
                </div>
              </div>



              <!-- Select de Rol -->
              <div>
  <label for="tipo" class="block text-sm font-medium text-gray-700">tipo</label>
  <select v-model="form.tipo" id="tipo" name="tipo" class="mt-1 block w-full border-2 border-gray-400 text-gray-800 bg-white rounded-md shadow-sm" >
    <option value="">Seleccionar tipo</option>
    <option v-for="tipo in props.tipos" :key="tipo.id" :value="tipo.id">{{ tipo.nombre }}</option>
  </select>
  <p v-if="form.errors.tipo" class="text-sm text-red-500 mt-2">{{ form.errors.tipo }}</p>
</div>


              <div class="mt-6 flex items-center justify-end">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md">Crear Persona</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Puedes agregar algunos estilos adicionales si lo deseas */
</style>
