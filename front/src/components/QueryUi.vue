<template xmlns="http://www.w3.org/1999/html">
    <div class="container text-black">
        <div class="row">
            <div class="col-6">
                <div class="row">
                    <h1>CRUD Operations</h1>
                    <h3>Auth</h3>
                    <!-- create form for basic auth OR radio button to bearer token -->
                    <form @submit.prevent="submitLogin">
                        <div>
                            <input id="basic" v-model="authType" type="radio" value="Basic" />
                            <label for="basic">Basic Auth</label>
                            <input
                                id="bearer"
                                v-model="authType"
                                type="radio"
                                value="Bearer"
                            />
                            <label for="bearer">Bearer Token</label>
                        </div>
                        <div v-if="authType === 'Basic'">
                            <input
                                v-model="myBasicAuth.email"
                                placeholder="Email"
                                type="text"
                            />
                            <input
                                v-model="myBasicAuth.password"
                                placeholder="Password"
                                type="password"
                            />
                        </div>
                        <div v-if="authType === 'Bearer'">
                            <input
                                v-model="myBearerToken"
                                placeholder="Bearer Token"
                                type="text"
                            />
                        </div>
                        <button type="submit">Submit</button>
                    </form>
                </div>
                <div class="row">
                    <h3>GET ALL</h3>
                    <!-- GET request form -->
                    <form @submit.prevent="getUsers">
                        <button type="submit">Get All Users</button>
                    </form>
                </div>
                <div class="row">
                    <h3>GET USER ID</h3>
                    <!-- GET by ID request form -->
                    <form @submit.prevent="getUser">
                        <input v-model="userId" placeholder="User ID" type="text" />
                        <button type="submit">Get User by ID</button>
                    </form>
                </div>
                <div class="row">
                    <h3>POST/PUT/PATCH</h3>
                    <!-- POST/PUT/PATCH request form -->
                    <form @submit.prevent="submitForm">
                        <div>
                            <input id="post" v-model="method" type="radio" value="POST" />
                            <label for="post">POST</label>
                            <input id="put" v-model="method" type="radio" value="PUT" />
                            <label for="put">PUT</label>
                            <input id="patch" v-model="method" type="radio" value="PATCH" />
                            <label for="patch">PATCH</label>
                        </div>

                        <input
                            v-model="userId"
                            placeholder="User ID (for PUT, PATCH)"
                            type="text"
                        />

                        <!-- User Data Form -->
                        <div>
                            <input
                                v-model="userData.nombre"
                                placeholder="First Name"
                                type="text"
                            />
                            <input
                                v-model="userData.nombre_segundo"
                                placeholder="Second Name"
                                type="text"
                            />
                            <input
                                v-model="userData.apellido_primero"
                                placeholder="First Surname"
                                type="text"
                            />
                            <input
                                v-model="userData.apellido_segundo"
                                placeholder="Second Surname"
                                type="text"
                            />
                            <input v-model="userData.email" placeholder="Email" type="text" />
                            <input
                                v-model="userData.password"
                                placeholder="Password"
                                type="password"
                            />
                            <input v-model="userData.rol" placeholder="Role" type="text" />
                        </div>
                        <button type="submit">Submit</button>
                        <button type="button" @click="generateFakeUser('en')">
                            Random EN
                        </button>
                        <button type="button" @click="generateFakeUser('es')">
                            Random ES
                        </button>
                        <button type="button" @click="generateFakeUser('de')">
                            Random DE
                        </button>
                        <button type="button" @click="generateFakeUser('pt')">
                            Random PT
                        </button>
                    </form>
                </div>
                <div class="row">
                    <h3>DELETE</h3>
                    <!-- DELETE request form -->
                    <form @submit.prevent="deleteUser">
                        <input
                            v-model="deleteId"
                            placeholder="User ID (for DELETE)"
                            type="text"
                        />
                        <button type="submit">Delete User</button>
                    </form>
                </div>

                <h2>Iot Devices</h2>

                <div class="row">
                    <h3>GET ALL IoT Devices</h3>
                    <!-- GET ALL IoT Devices request form -->
                    <form @submit.prevent="getIotDevices">
                        <button type="submit">Get All IoT Devices</button>
                    </form>
                </div>
                <div class="row">
                    <h3>GET IoT Device by ID</h3>
                    <!-- GET IoT Device by ID request form -->
                    <form @submit.prevent="getIotDevice">
                        <input
                            v-model="iotDeviceId"
                            placeholder="IoT Device ID"
                            type="text"
                        />
                        <button type="submit">Get IoT Device by ID</button>
                    </form>
                </div>
                <div class="row">
                    <h3>DELETE IoT Device by ID</h3>
                    <!-- DELETE IoT Device by ID request form -->
                    <form @submit.prevent="deleteIotDevice">
                        <input
                            v-model="iotDeviceId"
                            placeholder="IoT Device ID"
                            type="text"
                        />
                        <button type="submit">Delete IoT Device by ID</button>
                    </form>
                </div>
                <div class="row">
                    <h3>TRANSFER IoT Device</h3>
                    <!-- TRANSFER IoT Device request form -->
                    <form @submit.prevent="transferIotDevice">
                        <input v-model="userId" placeholder="User ID" type="text" />
                        <input
                            v-model="iotDeviceId"
                            placeholder="IoT Device ID"
                            type="text"
                        />
                        <button type="submit">Transfer IoT Device</button>
                    </form>
                </div>
                <div class="row">
                    <h3>Generate UUID</h3>
                    <!-- Generate UUID button -->
                    <button @click="generateUUID">Generate UUID</button>
                </div>

                <div class="row">
                    <h3>NOT CRUD Operations</h3>
                    <h4>Get my devices and positions</h4>
                    <form @submit.prevent="getDevicesByMyself">
                        <button type="submit">Get my devices</button>
                    </form>
                </div>
            </div>
            <div class="col-6">
                <h1>Response:</h1>
                <highlightjs v-if="response" :code="response" autodetect class="font-monospace" />
            </div>
        </div>
    </div>
</template>

<style>
</style>

<script>
import Query from '@/types/Query.js'
import URL from '@/types/URL.js'
import BasicAuth from '@/types/BasicAuth.js'
import User from '@/types/User.js'
import { faker, fakerDE, fakerES, fakerPT_PT } from '@faker-js/faker'
import { nextTick } from 'vue'
import BearerToken from '@/types/BearerToken.js'
import 'highlight.js/styles/xt256.css'
import 'highlight.js/lib/common'
import hljsVuePlugin from '@highlightjs/vue-plugin'
import parseUrl from '@/assets/js/miscelaneus'

export default {
    components: {
        highlightjs: hljsVuePlugin.component,
    },
    data() {
        return {
            method: 'POST',
            userId: '',
            userData: '',
            deleteId: '',
            getId: '',
            response: 'Esperando acción del usuario...',
            query: null,
            iotDeviceId: '',
            authType: 'Basic',
            myBasicAuth: {
                email: 'admin@admin.com',
                password: 'admin',
            },
            myBearerToken: '',
        }
    },
    created() {
        let connectData = parseUrl(this.$config.devConfig.apiServer);

        this.myUrl = new URL(connectData[0], connectData[1], connectData[2])
        this.query = new Query(this.myUrl).withAuth(new BasicAuth('admin@admin.com', 'admin'))
        this.faker = faker

        nextTick(() => {
            this.authType = 'Basic'
            this.myBasicAuth = {
                email: 'admin@admin.com',
                password: 'admin',
            }
        })
    },
    methods: {
        async submitLogin() {
            try {
                if (this.authType === 'Basic') {
                    this.query.withAuth(new BasicAuth(this.myBasicAuth.email, this.myBasicAuth.password))
                } else if (this.authType === 'Bearer') {
                    console.log('Bearer token:', new BearerToken(this.myBearerToken))
                    this.query.withAuth(new BearerToken(this.myBearerToken))
                }
                const response = await this.query.login()
                this.response = 'Logged in successfully \n' + JSON.stringify(response, null, 2)
            } catch (err) {
                this.response = JSON.stringify(err, null, 2)
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

        async getIotDevice() {
            try {
                this.response = null // Clear the previous response
                this.response = JSON.stringify(await this.query.getIotDevice(this.iotDeviceId), null, 2)
            } catch (err) {
                this.response = JSON.stringify(err, null, 2)
            }
        },

        async deleteIotDevice() {
            try {
                this.response = JSON.stringify(await this.query.deleteIotDevice(this.iotDeviceId), null, 2)
            } catch (err) {
                this.response = JSON.stringify(err, null, 2)
            }
        },

        async transferIotDevice() {
            try {
                this.response = JSON.stringify(await this.query.transferIotDevice(
                    this.userId,
                    this.iotDeviceId,
                ), null, 2)
            } catch (err) {
                this.response = JSON.stringify(err, null, 2)
            }
        },

        generateUUID() {
            this.iotDeviceId = faker.string.uuid()
        },
        async getUsers() {
            try {
                this.response = JSON.stringify(await this.query.getUsers(), null, 2)
            } catch (err) {
                this.response = JSON.stringify(err, null, 2)
            }
        },
        async getUser() {
            try {
                this.response = JSON.stringify(await this.query.getUser(this.userId), null, 2)
            } catch (err) {
                this.response = JSON.stringify(err, null, 2)
            }
        },
        async submitForm() {
            try {
                const user = new User()
                user.withObject(this.userData)
                let response
                if (this.method === 'POST') {
                    this.userId = null // Set userId to null for POST requests
                    response = await this.query.postUser(user)
                } else if (this.method === 'PUT') {
                    response = await this.query.putUser(user)
                } else if (this.method === 'PATCH') {
                    response = await this.query.patchUser(user)
                }
                this.response = 'User created/updated successfully \n' + JSON.stringify(response, null, 2)
            } catch (err) {
                this.response = JSON.stringify(err, null, 2)
            }
        },
        async deleteUser() {
            try {
                this.response = JSON.stringify(await this.query.deleteUser(this.deleteId), null, 2)
            } catch (err) {
                this.response = JSON.stringify(err.message, null, 2)
            }
        },
        async getDevicesByMyself() {
            try {
                this.response = JSON.stringify(await this.query.getIotDevicesBySelf(), null, 2)
            } catch (err) {
                this.response = JSON.stringify(err, null, 2)
            }
        },
        generateFakeUser(locale) {
            if (locale === 'de') {
                this.faker = fakerDE
            } else if (locale === 'pt') {
                this.faker = fakerPT_PT
            } else if (locale === 'es') {
                this.faker = fakerES
            }

            this.userData = {
                nombre: this.faker.person.firstName(),
                nombre_segundo: this.faker.person.firstName(),
                apellido_primero: this.faker.person.lastName(),
                apellido_segundo: this.faker.person.lastName(),
                email: this.faker.internet.email(),
                password: this.faker.internet.password(),
                rol: 'user',
            }
        },
    },
}
</script>