<template>
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <img
                        alt="Logo"
                        class="footer-logo img-fluid"
                        src="../assets/contratipo_dorado.svg"
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
                <div class="col-md-3">
                    <p class="contact-info">
                        <strong>{{ $t('footer.footer_direction') }}</strong> Dirección de tu
                        empresa<br />
                        <strong>{{ $t('footer.footer_number') }}</strong> +1234567890<br />
                        <strong>{{ $t('footer.footer_mail') }}</strong> info@empresa.com
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

</style>