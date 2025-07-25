<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const page = usePage();
const ventasData = ref(page.props.ventas || {});
const ventas = ref(ventasData.value.data || []);

const onDeleteSuccess = (e) => {
  ventas.value = e.props.ventas.data;
};

// Opcional: si quieres paginar
const changePage = (url) => {
  if (url) {
    Inertia.get(url, {}, { preserveState: true, replace: true });
  }
};
</script>

<template>
    <Head title="Ventas" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Ventas
            </h2>
        </template>

        <div>
            <!-- Tabla de ventas -->
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <section id="contenido_principal">
                                <!-- Botón para crear venta -->
                                <div class="col-md-12 mb-4">
                                    <Link :href="route('admin.ventas.create')" method="get" as="button" class="bg-blue-500 text-white px-4 py-2 rounded">
                                        Crear Venta
                                    </Link>
                                </div>

                                <!-- Tabla -->
                                <div class="col-md-12">
                                    <div class="box box-default border border-gray-500">
                                        <div class="overflow-auto p-4">
                                            <table class="table table-bordered table-condensed table-striped w-full">
                                                <!-- Encabezados -->
                                                <thead>
                                                    <tr class="bg-gray-100">
                                                        <th style="text-align: center;">Nro</th>
                                                        <th style="text-align: center;">Comprador</th>
                                                        <th style="text-align: center;">Monto Total</th>
                                                        <th style="text-align: center;">Estado</th>
                                                        <th style="text-align: center;">Acción</th>
                                                    </tr>
                                                </thead>
                                               <tbody>
                                                    <tr v-for="venta in ventas" :key="venta.id">
                                                        <td style="text-align: center;">{{ venta.id }}</td>
                                                        <td style="text-align: center;">{{ venta.comprador?.nombre || '' }}</td>
                                                        <td style="text-align: center;">{{ Number(venta.montototal).toFixed(2) }}</td>
                                                        <td style="text-align: center;" :style="{ color: venta.estado === 1 ? 'green' : 'red' }">
                                                        {{ venta.estado === 1 ? 'Activo' : 'Inactivo' }}
                                                        </td>
                                                        <td style="text-align: center;">
                                                        <Link
                                                            @success="onDeleteSuccess"
                                                            :href="route('admin.ventas.destroy', venta.id)"
                                                            method="delete"
                                                            as="button"
                                                            class="bg-red-500 text-white px-4 py-2 rounded"
                                                        >
                                                            Eliminar
                                                        </Link>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                            </table>
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
