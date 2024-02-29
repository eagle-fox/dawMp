<script>
import Cookies from 'js-cookie'
import URL from '@/types/URL.js'
import Query from '@/types/Query.js'
import BearerToken from '@/types/BearerToken.js'


export default {
  name: 'App',
  methods: {
    createNewUserSession(userToken) {
      // With the token of the user we must make the request to the
      // API to obtain the information of the user, if the token is not
      // registered we create a visitor session.

      // API Request in developtment

      if (!this.checkToken(userToken)) {
        return false
      }

      let userData = {
        name: 'Unknow',
        email: 'unknow@gmail.com',
        role: 'unknow',
        token: userToken,
      }

      this.$store
        .dispatch('createNewUserSession', userData)
        .then(() => {
          console.log(this.$store.getters.getUserSession)

        })
        .catch((error) => {
          console.error('Error al crear la nueva userSession:', error)
        })
    },
    async loadIotDevices(){
      let myUrl = new URL('http', 'localhost', 2003)
      let query = new Query(myUrl).withAuth(new BearerToken('9d85983c-54b5-448c-ad74-bedf128a85f1'))
      let response = await query.getIotDevicesBySelf()
      response = response.data;


      this.$store
        .dispatch('setIotDevices', response)
        .then(() => {
          console.log(this.$store.getters.getUserSession)
          // console.log(this.$store.getters.getIotDevices + 'A')
        });

      return response
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
    },
  },
  mounted() {
    this.loadUserSessionCookie();
    this.loadIotDevices();
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