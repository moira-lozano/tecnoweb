<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div class="flex items-center">
                        <h1 class="text-3xl font-bold text-gray-900">
                            Mi Tienda
                        </h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div v-if="$page.props.auth.user">
                            <!-- Usuario logueado -->
                            <div class="flex items-center space-x-4">
                                <span class="text-gray-700">
                                    Hola, {{ $page.props.auth.user.name }}
                                </span>
                                <span v-if="$page.props.auth.user.roles && $page.props.auth.user.roles.length > 0" 
                                      class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                    {{ $page.props.auth.user.roles[0].name }}
                                </span>
                                <Link :href="route('profile.edit')" class="text-blue-600 hover:text-blue-800">
                                    Perfil
                                </Link>
                                <Link :href="route('logout')" method="post" class="text-red-600 hover:text-red-800">
                                    Cerrar Sesión
                                </Link>
                            </div>
                        </div>
                        <div v-else>
                            <!-- Usuario no logueado -->
                            <div class="flex items-center space-x-4">
                                <Link :href="route('login')" class="text-blue-600 hover:text-blue-800">
                                    Iniciar Sesión
                                </Link>
                                <Link :href="route('register')" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                    Registrarse
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="text-center">
                <h2 class="text-4xl font-extrabold text-gray-900 sm:text-5xl">
                    Bienvenido a Nuestra Tienda
                </h2>
                <p class="mt-4 text-xl text-gray-600">
                    Encuentra las mejores licencias de software al mejor precio
                </p>
                
                <!-- Call to Action Button -->
                <div class="mt-8">
                    <button @click="handleComprarClick" 
                            class="bg-green-600 text-white px-8 py-4 rounded-lg text-xl font-semibold hover:bg-green-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        🛒 Comprar Ahora
                    </button>
                </div>
            </div>

            <!-- Admin Access Button (solo para administradores) -->
            <div v-if="$page.props.auth.user && isAdmin" class="mt-8 text-center">
                <Link :href="route('dashboard')" class="bg-gray-600 text-white px-6 py-3 rounded-md hover:bg-gray-700 inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Panel Administrativo
                </Link>
            </div>

            <!-- Products Section -->
            <div class="mt-16">
                <h3 class="text-2xl font-bold text-gray-900 mb-8 text-center">
                    Licencias Disponibles
                </h3>
                
                <!-- Loading State -->
                <div v-if="!licencias" class="text-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-4 text-gray-600">Cargando productos...</p>
                </div>
                
                <!-- Products Grid -->
                <div v-else-if="licencias && licencias.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <div v-for="licencia in licencias.slice(0, 8)" :key="licencia.id" 
                         class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <!-- Imagen del producto -->
                        <div class="h-48 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        
                        <div class="p-4">
                            <h4 class="font-semibold text-gray-900 text-lg mb-2">{{ licencia.nombre }}</h4>
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ licencia.descripcion || 'Licencia de software premium' }}</p>
                            
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-2xl font-bold text-green-600">${{ licencia.precio }}</span>
                                <span v-if="licencia.stock > 0" class="text-sm text-green-600 bg-green-100 px-2 py-1 rounded">
                                    Stock: {{ licencia.stock }}
                                </span>
                                <span v-else class="text-sm text-red-600 bg-red-100 px-2 py-1 rounded">
                                    Agotado
                                </span>
                            </div>
                            
                            <button @click="handleComprarClick" 
                                    :disabled="licencia.stock === 0"
                                    :class="[
                                        'w-full py-2 rounded font-medium transition-colors duration-200',
                                        licencia.stock > 0 
                                            ? 'bg-blue-600 text-white hover:bg-blue-700' 
                                            : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                    ]">
                                {{ licencia.stock > 0 ? 'Comprar' : 'Agotado' }}
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- No products available -->
                <div v-else class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay productos disponibles</h3>
                    <p class="text-gray-600">Próximamente tendremos nuevas licencias disponibles.</p>
                </div>
                
                <!-- Ver más productos button -->
                <div v-if="licencias && licencias.length > 8" class="text-center mt-8">
                    <button @click="handleComprarClick" 
                            class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        Ver Todos los Productos ({{ licencias.length }})
                    </button>
                </div>
            </div>

            <!-- Features Section -->
            <div class="mt-16">
                <h3 class="text-2xl font-bold text-gray-900 mb-8 text-center">
                    ¿Por qué elegirnos?
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Licencias Originales</h4>
                        <p class="text-gray-600">Todas nuestras licencias son 100% originales y verificadas</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Mejores Precios</h4>
                        <p class="text-gray-600">Precios competitivos y ofertas especiales</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Soporte 24/7</h4>
                        <p class="text-gray-600">Atención al cliente disponible las 24 horas</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Links for Logged Users -->
            <div v-if="$page.props.auth.user" class="mt-12">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Accesos Rápidos</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <Link :href="route('landing.productos')" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-200">
                            <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <span class="text-blue-700">Ver Catálogo Completo</span>
                        </Link>
                        
                        <Link :href="route('landing.mis.productos')" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-200">
                            <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span class="text-green-700">Mis Licencias</span>
                        </Link>
                        
                        <Link :href="route('landing.pagos.create')" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors duration-200">
                            <svg class="w-6 h-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <span class="text-purple-700">Realizar Pago</span>
                        </Link>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white mt-16">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p>&copy; 2024 Mi Tienda. Todos los derechos reservados.</p>
                    <p class="mt-2 text-gray-400">Licencias de software originales y verificadas</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'

export default {
    name: 'Landing',
    components: {
        Link
    },
    props: {
        licencias: {
            type: Array,
            default: () => []
        }
    },
    computed: {
        isAdmin() {
            const user = this.$page.props.auth.user;
            if (!user || !user.roles || user.roles.length === 0) return false;
            
            const adminRoles = ['gerente', 'vendedor', 'supervisor'];
            return user.roles.some(role => adminRoles.includes(role.toLowerCase()));
        }
    },
    methods: {
        handleComprarClick() {
            // Si el usuario está logueado, redirigir al dashboard/carrito
            if (this.$page.props.auth.user) {
                this.$inertia.visit(route('landing.productos')); // o la ruta donde está tu carrito
            } else {
                // Si no está logueado, mostrar modal o redirigir al login
                if (confirm('Necesitas iniciar sesión para comprar. ¿Deseas iniciar sesión ahora?')) {
                    this.$inertia.visit(route('login'));
                }
            }
        }
    }
}
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>