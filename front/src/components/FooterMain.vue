<template>
    <footer class="footer">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-3 text-center text-md-left">
            <img
              alt="Logo"
              class="footer-logo"
              src="../assets/logo_circle.svg"
            />
          </div>
          <div class="col-md-6">
            <ul class="nav justify-content-center">
              <li class="nav-item">
                <a class="nav-link" href="#inicio">Inicio</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#acerca-de">Acerca de</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#servicios">Servicios</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#contacto">Contacto</a>
              </li>
            </ul>
          </div>
          <div class="col-md-3 text-center text-md-left">
            <p class="contact-info">
              <strong>{{ $t('footer.footer_direction') }}</strong> Dirección de tu empresa<br />
              <strong>{{ $t('footer.footer_number') }}</strong> +1234567890<br />
              <strong>{{ $t('footer.footer_mail') }}</strong> eaglefoxOfficial@empresa.com
            </p>
          </div>
        </div>
      </div>
    </footer>
  </template>
  
  <script>
  export default {
    name: 'Footer',
  }
  </script>
  
  <style scoped>
  .footer {
    background-color: #343a40;
    color: #ffffff;
    padding: 1rem 0;
  }
  
  .footer-logo {
    width: 100px;
    height: auto;
    margin-bottom: 1rem;
  }
  
  .nav-link {
    color: #ffffff;
    transition: color 0.3s ease;
  }
  
  .nav-link:hover {
    color: #ffc107;
  }
  
  .contact-info {
    font-size: 0.9rem;
  }
  
  @media (max-width: 768px) {
    .footer-logo {
      margin-bottom: 1rem;
    }
    
    .contact-info {
      text-align: center;
    }
  }
  </style>
  