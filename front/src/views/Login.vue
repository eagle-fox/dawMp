<script>
import NavBar from '@/components/NavBar.vue'
import { styleAssets } from '@/assets/config.json'
import Query from '@/types/Query.js'
import URL from '@/types/URL.js'
import BasicAuth from '@/types/BasicAuth.js'
import User from '@/types/User.js'

export default {
    name: 'Login',
    components: {
        NavBar,
    },
    data() {
        return {
            svgFile: null,
            name: '',
            password: '',
            query: null,
            response: 'Esperando acción del usuario...',
            authType: 'Basic'
        }
    },
    methods: {
        loadSvgFile() {
            this.svgFile = 'src/assets/' + styleAssets.svgData.typoBackground
        },
        async submitLogin() {
            try {
                if (this.authType === 'Basic') {

                    // Log in using Gmail and Password 

                    this.myUrl = new URL('http', 'localhost', 2003)
                    this.query = new Query(this.myUrl).withAuth(new BasicAuth(this.name, this.password))
                }

                // When I get the data, I dump it into the user's session. 

                const response = await this.query.login()
                this.response = 'Logged in successfully \n' + JSON.stringify(response, null, 2)

                let userData = {
                    name: response.user.nombre,
                    email: response.user.email,
                    role: response.user.rol,
                    token: response.user.clients[0].token,

                }

                this.$store
                    .dispatch('updateUserSession', userData)
                    .then(() => {
                    console.log(this.$store.getters.getUserSession)

                    })
                    .catch((error) => {
                    console.error('Error al crear la nueva userSession:', error)
                    })


                console.log(userData);
            } catch (err) {
                this.response = JSON.stringify(err, null, 2)
                console.log(err);
            }
        },
        async login() {
            try {
                this.response = JSON.stringify(await this.query.login(), null, 2)
            } catch (err) {
                this.response = JSON.stringify(err, null, 2)
            }
        },
        async getIotDevices() {
            try {
                this.response = JSON.stringify(await this.query.getIotDevices(), null, 2)
            } catch (err) {
                this.response = JSON.stringify(err, null, 2)
            }
        },
    },
    mounted() {
        this.loadSvgFile()
    },
}
</script>

<template>
    <NavBar></NavBar>
    <div :style="{ 'background-image': 'url(' + svgFile + ')' }" class="main-container svg-path">
        <div class="d-inline-block d-flex justify-content-center align-items-center all bg-light rounded">
            <div class="shadow p-4 rounded">
                <div class="d-flex justify-content-center">
                    <img alt="Logo" src="../assets/logo_circle.svg" width="64" />
                </div>
                <div class="d-flex justify-content-center align-items-center flex-column gap-2">
                    <form>
                        <div class="mb-3">
                            <label class="form-label" for="name">{{
                                $t('login.login_username')
                            }}</label>
                            <input id="name" v-model="name" aria-describedby="name" class="form-control" type="text" />
                            <div id="name" class="form-text">
                                {{ $t('login.login_message') }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="exampleInputPassword1">{{
                                $t('login.login_password')
                            }}</label>
                            <input v-model="password" id="exampleInputPassword1" class="form-control" type="password" />
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a class="btn btn-primary" @click="submitLogin()">{{
                                $t('login.login_t1')
                            }}</a>
                            <router-link class="btn btn-primary" to="/register" type="submit">{{ $t('login.login_message1')
                            }}
                            </router-link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.main-container {
    height: calc(100vh - 58px);
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>