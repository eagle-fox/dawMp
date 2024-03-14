<script>
import NavBar from '@/components/NavBar.vue'
import { cookieSettings, styleAssets } from '@/assets/config.json'
import Query from '@/types/Query.js'
import URL from '@/types/URL.js'
import BasicAuth from '@/types/BasicAuth.js'
import Cookies from 'js-cookie'
import BearerToken from '@/types/BearerToken.js'

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
        setUserCookie(token){
            let secureStatus = cookieSettings.secure
            let sameSiteConfig = cookieSettings.sameSite

            Cookies.set('tokenCookie', token, {
                expires: 365,
                sameSite: sameSiteConfig,
                secure: secureStatus,
            })
        },
        showFailLongin(errorMessage) {
            let form = document.getElementById('loginForm');
            let failDiv = document.createElement('div');

            failDiv.innerHTML =
            `
                        <div class="d-flex p-2 justify-content-center">
                            <div class="bg-danger w-auto rounded text-center p-1 text-light">${errorMessage}</div>
                        </div>
            `;
            failDiv.style.textAlign = 'center';
            failDiv.style.padding = '5px';

            form.appendChild(failDiv);
            
        },parseUrl(url) {
            const urlObj = new URL(url);
            const protocol = urlObj.protocol.replace(':', '');
            const hostname = urlObj.hostname;
            const port = urlObj.port || (protocol === 'https' ? '443' : '80'); // Si no hay puerto, establece el puerto predeterminado basado en el protocolo

            return [protocol, hostname, port];
        },
        async submitLogin() {
            try {
                if (this.authType === 'Basic') {

                    // Log in using Gmail and Password 

                    let connectData = this.parseUrl(this.$config.devConfig.apiServer);

                    this.myUrl = new URL(connectData[0], connectData[1], connectData[2])
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
                        this.loadIotDevices();
                        this.setUserCookie(this.$store.getters.getUserSession.token);
                    })
                    .catch((error) => {
                        console.error('Error al crear la nueva userSession:', error)
                    })


            } catch (err) {
                this.response = JSON.stringify(err, null, 2)
                console.log(err);
                this.showFailLongin(this.$t('login.login_error'));
                
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
        parseUrl(url) {
            const urlObj = new URL(url);
            const protocol = urlObj.protocol.replace(':', '');
            const hostname = urlObj.hostname;
            const port = urlObj.port || (protocol === 'https' ? '443' : '80'); // Si no hay puerto, establece el puerto predeterminado basado en el protocolo

            return [protocol, hostname, port];
        },
        async loadIotDevices() {
            try {
                let myUrl = new URL(connectData[0], connectData[1], connectData[2]);
                let query = new Query(myUrl).withAuth(new BearerToken(this.$store.getters.getUserSession.token));
                let response = await query.getIotDevicesBySelf();
                response = response.data;

                await this.$store.dispatch('setIotDevices', response);
                this.setUserCookie(this.$store.getters.getUserSession.token);
                console.log(this.$store.getters.getUserSession.token);
                this.$router.push('/dashboard')
                return response;
            } catch (error) {
                // Manejar el error según tus necesidades
                console.error("Error al cargar los dispositivos IoT:", error);
                throw error; // Puedes lanzar el error nuevamente si es necesario
            }
        },
        async checkUserSession(){
            // AutoLogin system, check if user token exists

            try{
                if (this.$store.getters.getUserSession.token !== null) {
                    document.getElementById('loginPanel').style.filter = 'blur(5px)';
                    this.loadIotDevices()
                }
            }catch (error) {
                console.log(error);
            }
        }
    },
    mounted() {
        this.loadSvgFile();
        this.checkUserSession();
    },
}
</script>

<template>
    <NavBar></NavBar>
    <div :style="{ 'background-image': 'url(' + svgFile + ')' }" class="main-container svg-path" id="loginPanel">
        <div class="d-inline-block d-flex justify-content-center align-items-center all bg-light rounded">
            <div class="shadow p-4 rounded">
                <div class="d-flex justify-content-center">
                    <img alt="Logo" src="../assets/logo_circle.svg" width="64" />
                </div>
                <div class="d-flex justify-content-center align-items-center flex-column gap-2">
                    <form id="loginForm">
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