<template>
  <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-md-3">
          <img src="../assets/pointers/cat.svg" alt="Logo" class="footer-logo img-fluid">
        </div>
        <div class="col-md-6">
          <ul class="nav justify-content-center">
            <li class="nav-item"><a class="nav-link" href="#inicio">Inicio</a></li>
            <li class="nav-item"><a class="nav-link" href="#acerca-de">Acerca de</a></li>
            <li class="nav-item"><a class="nav-link" href="#servicios">Servicios</a></li>
            <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
          </ul>
        </div>
        <div class="col-md-3">
          <p class="contact-info">
            <strong>Dirección:</strong> Dirección de tu empresa<br>
            <strong>Teléfono:</strong> +1234567890<br>
            <strong>Email:</strong> info@empresa.com
          </p>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'Footer',
};
</script>

<style scoped>
.footer {
  background-color: #4cb8a0;
  color: #fff;
  padding: 20px 0;
}

.footer-logo {
  max-width: 100px;
}

.nav-link {
  color: #fff;
}

.nav-link:hover {
  color: #1f4233;
  text-decoration: underline;
}

.contact-info {
  color: #fff;
  text-align: right;
}
</style>
