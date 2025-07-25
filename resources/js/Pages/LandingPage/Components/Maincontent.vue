<!-- resources/js/components/MainContent.vue -->
<!-- resources/js/components/MainContent.vue -->
<template>
  <div class="main-content">
    <div class="container">
      <h1 class="title">Bienvenido a TecnoLicencias</h1>
      <p class="description">
        En <strong>TecnoLicencias</strong> nos especializamos en ofrecerte
        las mejores <strong>licencias de software originales</strong> para tu negocio y uso personal.
        Nuestro objetivo es que accedas de forma rápida y segura a licencias legales de
        <em>Sistemas Operativos, Suites de Ofimática, Antivirus y mucho más</em>.
      </p>
      <p class="description mt-4">
        Descubre nuestra amplia variedad de licencias con soporte garantizado y precios competitivos.
        ¡Ahorra tiempo y adquiere tu licencia digital al instante con nosotros!
      </p>
      <!-- Aquí puedes agregar botones o enlaces -->
      <div class="text-center mt-6">
        <a
          href="/landing/productos"
          class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition"
        >
          Ver Licencias Disponibles
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
// Por ahora no necesitas lógica adicional aquí
</script>

<style scoped>
.main-content {
  background-color: #f9f9f9;
  padding: 50px 0;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 15px;
}

.title {
  font-size: 2.5rem;
  color: #1a202c;
  text-align: center;
  margin-bottom: 20px;
  font-weight: 700;
}

.description {
  font-size: 1.2rem;
  color: #4a5568;
  text-align: center;
  line-height: 1.6;
}

.mt-4 {
  margin-top: 1rem;
}

.mt-6 {
  margin-top: 1.5rem;
}

.text-center {
  text-align: center;
}
</style>



  <script setup>

  </script>

  <style scoped>
  .main-content {
    background-color: #f9f9f9;
    padding: 50px 0;
  }

  .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
  }

  .title {
    font-size: 2.5rem;
    color: #333;
    text-align: center;
    margin-bottom: 20px;
  }

  .description {
    font-size: 1.2rem;
    color: #666;
    text-align: center;
  }
  </style>
