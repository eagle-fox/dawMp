<script>
import Cookies from 'js-cookie'
import URL from '@/types/URL.js'
import Query from '@/types/Query.js'
import BearerToken from '@/types/BearerToken.js'


export default {
  name: 'App',
  data() {
    return {
      name: '',
      password: '',
      query: null,
      response: 'Esperando acción del usuario...',
      authType: 'Basic'
    }
  },
  methods: {
    createNewUserSession(userToken) {
      // With the token of the user we must make the request to the
      // API to obtain the information of the user, if the token is not
      // registered we create a visitor session.

      let userData = {
        name: 'Unknow',
        email: 'unknow@gmail.com',
        role: 'unknow',
        token: userToken,
      }

      this.$store
        .dispatch('createNewUserSession', userData)
        .then(() => {


        })
        .catch((error) => {
          console.error('Error al crear la nueva userSession:', error)
        })
    },
    checkToken(token) {
      const regex =
        /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-4[0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}$/
      return regex.test(token)
    },
    loadUserSessionCookie() {
      if (Cookies.get('sessionCookie')) {
        this.createNewUserSession(Cookies.get('sessionCookie'))
      } else {
        this.$store
          .dispatch('makeVisitorSession')
          .then(() => {
            console.log(this.$store.getters.getUserSession)
          })
          .catch((error) => {
            console.error('Error al crear la nueva userSession:', error)
          })
      }
    },parseUrl(url) {
      const urlObj = new URL(url);
      const protocol = urlObj.protocol.replace(':', '');
      const hostname = urlObj.hostname;
      const port = urlObj.port || (protocol === 'https' ? '443' : '80'); // Si no hay puerto, establece el puerto predeterminado basado en el protocolo

      return [protocol, hostname, port];
    },
    async loadUserSessionByCookie() {
      let connectData = this.parseUrl(this.$config.devConfig.apiServer);

      if (Cookies.get('tokenCookie')) {
        let myUrl = new URL(connectData[0], connectData[1], connectData[2]);
        let query = new Query(myUrl).withAuth(new BearerToken(Cookies.get('tokenCookie')));
        let response = await query.login();

        let userData = {
          name: response.user.nombre,
          email: response.user.email,
          role: response.user.rol,
          token: response.user.clients[0].token,
        }

        this.$store
          .dispatch('updateUserSession', userData)
          .then(() =>{
            //this.$router.push('/dashboard')
          })
          .catch((error) => {
            console.error('Error al crear la nueva userSession:', error)
          })

      }
    }
  },
  mounted() {
    this.createNewUserSession(null);
    this.loadUserSessionByCookie();
  },
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