<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Inertia } from "@inertiajs/inertia";

const page = usePage();

const onDeleteSuccess = (e) => {
    console.log(e);
    marcas.value = e.props.marcas.data;
};

// Datos de marcas
const marcasData = ref(page.props.marcas || {});
const marcas = ref(marcasData.value.data || []);
const currentPage = ref(marcasData.value.current_page || 1);
const lastPage = ref(marcasData.value.last_page || 1);

// Cambiar de página
const changePage = (url) => {
  if (url) {
    Inertia.get(url, {}, { preserveState: true, replace: true });
  }
};

// Calcular páginas visibles
const visiblePages = computed(() => {
  let startPage = currentPage.value - 1;
  let endPage = currentPage.value + 1;

  if (startPage < 1) {
    startPage = 1;
    endPage = Math.min(3, lastPage.value);
  }
  if (endPage > lastPage.value) {
    endPage = lastPage.value;
    startPage = Math.max(lastPage.value - 2, 1);
  }

  return marcasData.value.links.filter((link) => {
    if (isNaN(link.label)) return false;
    const pageNumber = Number(link.label);
    return pageNumber >= startPage && pageNumber <= endPage;
  });
});
</script>

<template>
  <Head title="Marcas" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Marcas
      </h2>
    </template>

    <div>
      <!-- Tabla de marcas -->
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
              <section id="contenido_principal">
                <div class="col-md-12 mt-2 mb-4">
                  <Link
                    :href="route('admin.marcas.create')"
                    method="get"
                    as="button"
                    class="bg-blue-500 text-white px-4 py-2 rounded"
                  >
                    Crear Marca
                  </Link>
                </div>

                <div class="col-md-12">
                  <div class="box box-default border border-gray-800">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-2">
                      <div class="overflow-auto">
                        <table class="table table-bordered table-condensed table-striped w-full">
                          <thead style="background-color: #dff1ff;">
                            <tr>
                              <th class="text-center">Nro</th>
                              <th class="text-center">Nombre</th>
                              <th class="text-center">Descripción</th>
                              <th class="text-center">Acción</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="marca in marcas" :key="marca.id">
                              <td class="text-center">{{ marca.id }}</td>
                              <td class="text-center">{{ marca.nombre }}</td>
                              <td class="text-center">{{ marca.descripcion }}</td>
                              <td class="text-center space-x-2">
                                <Link
                                  :href="route('admin.marcas.edit', marca)"
                                  method="get"
                                  class="bg-yellow-500 text-white p-2 rounded"
                                  as="button"
                                >
                                  Editar
                                </Link>
                                <Link
                                  @success="onDeleteSuccess"
                                  :href="route('admin.marcas.destroy', marca)"
                                  method="delete"
                                  class="bg-red-500 text-white p-2 rounded"
                                  as="button"
                                >
                                  Eliminar
                                </Link>
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <!-- Paginación -->
                        <div v-if="marcasData && marcasData.data">
                          <div class="mt-4 flex justify-center gap-2">
                            <button
                              v-if="marcasData.prev_page_url"
                              @click="changePage(marcasData.prev_page_url)"
                              class="px-4 py-2 rounded border bg-gray-100 text-gray-600 hover:bg-gray-200"
                            >
                              Anterior
                            </button>

                            <button
                              v-for="link in visiblePages"
                              :key="link.url"
                              @click="changePage(link.url)"
                              :disabled="!link.url || link.active"
                              class="px-4 py-2 rounded border"
                              :class="{
                                'bg-blue-500 text-white': link.active,
                                'bg-gray-100 text-gray-600 hover:bg-gray-200': !link.active
                              }"
                            >
                              {{ link.label }}
                            </button>

                            <button
                              v-if="marcasData.next_page_url"
                              @click="changePage(marcasData.next_page_url)"
                              class="px-4 py-2 rounded border bg-gray-100 text-gray-600 hover:bg-gray-200"
                            >
                              Siguiente
                            </button>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </section>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
