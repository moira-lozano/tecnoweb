<script setup>
import { ref } from 'vue';
import { useForm , router} from '@inertiajs/vue3';
import Header from '@/Pages/LandingPage/Components/Header.vue';
import { onMounted } from "vue";


const qrImage = ref('');
const numeroTransaccion = ref('');
const modalMessage = ref('');
const isModalVisible = ref(false);

const props = defineProps({
  numeroTransaccion: String,
  qrImage: String,
});

qrImage.value = props.qrImage;

const validateForm = () => {
  const errors = {};

  if (!form.name) {
    errors.name = 'El nombre es obligatorio.';
  }

  if (!form.cedula) {
    errors.cedula = 'La cédula es obligatoria.';
  }

  if (!form.tcCorreo) {
    errors.tcCorreo = 'El correo es obligatorio.';
  } else if (!/\S+@\S+\.\S+/.test(form.tcCorreo)) {
    errors.tcCorreo = 'Por favor ingresa un correo válido.';
  }

  if (!form.tnTelefono) {
    errors.tnTelefono = 'El teléfono es obligatorio.';
  }

  if (!form.tcDireccion) {
    errors.tcDireccion = 'La direccion es obligatorio.';
  }

  if (!form.tcRazonSocial) {
    errors.tcRazonSocial = 'La razón social es obligatoria.';
  }

  if (!form.tnMonto || form.tnMonto <= 0) {
    errors.tnMonto = 'El monto total debe ser mayor a 0.';
  }

  if (!form.tnTipoServicio) {
    errors.tnTipoServicio = 'Selecciona un tipo de servicio.';
  }

  return errors;
};

const form = useForm({
  name: '',
  cedula: '',
  precio: '',
  tnTelefono: '',
  tcRazonSocial: '',
  tcCorreo: '',
  tcDireccion: '',
  tnMonto: 0.01,
  taPedidoDetalle: [],
  tnTipoServicio: '',
});

const submitForm = () => {
  form.errors = {};
  const errors = validateForm();

  if (Object.keys(errors).length > 0) {
    for (const [key, value] of Object.entries(errors)) {
      form.errors[key] = value;
    }
    return;
  }

  const pedidoDetalle = localStorage.getItem('carrito');
  if (pedidoDetalle) {
    try {
      form.taPedidoDetalle = JSON.parse(pedidoDetalle);
    } catch (error) {
      console.error('Error al parsear los datos de pedidoDetalle:', error);
    }
  }

  // ✅ Si es efectivo, solo guardar la venta sin llamar a PagoFácil
  if (form.tnTipoServicio == 3) {
    console.log('📝 Datos que se envían al servidor:', form.data());
    console.log('🛒 Carrito actual:', carrito.value);
    
    // 🧪 TEMPORAL: Primero hacer debug
    axios.post('/debug-pago', form.data())
      .then(debugResponse => {
        console.log('🔍 Debug exitoso:', debugResponse.data);
        
        // Ahora hacer la petición real
        return axios.post(route('landing.pagos.generarCobro'), form.data());
      })
      .then(response => {
        console.log('✅ Respuesta del servidor:', response.data);
        showModal("Su pedido fue registrado exitosamente. Por favor pague en efectivo.");
        localStorage.removeItem('carrito');
      })
      .catch(error => {
        
        // Mostrar mensaje de error más específico
        let errorMessage = 'Error al procesar el pago';
        if (error.response?.data?.message) {
          errorMessage = error.response.data.message;
        } else if (error.response?.data?.errors) {
          errorMessage = Object.values(error.response.data.errors).flat().join(', ');
        } else if (error.response?.status === 500) {
          errorMessage = 'Error interno del servidor. Revisa los logs.';
        }
        
        showModal("Error: " + errorMessage);
      });
    return;
  }

  // ✅ Para QR o Tigo Money
  console.log('📝 Datos para QR/Tigo Money:', form.data());
  
  axios.post(route('landing.pagos.generarCobro'), form.data())
    .then(response => {
      console.log('✅ Respuesta QR/Tigo Money:', response.data);
      qrImage.value = response.data.qrImage;
      numeroTransaccion.value = response.data.nroTransaccion;
      consultarTransaccion(numeroTransaccion.value);
    })
    .catch(error => {
      console.error('❌ Error QR/Tigo Money:', error);
      console.error('❌ Respuesta del servidor:', error.response?.data);
      showModal("Error: " + (error.response?.data?.message || 'Error al procesar el pago'));
    });
};

// Función para consultar la transacción
const consultarTransaccion = (nroTransaccion) => {
  const intervalID = setInterval(async () => {
    try {
      console.log('Entrando en la consulta de transacción:', nroTransaccion);

      const response = await axios.get(route('landing.pagos.consultar'), {
        params: { venta_id: nroTransaccion },
      });

      if (response.data.trim() === "COMPLETADO-PROCESADO") {
        clearInterval(intervalID);
        showModal("Su pedido fue pagado con éxito!!!!");
      }
    } catch (error) {
      console.error('Error al consultar la transacción:', error);
    }
  }, 10000);
};

const carrito = ref([]);

onMounted(() => {
  const data = localStorage.getItem("carrito");
  console.log("📦 Datos del carrito desde localStorage:", data); // 👈 DEBUG
  
  if (data) {
    try {
      carrito.value = JSON.parse(data);
      console.log("🛒 Carrito parseado:", carrito.value); // 👈 DEBUG
      
      form.taPedidoDetalle = carrito.value;
      form.tnMonto = calcularTotal();
      
      console.log("💰 Total calculado:", form.tnMonto); // 👈 DEBUG
    } catch (error) {
      console.error("❌ Error al parsear carrito:", error);
    }
  } else {
    console.warn("⚠️ No hay datos en localStorage");
  }
});

const calcularTotal = () => {
  const total = carrito.value.reduce((acc, item) => {
    const precioUnitario = getPrecioUnitario(item);
    const subtotal = item.cantidad * precioUnitario;
    console.log(`📊 Item: ${item.nombre}, Cantidad: ${item.cantidad}, Precio: ${precioUnitario}, Subtotal: ${subtotal}`);
    return acc + subtotal;
  }, 0);
  return total;
};

// ✅ FUNCIÓN MEJORADA para obtener precio unitario
const getPrecioUnitario = (item) => {
  // Primero verifica si ya tiene precio_unitario calculado
  if (item.precio_unitario !== undefined && item.precio_unitario !== null) {
    return Number(item.precio_unitario);
  }
  
  // Si no, calcula basado en ofertas
  if (item.isOferta === 1 && item.descuento) {
    const precioConDescuento = item.precio - (item.precio * item.descuento) / 100;
    return Number(precioConDescuento);
  }
  
  // Precio normal
  return Number(item.precio || 0);
};

const formatearPrecio = (valor) => {
  return Number(valor || 0).toFixed(2);
};

function showModal(mensaje) {
  modalMessage.value = mensaje;
  isModalVisible.value = true;
}

function closeModal() {
   isModalVisible.value = false;
   router.visit('/'); // 👈 redirige a la ruta del carrito
}
</script>

<template>
  <Header />
  <!-- Modal de éxito -->
  <div v-if="isModalVisible" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-lg shadow-lg">
      <p class="text-lg font-bold text-center">{{ modalMessage }}</p>
      <div class="mt-4 flex justify-center">
        <button @click="closeModal" class="bg-blue-500 text-white p-2 rounded">Cerrar</button>
      </div>
    </div>
  </div>

  <div class="contenido-principal mt-16">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <div class="mb-6 text-center">
            <h3 class="text-3xl font-bold">PagoFacil QR y Tigo Money</h3>
            <p class="text-blue-500">Integración de servicios PagoFacil</p>
          </div>

          <div class="flex flex-col md:flex-row space-y-6 md:space-y-0 md:space-x-6">
            <div class="md:w-1/2 space-y-4">
              <h3 class="text-2xl font-bold">Datos para la compra</h3>
              <form @submit.prevent="submitForm" target="QrImage">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                  <div class="space-y-6">
                    <div>
                      <label for="name" class="block text-sm font-medium dark:text-gray-100 text-gray-700">Nombre</label>
                      <input v-model="form.name" type="text" id="name" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-gray-900 dark:text-gray-100 dark:bg-gray-700 bg-white" required />
                      <p v-if="form.errors.name" class="text-sm text-red-500 mt-2">{{ form.errors.name }}</p>
                    </div>

                    <div>
                      <label for="cedula" class="block text-sm font-medium dark:text-gray-100 text-gray-700">Cédula</label>
                      <input v-model="form.cedula" type="text" id="cedula" name="cedula" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-gray-900 dark:text-gray-100 dark:bg-gray-700 bg-white" required />
                      <p v-if="form.errors.cedula" class="text-sm text-red-500 mt-2">{{ form.errors.cedula }}</p>
                    </div>

                    <div>
                      <label for="email" class="block text-sm font-medium dark:text-gray-100  text-gray-700">Correo</label>
                      <input v-model="form.tcCorreo" type="email" id="email" name="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-gray-900 dark:text-gray-100 dark:bg-gray-700 bg-white" required />
                      <p v-if="form.errors.tcCorreo" class="text-sm text-red-500 mt-2">{{ form.errors.tcCorreo }}</p>
                    </div>

                    <div>
                      <label for="telefono" class="block text-sm font-medium dark:text-gray-100  text-gray-700">Teléfono</label>
                      <input v-model="form.tnTelefono" type="text" id="telefono" name="telefono" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-gray-900 dark:text-gray-100 dark:bg-gray-700 bg-white" required />
                      <p v-if="form.errors.tnTelefono" class="text-sm text-red-500 mt-2">{{ form.errors.tnTelefono }}</p>
                    </div>
                  </div>

                  <div class="space-y-6">
                    <div>
                      <label for="direccion" class="block text-sm font-medium dark:text-gray-100 text-gray-700">Direccion</label>
                      <input v-model="form.tcDireccion" type="text" id="direccion" name="direccion" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-gray-900 dark:text-gray-100 dark:bg-gray-700 bg-white" required />
                      <p v-if="form.errors.tcDireccion" class="text-sm text-red-500 mt-2">{{ form.errors.tcDireccion }}</p>
                    </div>

                    <div>
                      <label for="tcRazonSocial" class="block text-sm font-medium dark:text-gray-100  text-gray-700">Razón Social</label>
                      <input v-model="form.tcRazonSocial" type="text" id="tcRazonSocial" name="tcRazonSocial" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-gray-900 dark:text-gray-100 dark:bg-gray-700 bg-white" required />
                      <p v-if="form.errors.tcRazonSocial" class="text-sm text-red-500 mt-2">{{ form.errors.tcRazonSocial }}</p>
                    </div>

                    <div>
                      <label for="tnMonto" class="block text-sm font-medium dark:text-gray-100  text-gray-700">Monto Total</label>
                      <input v-model="form.tnMonto" type="number" step="0.01" id="tnMonto" name="tnMonto" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-gray-900 dark:text-gray-100 dark:bg-gray-700 bg-white" required />
                      <p v-if="form.errors.tnMonto" class="text-sm text-red-500 mt-2">{{ form.errors.tnMonto }}</p>
                    </div>

                    <div>
                      <label for="tipoServicio" class="block text-sm font-medium dark:text-gray-100 text-gray-700">Tipo de Servicio</label>
                      <select v-model="form.tnTipoServicio" id="tipoServicio" class="mt-1 block w-full border p-2 rounded-md shadow-sm text-gray-900 dark:text-gray-100 dark:bg-gray-700 bg-white">
                        <option value="">Seleccionar...</option>
                        <option value="1">Servicio QR</option>
                        <option value="2">Tigo Money</option>
                        <option value="3">Efectivo</option>
                      </select>
                      <p v-if="form.errors.tnTipoServicio" class="text-sm text-red-500 mt-2">{{ form.errors.tnTipoServicio }}</p>
                    </div>
                  </div>
                </div>

                <!-- ✅ SECCIÓN MEJORADA DEL DETALLE DE COMPRA -->
                <div class="mb-10 bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                  <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Detalle de la Compra</h3>
                  
                  <!-- 👈 Mostrar mensaje si no hay productos -->
                  <div v-if="!carrito || carrito.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <div class="text-4xl mb-2">🛒</div>
                    <p>No hay productos en el carrito</p>
                    <p class="text-sm mt-2">Regresa al catálogo para agregar productos</p>
                  </div>

                  <!-- 👈 Tabla de productos -->
                  <div v-else>
                    <table class="w-full text-sm text-left border-collapse">
                      <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white">
                          <th class="border px-4 py-2">Producto</th>
                          <th class="border px-4 py-2 text-center">Cantidad</th>
                          <th class="border px-4 py-2 text-right">Precio Unitario</th>
                          <th class="border px-4 py-2 text-right">Subtotal</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="item in carrito"
                          :key="item.id"
                          class="border-b dark:border-gray-700 text-gray-900 dark:text-white"
                        >
                          <td class="border px-4 py-2">{{ item.nombre || 'Producto sin nombre' }}</td>
                          <td class="border px-4 py-2 text-center">{{ item.cantidad || 0 }}</td>
                          <td class="border px-4 py-2 text-right">$ {{ formatearPrecio(getPrecioUnitario(item)) }}</td>
                          <td class="border px-4 py-2 text-right">
                            $ {{ formatearPrecio(item.cantidad * getPrecioUnitario(item)) }}
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    
                    <div class="text-right text-lg mt-4 text-gray-900 dark:text-white">
                      <strong>Total:</strong> $ {{ formatearPrecio(calcularTotal()) }}
                    </div>
                  </div>
                </div>

                <div class="mt-6 flex items-center justify-center">
                  <button type="submit" class="bg-blue-500 dark:text-gray-100 text-white px-4 py-2 rounded-md">Generar Pago</button>
                </div>
              </form>
            </div>

            <div class="md:w-1/2">
              <h3 class="text-2xl font-bold text-center"></h3>
              <div class="flex justify-center">
                <iframe name="QrImage" style="width: 100%; height: 495px;" :src="qrImage"></iframe>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Estilos adicionales si los necesitas */
</style>