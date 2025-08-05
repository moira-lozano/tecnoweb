<script setup>
import { ref, onMounted, watch } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);

// 🎨 Sistema de temas
const temaActual = ref('light');
const temasDisponibles = {
  light: {
    nombre: 'Modo Diurno',
    icon: '☀️',
    classes: 'theme-light'
  },
  dark: {
    nombre: 'Modo Nocturno', 
    icon: '🌙',
    classes: 'theme-dark'
  },
  kids: {
    nombre: 'Modo Niños',
    icon: '🌈',
    classes: 'theme-kids'
  }
};

// Cargar tema guardado
onMounted(() => {
  const temaGuardado = localStorage.getItem('tema-sistema');
  if (temaGuardado && temasDisponibles[temaGuardado]) {
    temaActual.value = temaGuardado;
  }
  aplicarTema();
});

// Observar cambios de tema
watch(temaActual, () => {
  aplicarTema();
  localStorage.setItem('tema-sistema', temaActual.value);
});

const aplicarTema = () => {
  const body = document.body;
  // Remover todas las clases de tema
  Object.values(temasDisponibles).forEach(tema => {
    body.classList.remove(tema.classes);
  });
  // Aplicar el tema actual
  body.classList.add(temasDisponibles[temaActual.value].classes);
};

const cambiarTema = (nuevoTema) => {
  temaActual.value = nuevoTema;
};
</script>

<template>
    <div class="min-h-screen transition-all duration-300">
        <div class="min-h-screen bg-surface">
            <nav class="border-b border-primary/20 bg-nav shadow-lg">
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between items-center">
                        <div class="flex items-center">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('dashboard')" class="transform hover:scale-105 transition-transform duration-200">
                                    <ApplicationLogo class="block h-9 w-auto fill-current text-primary" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                    class="nav-link"
                                >
                                    🏠 Dashboard
                                </NavLink>
                            </div>
                        </div>

                        <!-- Selector de Tema -->
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <button class="theme-selector">
                                        <span class="text-lg">{{ temasDisponibles[temaActual].icon }}</span>
                                        <span class="ml-2 text-sm font-medium text-text-primary">
                                            {{ temasDisponibles[temaActual].nombre }}
                                        </span>
                                        <svg class="fill-current h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </template>
                                <template #content>
                                    <button
                                        v-for="(tema, key) in temasDisponibles"
                                        :key="key"
                                        @click="cambiarTema(key)"
                                        class="dropdown-theme-item"
                                        :class="{ 'active': temaActual === key }"
                                    >
                                        <span class="text-lg mr-3">{{ tema.icon }}</span>
                                        {{ tema.nombre }}
                                        <span v-if="temaActual === key" class="ml-auto text-primary">✓</span>
                                    </button>
                                </template>
                            </Dropdown>
                        </div>

                        <!-- Módulo Usuarios -->
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <button class="dropdown-button">
                                        <span class="mr-2">👥</span>
                                        <div>Módulo Usuarios</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </template>
                                <template #content>
                                    <NavLink :href="route('admin.users')" :active="route().current('admin.users')" class="dropdown-nav-link">
                                        👤 Usuarios
                                    </NavLink>
                                    <NavLink :href="route('admin.roles')" :active="route().current('admin.roles')" class="dropdown-nav-link">
                                        🎭 Roles
                                    </NavLink>
                                </template>
                            </Dropdown>
                        </div>

                        <!-- Módulo Personas -->
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <button class="dropdown-button">
                                        <span class="mr-2">👨‍👩‍👧‍👦</span>
                                        <div>Módulo Personas</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </template>
                                <template #content>
                                    <NavLink :href="route('admin.personas')" :active="route().current('admin.personas')" class="dropdown-nav-link">
                                        👤 Personas
                                    </NavLink>
                                    <NavLink :href="route('admin.tipos')" :active="route().current('admin.tipos')" class="dropdown-nav-link">
                                        🏷️ Tipos
                                    </NavLink>
                                </template>
                            </Dropdown>
                        </div>

                        <!-- Módulo Licencias -->
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <button class="dropdown-button">
                                        <span class="mr-2">📜</span>
                                        <div>Módulo Licencias</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </template>
                                <template #content>
                                    <NavLink :href="route('admin.licencias')" :active="route().current('admin.licencias')" class="dropdown-nav-link">
                                        📜 Licencias
                                    </NavLink>
                                    <NavLink :href="route('admin.series')" :active="route().current('admin.series')" class="dropdown-nav-link">
                                        🔢 Series
                                    </NavLink>
                                    <NavLink :href="route('admin.marcas')" :active="route().current('admin.marcas')" class="dropdown-nav-link">
                                        🏷️ Marcas
                                    </NavLink>
                                    <NavLink :href="route('admin.categorias')" :active="route().current('admin.categorias')" class="dropdown-nav-link">
                                        📁 Categorías
                                    </NavLink>
                                    <NavLink :href="route('admin.comisiones')" :active="route().current('admin.comisiones')" class="dropdown-nav-link">
                                        💰 Comisiones
                                    </NavLink>
                                    <NavLink :href="route('admin.detallecomisionvendedor')" :active="route().current('admin.detallecomisionvendedor')" class="dropdown-nav-link">
                                        👨‍💼 Vendedores/Comisiones
                                    </NavLink>
                                </template>
                            </Dropdown>
                        </div>

                        <!-- Módulo Ventas -->
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <button class="dropdown-button">
                                        <span class="mr-2">💳</span>
                                        <div>Módulo Ventas</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </template>
                                <template #content>
                                    <NavLink :href="route('admin.ventas')" :active="route().current('admin.ventas')" class="dropdown-nav-link">
                                        💳 Ventas
                                    </NavLink>
                                    <NavLink :href="route('admin.pagos')" :active="route().current('admin.pagos')" class="dropdown-nav-link">
                                        💰 Pagos
                                    </NavLink>
                                </template>
                            </Dropdown>
                        </div>

                        <!-- Módulo Reportes -->
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <button class="dropdown-button">
                                        <span class="mr-2">📊</span>
                                        <div>Módulo Reportes</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </template>
                                <template #content>
                                    <NavLink :href="route('admin.reportes')" :active="route().current('admin.reportes')" class="dropdown-nav-link">
                                        📊 Reportes
                                    </NavLink>
                                    <NavLink :href="route('admin.views')" :active="route().current('admin.views')" class="dropdown-nav-link">
                                        👁️ Views
                                    </NavLink>
                                </template>
                            </Dropdown>
                        </div>

                        <!-- User Settings Dropdown -->
                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="user-button">
                                                <span class="mr-2">👤</span>
                                                {{ $page.props.auth.user.name }}
                                                <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>
                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')" class="dropdown-nav-link">
                                            ⚙️ Perfil
                                        </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button" class="dropdown-nav-link text-red-600 hover:text-red-800">
                                            🚪 Cerrar Sesión
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Mobile menu button -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="mobile-menu-button"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{ 'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{ 'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Navigation Menu -->
                <div :class="{ 'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown }" class="sm:hidden mobile-menu">
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')" class="mobile-nav-link">
                            🏠 Dashboard
                        </ResponsiveNavLink>
                        
                        <!-- Selector de tema móvil -->
                        <div class="px-4 py-2">
                            <label class="block text-sm font-medium text-text-primary mb-2">🎨 Tema:</label>
                            <select v-model="temaActual" class="mobile-theme-select">
                                <option v-for="(tema, key) in temasDisponibles" :key="key" :value="key">
                                    {{ tema.icon }} {{ tema.nombre }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Mobile user info -->
                    <div class="border-t border-primary/20 pb-1 pt-4">
                        <div class="px-4">
                            <div class="text-base font-medium text-text-primary">{{ $page.props.auth.user.name }}</div>
                            <div class="text-sm font-medium text-text-secondary">{{ $page.props.auth.user.email }}</div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')" class="mobile-nav-link">⚙️ Perfil</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button" class="mobile-nav-link text-red-600">🚪 Cerrar Sesión</ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-nav shadow-md" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="bg-surface min-h-screen">
                <slot />
            </main>
        </div>
    </div>
</template>

<style>
/* ===== TEMA DIURNO (Light) ===== */
.theme-light {
  --color-primary: #3b82f6;
  --color-primary-dark: #1d4ed8;
  --color-surface: #f8fafc;
  --color-nav: #ffffff;
  --color-text-primary: #1f2937;
  --color-text-secondary: #6b7280;
  --color-border: #e5e7eb;
  --color-success: #10b981;
  --color-warning: #f59e0b;
  --color-error: #ef4444;
}

/* ===== TEMA NOCTURNO (Dark) ===== */
.theme-dark {
  --color-primary: #60a5fa;
  --color-primary-dark: #3b82f6;
  --color-surface: #111827;
  --color-nav: #1f2937;
  --color-text-primary: #f9fafb;
  --color-text-secondary: #d1d5db;
  --color-border: #374151;
  --color-success: #34d399;
  --color-warning: #fbbf24;
  --color-error: #f87171;
}

/* ===== TEMA NIÑOS (Kids) ===== */
.theme-kids {
  --color-primary: #ec4899;
  --color-primary-dark: #be185d;
  --color-surface: linear-gradient(135deg, #fef3c7 0%, #fed7e2 50%, #ddd6fe 100%);
  --color-nav: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4, #feca57);
  --color-text-primary: #1f2937;
  --color-text-secondary: #374151;
  --color-border: #f472b6;
  --color-success: #10b981;
  --color-warning: #f59e0b;
  --color-error: #ef4444;
}

/* ===== VARIABLES CSS GLOBALES ===== */
:root {
  --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ===== CLASES DE UTILIDAD ===== */
.bg-primary { background-color: var(--color-primary); }
.bg-surface { background: var(--color-surface); }
.bg-nav { background: var(--color-nav); }
.text-primary { color: var(--color-primary); }
.text-text-primary { color: var(--color-text-primary); }
.text-text-secondary { color: var(--color-text-secondary); }
.border-primary { border-color: var(--color-primary); }

/* ===== COMPONENTES ESPECÍFICOS ===== */

/* Navegación principal */
.nav-link {
  @apply inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition-all duration-200;
  color: var(--color-text-secondary);
  border-color: transparent;
}

.nav-link:hover {
  color: var(--color-text-primary);
  border-color: var(--color-primary);
  transform: translateY(-1px);
}

.nav-link.active {
  color: var(--color-primary);
  border-color: var(--color-primary);
  font-weight: 600;
}

/* Botones de dropdown */
.dropdown-button {
  @apply inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md transition-all duration-200;
  color: var(--color-text-secondary);
  background: var(--color-nav);
}

.dropdown-button:hover {
  color: var(--color-text-primary);
  background: var(--color-surface);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Selector de tema */
.theme-selector {
  @apply inline-flex items-center px-4 py-2 border-2 text-sm leading-4 font-medium rounded-lg transition-all duration-300;
  color: var(--color-text-primary);
  background: var(--color-nav);
  border-color: var(--color-primary);
}

.theme-selector:hover {
  background: var(--color-primary);
  color: white;
  transform: scale(1.05);
  box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

/* Items de dropdown de tema */
.dropdown-theme-item {
  @apply w-full text-left px-4 py-3 text-sm transition-all duration-200 flex items-center;
  color: var(--color-text-primary);
  background: var(--color-nav);
}

.dropdown-theme-item:hover {
  background: var(--color-surface);
  color: var(--color-primary);
  transform: translateX(4px);
}

.dropdown-theme-item.active {
  background: var(--color-primary);
  color: white;
  font-weight: 600;
}

/* Enlaces de dropdown */
.dropdown-nav-link {
  @apply block w-full px-4 py-2 text-left text-sm transition-all duration-200;
  color: var(--color-text-primary);
}

.dropdown-nav-link:hover {
  background: var(--color-surface);
  color: var(--color-primary);
  transform: translateX(4px);
}

/* Botón de usuario */
.user-button {
  @apply inline-flex items-center rounded-md border border-transparent px-3 py-2 text-sm font-medium leading-4 transition-all duration-200;
  color: var(--color-text-secondary);
  background: var(--color-nav);
}

.user-button:hover {
  color: var(--color-text-primary);
  background: var(--color-surface);
  transform: translateY(-1px);
}

/* Menú móvil */
.mobile-menu-button {
  @apply inline-flex items-center justify-center rounded-md p-2 transition-all duration-200;
  color: var(--color-text-secondary);
}

.mobile-menu-button:hover {
  background: var(--color-surface);
  color: var(--color-text-primary);
}

.mobile-menu {
  background: var(--color-nav);
  border-top: 2px solid var(--color-primary);
}

.mobile-nav-link {
  @apply block w-full px-4 py-2 text-left text-base font-medium transition-all duration-200;
  color: var(--color-text-primary);
}

.mobile-nav-link:hover {
  background: var(--color-surface);
  color: var(--color-primary);
}

.mobile-theme-select {
  @apply w-full px-3 py-2 border rounded-md text-sm transition-all duration-200;
  color: var(--color-text-primary);
  background: var(--color-nav);
  border-color: var(--color-border);
}

.mobile-theme-select:focus {
  border-color: var(--color-primary);
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* ===== ANIMACIONES ESPECIALES PARA MODO NIÑOS ===== */
.theme-kids .nav-link:hover {
  animation: bounce-kids 0.6s ease-in-out;
}

.theme-kids .dropdown-button:hover {
  animation: wiggle-kids 0.5s ease-in-out;
}

.theme-kids .theme-selector:hover {
  animation: rainbow-pulse 1s ease-in-out infinite;
}

@keyframes bounce-kids {
  0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
  40% { transform: translateY(-3px); }
  60% { transform: translateY(-2px); }
}

@keyframes wiggle-kids {
  0%, 7%, 14%, 21%, 28%, 35%, 42%, 49%, 56%, 63%, 70%, 77%, 84%, 91%, 100% { transform: rotate(0deg); }
  3.5%, 10.5%, 17.5%, 24.5%, 31.5%, 38.5%, 45.5%, 52.5%, 59.5%, 66.5%, 73.5%, 80.5%, 87.5%, 94.5% { transform: rotate(2deg); }
}

@keyframes rainbow-pulse {
  0% { box-shadow: 0 0 20px rgba(255, 107, 107, 0.6); }
  25% { box-shadow: 0 0 20px rgba(78, 205, 196, 0.6); }
  50% { box-shadow: 0 0 20px rgba(69, 183, 209, 0.6); }
  75% { box-shadow: 0 0 20px rgba(150, 206, 180, 0.6); }
  100% { box-shadow: 0 0 20px rgba(254, 202, 87, 0.6); }
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 640px) {
  .dropdown-button {
    @apply px-2 py-1 text-xs;
  }
  
  .theme-selector {
    @apply px-2 py-1 text-xs;
  }
}

/* ===== TRANSICIONES GLOBALES ===== */
* {
  transition: var(--transition);
}

/* Mejorar legibilidad en modo kids */
.theme-kids .text-text-primary {
  font-weight: 600;
  text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.8);
}

.theme-kids .dropdown-nav-link {
  font-weight: 500;
}
</style>