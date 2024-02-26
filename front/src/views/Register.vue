<script>
import NavBar from '@/components/NavBar.vue'
import ConnectionApi from '../assets/js/connectionApi.js'
import { styleAssets } from '@/assets/config.json'

export default {
    name: 'Register',
    components: {
        NavBar,
    },
    data() {
        return {
            formData: {
                name: null,
                secondName: null,
                firstSubname: null,
                secondSubname: null,
                email: null,
                password: null,
                repeatPassword: null,
            },
            svgFile: null,
        }
    },
    methods: {
        showError(errorMessage, id) {
            let itemByID = document.getElementById(id)
            let alertMessage = document.createElement('div')

            alertMessage.innerHTML = `
            <div class="alert alert-danger d-flex align-items-center d-flex gap-2" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" class='' style='width:25px;' class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                <div>
                    ${errorMessage}
                </div>
            </div>`

            alertMessage.style.position = 'absolute'
            alertMessage.style.top = '50%'
            alertMessage.style.left = '50%'
            alertMessage.style.transform = 'translate(-50%, -50%)'
            alertMessage.style.textAlign = 'center'

            itemByID.append(alertMessage)

            setTimeout(() => {
                alertMessage.remove()
            }, 5000)
        },
        setLoadStatus(type, id) {
            let itemByID = document.getElementById(id)

            if (type) {
                let loadingMaker = document.createElement('div')
                loadingMaker.innerHTML = `
                <div class="spinner-border text-primary loadSphere" id='loadMarker' style='width: 50px; height: 50px;' role="status">
                <span class="visually-hidden">Loading...</span>
                </div>
                <div class="text-primary">
                    ${this.$t('miscelaneus.loading')}...
                </div>
                `

                loadingMaker.style.position = 'absolute'
                loadingMaker.style.top = '50%'
                loadingMaker.style.left = '50%'
                loadingMaker.style.transform = 'translate(-50%, -50%)'
                loadingMaker.style.textAlign = 'center'

                loadingMaker.id = 'loadMarker'

                itemByID.children[1].style.filter = 'blur(5px)'
                itemByID.children[0].style.filter = 'blur(5px)'
                itemByID.append(loadingMaker)
            } else {
                let loadMarker = document.getElementById('loadMarker')
                loadMarker.remove()

                itemByID.children[1].style.filter = 'blur(0px)'
                itemByID.children[0].style.filter = 'blur(0px)'
            }
        },
        async handleSubmit() {
            this.setLoadStatus(true, 'formMakeUser')

            const formDataTesting = {
                nombre: this.formData.name,
                nombre_segundo: this.formData.secondName,
                apellido_primero: this.formData.firstSubname,
                apellido_segundo: this.formData.secondSubname,
                email: this.formData.email,
                password: this.formData.password,
            }

            const connectionApiInstance = new ConnectionApi()
            const result = await connectionApiInstance.makeUser(formDataTesting)

            if (typeof result === 'string') {
                this.setLoadStatus(false, 'formMakeUser')
                this.showError(result, 'formMakeUser')
            }

            if (result == true) {
                this.$router.push('/')
            }

            //this.$router.push('/');
        },
        loadSvgFile() {
            this.svgFile = 'src/assets/' + styleAssets.svgData.typoBackground
        },
    },
    mounted() {
        this.loadSvgFile()
    },
}
</script>

<template>
    <NavBar></NavBar>
    <div
        :style="{ 'background-image': 'url(' + svgFile + ')' }"
        class="main-container svg-path"
    >
        <div class="d-flex justify-content-center align-items-center">
            <div class="shadow p-4 rounded bg-light" id="formMakeUser">
                <h3 class="text-center mb-4">{{ $t('login.login_message1') }}</h3>
                <form @submit.prevent="handleSubmit">
                    <div class="mb-3 d-flex gap-2 form-media">
                        <div>
                            <label class="form-label" for="name">{{
                                    $t('login.login_username')
                                }}</label>
                            <input
                                id="name"
                                v-model="formData.name"
                                class="form-control"
                                type="text"
                            />
                        </div>
                        <div>
                            <label class="form-label" for="secondName">{{
                                    $t('login.login_secondName')
                                }}</label>
                            <input
                                id="secondName"
                                v-model="formData.secondName"
                                class="form-control"
                                type="text"
                            />
                        </div>
                    </div>
                    <div class="mb-3 d-flex gap-2 form-media">
                        <div>
                            <label class="form-label" for="firtsSubname">{{
                                    $t('login.login_firtsSubname')
                                }}</label>
                            <input
                                id="firtsSubname"
                                v-model="formData.firstSubname"
                                class="form-control"
                                type="text"
                            />
                        </div>
                        <div>
                            <label class="form-label" for="secondSubname">{{
                                    $t('login.login_secondSubname')
                                }}</label>
                            <input
                                id="secondSubname"
                                v-model="formData.secondSubname"
                                class="form-control"
                                vtype="text"
                            />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">{{
                                $t('login.login_email')
                            }}</label>
                        <input
                            id="email"
                            v-model="formData.email"
                            aria-describedby="emailHelp"
                            class="form-control"
                            type="email"
                        />
                        <div id="emailhelp" class="form-text">
                            {{ $t('login.login_message') }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">{{
                                $t('login.login_password')
                            }}</label>
                        <input
                            id="password"
                            v-model="formData.password"
                            class="form-control"
                            type="password"
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="repeatPassword">{{
                                $t('login.login_rpassword')
                            }}</label>
                        <input
                            id="repeatPassword"
                            v-model="formData.repeatPassword"
                            class="form-control"
                            type="password"
                        />
                    </div>
                    <button class="btn btn-primary" type="submit">
                        {{ $t('login.login_message1') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
.main-container {
    height: calc(94vh);
    display: flex;
    justify-content: center;
    align-items: center;
}

.loadSphere {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

@media only screen and (max-width: 560px) {
    .form-media {
        flex-direction: column;
    }

    .main-container {
        padding-top: 40px;
    }
}
</style>