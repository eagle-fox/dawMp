<script>
import Cookies from 'js-cookie';

export default {
  name: 'App',
  methods: {
    // Método para crear una nueva userSession
    createNewUserSession() {
      // Datos para la nueva userSession
      const userData = {
        firstName: 'Nuevo',
        lastName: 'Usuario',
        role: 'Sesión'
      };


      this.$store.dispatch('createNewUserSession', userData)
          .then(() => {
            console.log('Nueva userSession creada:', this.$store.getters.getUserSession);
          })
          .catch(error => {
            console.error('Error al crear la nueva userSession:', error);
          });
    }
  },
  mounted() {
    this.createNewUserSession();
  }
}

</script>

<template>
  <router-view></router-view>
</template>

<style scoped>
header {
  line-height: 1.5;
}

.logo {
  display: block;
  margin: 0 auto 2rem;
}

@media (min-width: 1024px) {
  header {
    display: flex;
    place-items: center;
    padding-right: calc(var(--section-gap) / 2);
  }

  .logo {
    margin: 0 2rem 0 0;
  }

  header .wrapper {
    display: flex;
    place-items: flex-start;
    flex-wrap: wrap;
  }
}
</style>
