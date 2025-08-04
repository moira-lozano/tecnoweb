<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold">Panel Administrativo</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">{{ $page.props.auth.user.name }}</span>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                            {{ $page.props.auth.user.rol }}
                        </span>
                        <Link :href="route('landing')" class="text-blue-600 hover:text-blue-800">
                            Ver Tienda
                        </Link>
                        <Link :href="route('logout')" method="post" class="text-red-600 hover:text-red-800">
                            Cerrar Sesión
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="border-4 border-dashed border-gray-200 rounded-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">
                        Bienvenido al Panel Administrativo
                    </h2>
                    
                    <!-- Admin Menu -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Usuarios -->
                        <Link :href="route('admin.users')" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-blue-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Usuarios</h3>
                                    <p class="text-gray-600">Gestionar usuarios del sistema</p>
                                </div>
                            </div>
                        </Link>

                        <!-- Roles -->
                        <Link :href="route('admin.roles')" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-green-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Roles</h3>
                                    <p class="text-gray-600">Gestionar roles y permisos</p>
                                </div>
                            </div>
                        </Link>

                        <!-- Productos/Licencias -->
                        <Link :href="route('admin.licencias')" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-purple-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Licencias</h3>
                                    <p class="text-gray-600">Gestionar productos y licencias</p>
                                </div>
                            </div>
                        </Link>

                        <!-- Ventas -->
                        <Link :href="route('admin.ventas')" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-yellow-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Ventas</h3>
                                    <p class="text-gray-600">Gestionar ventas y catálogo</p>
                                </div>
                            </div>
                        </Link>

                        <!-- Pagos -->
                        <Link :href="route('admin.pagos')" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-red-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Pagos</h3>
                                    <p class="text-gray-600">Gestionar pagos y transacciones</p>
                                </div>
                            </div>
                        </Link>

                        <!-- Reportes -->
                        <Link :href="route('admin.reportes')" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-indigo-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Reportes</h3>
                                    <p class="text-gray-600">Ver reportes y estadísticas</p>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'

export default {
    name: 'Dashboard',
    components: {
        Link
    }
}
</script>