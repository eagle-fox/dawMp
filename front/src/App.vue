<script>
import Cookies from 'js-cookie';

export default {
  name: 'App',
  methods: {

    createNewUserSession() {

      const userData = {
        firstName: 'Nuevo',
        email: 'Usuario',
        token: this.generateUUID()
      };

      this.$store.dispatch('createNewUserSession', userData)
          .then(() => {
            console.log('Nueva userSession creada:', this.$store.getters.getUserSession);
          })
          .catch(error => {
            console.error('Error al crear la nueva userSession:', error);
          });
    },

    // Only test function

    generateUUID() {
      return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (Math.random() * 16) | 0,
            v = c === 'x' ? r : (r & 0x3) | 0x8;
        return v.toString(16);
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
