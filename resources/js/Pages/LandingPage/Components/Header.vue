<template >
      <div :class="`${themeClass} ${isDarkMode ? 'dark' : 'light'} ${fontClass}`">
  <header class=" bg-gray-900 text-white py-4 px-8 shadow-md flex items-center justify-between space-x-4">
  <!-- Logo -->
  <div class="flex items-center space-x-2">
    <img :src="'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTsFLEf31cOfWNroxrDcNgkXm5dYP4PoyFiXA&s'" alt="Visal Import Export S.A." class="h-12" />
    <span class="text-lg font-bold tracking-wide">LIcences-guard</span>
  </div>

  <!-- Buscador centrado -->
  <div class="flex-grow flex justify-center">
   <div class="flex-center">
              <form @submit.prevent="submitForm" class="flex-left w-full max-w-4xl">
                <div class="relative flex items-stretch w-full">
                  <input
                    type="search"
                    v-model="form.search"
                    placeholder="Buscar productos"
                    class="block p-4 w-full text-sm text-gray-900 bg-white rounded-lg border"
                  />
                  <button
                    type="submit"
                    class="ml-2 text-red bg--500 hover:bg-blue-400 focus:outline-none"
                  >
                    Buscar
                  </button>
                </div>
              </form>
            </div>
  </div>

  <!-- Navegación -->
  <nav>
    <ul class="flex space-x-6 items-center">

    <li v-if="!user">
      <Link :href="route('login')" method="get" as="button">Login</Link>

    </li>

    <li v-else>
      <!-- Logout usando href con confirmación -->
      <Link   class="text-sm font-semibold bg-red-600 text-white px-2 py-2 rounded-full hover:bg-red-700 transition"
         :href="route('logout')" method="post" as="button" @click.prevent="logout">Log Out</Link>

    </li>
    <li v-if="!user">
        <Link  class="text-lg font-semibold bg-orange-500 text-white py-2 px-4 rounded-full hover:bg-orange-600 transition"
         :href="route('register')" method="get" as="button">Register</Link>
      </li>
  </ul>
  </nav>
</header>
</div>
  </template>

  <script setup>

  import { Link, useForm } from '@inertiajs/vue3';
  import { ref } from 'vue';
  import {usePage } from '@inertiajs/vue3';
const page = usePage();
const user = ref(page.props.user);
const selectedTheme = ref(''); // Tema seleccionado
const isDarkMode = ref(false); // Estado del modo oscuro/claro
const themeClass = ref('default-theme'); // Clase aplicada al tema
const themeTextClass = ref(''); // Clase de color de texto
const fontClass = ref(''); // Clase para la fuente
  const form = useForm({
    search: '',
  });
  const logout = () => {
  if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
    window.location.href = route('/');  // Redirige a la ruta logout
  }
};
  const emit = defineEmits(['search']);
  const submitForm = () => {
    emit('search', form.search); // Emitir evento con el término de búsqueda
  };


  const applyTheme = () => {
  switch (selectedTheme.value) {
    case 'niño':
    themeClass.value = 'bg-niño-light dark:bg-niño-dark text-gray-800 dark:text-white font-niño';
    fontClass.value = 'font-niño'; // Aplicar fuente para el tema niño
      break;
    case 'adolescente':
    themeClass.value = 'bg-adolescente-light dark:bg-adolescente-dark text-white dark:text-white font-adolescente';
    fontClass.value = 'font-adolescente';
      break;
    case 'adulto':
    themeClass.value = 'bg-adulto-light dark:bg-adulto-dark text-gray-800 dark:text-white font-adulto';
    fontClass.value = 'font-adulto';
      break;
    default:
          themeClass.value = 'bg-default';
          fontClass.value = '';
  }
      localStorage.setItem('theme', themeClass.value);
      localStorage.setItem('font', fontClass.value);
  };

/*   const setThemeBasedOnTime = () => {
  const currentHour = new Date().getHours();
  isDarkMode.value = currentHour >= 19 || currentHour < 7;
  }; */


const savedTheme = localStorage.getItem('theme');
const savedFont = localStorage.getItem('font');
  if (savedTheme) {
    themeClass.value = savedTheme;
    selectedTheme.value = savedTheme;
  } else {
      localStorage.setItem('theme', 'bg-adulto-light dark:bg-adulto-dark text-gray-800 dark:text-white');
      themeClass.value = 'bg-adulto-light dark:bg-adulto-dark text-gray-800 dark:text-white';
      selectedTheme.value =  'adulto';
  }
  if (savedFont) {
  fontClass.value = savedFont;
}



// Llama a la función inmediatamente al cargar para aplicar el tema desde el principio
setThemeBasedOnTime();
  </script>

  <style scoped>

  /* Estilos básicos para el header */
  .site-navbar {
    position: fixed; /* Fijar el header en la parte superior */
    top: 0;
    left: 0;
    right: 0;
    background-color: #2e77d6;
    padding: 10px 0;
    color: white;
    z-index: 1000; /* Asegura que el header esté sobre otros elementos */
  }

  .site-logo {
    font-size: 1.8rem;
    color: white;
    text-decoration: none;
  }

  .nav-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex; /* Usar flex para alinear los items en una fila */
  }

  .nav-list li {
    margin: 0 20px;
  }

  .nav-list li a,


  .nav-list li a:hover,
  .nav-list li button:hover {
    text-decoration: underline;
  }

  body {
    padding-top: 70px; /* Ajusta este valor dependiendo de la altura de tu header */
  }

  /* Organiza el header */
  .d-flex {
    display: flex;
    justify-content: space-between; /* Distribuye el contenido entre los elementos */
    align-items: center;
    width: 100%;
  }

  /* Contenedor de logo alineado a la izquierda */
  .flex-left {
    display: flex;
    align-items: center;
  }

  /* Contenedor del buscador centrado */
  .flex-center {
    display: flex;
    justify-content: center; /* Centra el contenido */
    flex: 1; /* Hace que el contenedor ocupe el máximo espacio disponible */
    text-align: center; /* Asegura que el formulario se centre */
  }

  /* Contenedor del formulario de búsqueda con ancho máximo más grande */
  form.w-full {
    max-width: 700px; /* Aumento el tamaño máximo del formulario */
  }

  /* Contenedor del menú de navegación alineado a la derecha */
  .flex-right {
    display: flex;
    align-items: center;
    margin-left: auto; /* Alinea los elementos de navegación a la derecha */
  }

/* Definir las fuentes para cada tema */
.font-niño {
  font-family: 'Comic Sans MS', cursive;
  font-size: 1.5rem;
}

.font-adolescente {
  font-family: 'Dancing Script', cursive;
  font-size: 1.2rem;
}

.font-adulto {
  font-family: 'Roboto', sans-serif;
  font-size: 1.2rem;
}


  </style>
