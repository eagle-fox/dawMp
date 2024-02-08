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

            let userData = {
                name: 'visitor',
                email: 'visitor@gmail.com',
                role: 'visitor',
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
        generateUUID() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                let r = (Math.random() * 16) | 0,
                    v = c === 'x' ? r : (r & 0x3) | 0x8
                return v.toString(16)
            })
        },
        loadUserSessionCookie(){
            if(Cookies.get('sessionCookie')){
                this.createNewUserSession(Cookies.get('sessionCookie'));
            }else {
                // Make a new token and session Cookie for new users

                let newToken = this.generateUUID()

                Cookies.set('sessionCookie',newToken, { expires: null, sameSite: 'None', secure: true })
                this.createNewUserSession(newToken)
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
