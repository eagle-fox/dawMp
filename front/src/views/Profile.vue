<script>
import NavBar from '@/components/NavBar.vue'
import Cookies from 'js-cookie'
import URL from '@/types/URL.js'
import Query from '@/types/Query.js'
import BearerToken from '@/types/BearerToken.js'
import ConnectionApi from '../assets/js/connectionApi.js'

export default {
    name: 'Profile',
    components: {
        NavBar,
    },
    data() {
        return {
            name: 'Testing',
        }
    },

    methods: {
        checkValidationToken(token) {
            // Verification to avoid accessing the profile without a valid user token

            if (token == null || !token) {
                console.error(
                    `Redirecting to home, /profile is not allowed | token: ${token}`,
                )
                this.$router.push('/')
            }
        },async loadUserSessionByCookie() {
            if (Cookies.get('tokenCookie')) {
                let url = JSON.stringify(this.$config.devConfig.apiServer);
                let connectData = parseUrl(url);
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
                    .then(() => {
                        console.log(this.$store.getters.getUserSession);
                    })
                    .catch((error) => {
                        console.error('Error al crear la nueva userSession:', error)
                    })

            }
        }
       
    },
    mounted() {
        console.log(this.$store.getters.getUserSession)
        this.loadUserSessionByCookie();
    },
}
</script>

<template>
    <NavBar></NavBar>
    <div>
    </div>
</template>

<style></style>