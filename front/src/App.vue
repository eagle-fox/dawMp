<script>
import Cookies from 'js-cookie'

export default {
  name: 'App',
  methods: {

    createNewUserSession(userToken) {

      // With the token of the user we must make the request to the
      // API to obtain the information of the user, if the token is not
      // registered we create a visitor session.

      // API Request in developtment

      if(!this.checkToken(userToken)){
          return false
      }

      let userData = {
        name: 'Unknow',
        email: 'unknow@gmail.com',
        role: 'unknow',
        token: userToken
      }

      this.$store.dispatch('createNewUserSession', userData)
          .then(() => {
            console.log(this.$store.getters.getUserSession)
          })
          .catch(error => {
            console.error('Error al crear la nueva userSession:', error)
          })
    },
    checkToken(token) {
      const regex = /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-4[0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}$/;
      return regex.test(token);
    },
    loadUserSessionCookie() {
      if (Cookies.get('sessionCookie')) {
        this.createNewUserSession(Cookies.get('sessionCookie'));
      }else{
        this.$store.dispatch('makeVisitorSession')
            .then(() => {
              console.log(this.$store.getters.getUserSession)
            })
            .catch(error => {
              console.error('Error al crear la nueva userSession:', error)
            })
      }
    }


  },
  mounted() {
    this.loadUserSessionCookie()
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
