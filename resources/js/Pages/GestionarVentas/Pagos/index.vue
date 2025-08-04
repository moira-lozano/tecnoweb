<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const page = usePage();
// Aseguramos que pagos sea un objeto de paginación
const pagosData = ref(page.props.pagos || {});
const pagos = ref(pagosData.value.data || []);

// Manejar éxito de eliminación (si implementas eliminar pagos luego)
const onDeleteSuccess = (e) => {
  console.log(e);
  pagosData.value = e.props.pagos || {};
  pagos.value = pagosData.value.data || [];
};
</script>

<template>
  <Head title="Pagos" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Pagos
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <section id="contenido_principal">
              <div class="col-md-12">
                <div class="box box-default border border-gray-500">
                  <div class="overflow-auto p-4">
                    <table class="table table-bordered table-condensed table-striped w-full">
                      <thead>
                        <tr class="bg-gray-100">
                          <th style="text-align: center;">Nro</th>
                          <th style="text-align: center;">Venta</th>
                          <th style="text-align: center;">Fecha Pago</th>
                          <th style="text-align: center;">Estado</th>
                          <th style="text-align: center;">Método de Pago</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="pago in pagos" :key="pago.id">
                          <td style="text-align: center;">{{ pago.id }}</td>
                           <td style="text-align: center;">{{ pago.venta_id }}</td>
                          <td style="text-align: center;">{{ pago.fechapago }}</td>
                          <td
                            style="text-align: center;"
                            :style="{ color: pago.estado === 2 ? 'green' : 'grease' }"
                          >
                            {{ pago.estado === 2 ? 'Pagado' : 'Pendiente' }}
                          </td>
                          <td style="text-align: center;">
                            {{ pago.metodopago == '3' ? 'Efectivo' : 'Pago QR' }}
                          </td>
                        </tr>
                      </tbody>
                    </table>

                    <!-- Ejemplo de paginación simple -->
                    <div v-if="pagosData.links" class="mt-4 flex justify-center gap-2">
                      <button
                        v-for="link in pagosData.links"
                        :key="link.url"
                        @click="$inertia.get(link.url)"
                        v-html="link.label"
                        :disabled="!link.url"
                        class="px-3 py-1 border rounded"
                        :class="{
                          'bg-blue-500 text-white': link.active,
                          'bg-gray-100': !link.active
                        }"
                      ></button>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
