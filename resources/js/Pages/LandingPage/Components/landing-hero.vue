<script setup>
import { Inertia } from '@inertiajs/inertia'

function navigateToForm() {
  Inertia.visit(route('landing.productos'))  // Ajusta el nombre de la ruta según la que tengas
}
</script>

<template>
  <section
    class="relative bg-cover bg-center py-32 px-6"
    :style="{ backgroundImage: `url('https://st2.depositphotos.com/3369547/8161/v/450/depositphotos_81612422-stock-illustration-software-design.jpg')` }"
  >
    <div class="absolute inset-0 bg-gradient-to-r from-black to-transparent opacity-80"></div>

    <div class="relative z-10 text-left max-w-4xl mx-auto">
      <h1 class="text-5xl md:text-6xl font-extrabold leading-tight text-white mb-6">
        Compra <br />
        <span class="text-orange-500">licencias digitales</span> <br />
        de forma rápida y segura.
      </h1>
      <p class="text-xl md:text-2xl text-gray-300 mb-8">
        Obtén tus licencias de software oficiales en pocos pasos, con total confianza y soporte garantizado.
      </p>
      <button
        @click="navigateToForm"
        class="bg-orange-500 hover:bg-orange-600 text-white py-4 px-8 rounded-full text-lg font-bold shadow-lg transition"
      >
        Comprar ahora
      </button>
    </div>
  </section>
</template>
