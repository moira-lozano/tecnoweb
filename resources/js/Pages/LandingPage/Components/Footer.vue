<!-- resources/js/components/Footer.vue -->
<template>
    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6">
      <div class="max-w-6xl mx-auto flex items-center justify-between">
        <p>&copy; 2025 LICENCES GUARD | Todos los derechos reservados.</p>
        <div class="flex space-x-4">
          <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-twitter"></i></a>
          <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-instagram"></i></a>
        </div>
        <p>
          Esta página fue visitada {{ pageview.visits }} {{  pageview.visits === 1 ? 'vez' : 'veces' }}.
        </p>

      </div>
    </footer>
  </template>

  <script setup>
  import { ref } from 'vue';
    import {usePage } from '@inertiajs/vue3';
const page = usePage();
const pageview = ref(page.props.pageview);
  </script>

  <style scoped>
  /* Estilos básicos para el footer */
  .site-footer {
    background-color: #333;
    color: white;
    padding: 40px 0;
    font-size: 0.9rem;
  }

  .site-footer h3 {
    color: #f39c12;
  }

  .site-footer p {
    margin-bottom: 15px;
  }

  .site-footer ul {
    list-style: none;
    padding: 0;
  }

  .site-footer ul li {
    margin-bottom: 10px;
  }

  .site-footer ul li a {
    color: white;
    text-decoration: none;
  }

  .site-footer ul li a:hover {
    text-decoration: underline;
  }

  .social-links {
    display: flex;
    gap: 10px;
  }

  .social-icon {
    color: white;
    font-size: 1.5rem;
    transition: color 0.3s ease;
  }

  .social-icon:hover {
    color: #f39c12;
  }

  .footer-bottom {
    background-color: #222;
    padding: 10px 0;
    text-align: center;
  }

  .footer-bottom p {
    margin: 0;
  }
  </style>
