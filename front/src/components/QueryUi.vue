<template>
    <div class="container">
        <div class="row">
            <div class="col-6">
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
                        <input v-model="getId" type="text" placeholder="User ID (for GET)">
                        <button type="submit">Show User</button>
                    </form>
                </div>
                <div class="row">
                    <h3>POST/PUT/PATCH</h3>
                    <!-- POST/PUT/PATCH request form -->
                    <form @submit.prevent="submitForm">
                        <div>
                            <input type="radio" id="post" value="POST" v-model="method">
                            <label for="post">POST</label>
                            <input type="radio" id="put" value="PUT" v-model="method">
                            <label for="put">PUT</label>
                            <input type="radio" id="patch" value="PATCH" v-model="method">
                            <label for="patch">PATCH</label>
                        </div>

                        <input v-model="userId" type="text" placeholder="User ID (for PUT, PATCH)">

                        <!-- User Data Form -->
                        <div>
                            <input v-model="userData.nombre" type="text" placeholder="First Name">
                            <input v-model="userData.nombre_segundo" type="text" placeholder="Second Name">
                            <input v-model="userData.apellido_primero" type="text" placeholder="First Surname">
                            <input v-model="userData.apellido_segundo" type="text" placeholder="Second Surname">
                            <input v-model="userData.email" type="text" placeholder="Email">
                            <input v-model="userData.password" type="password" placeholder="Password">
                            <input v-model="userData.rol" type="text" placeholder="Role">
                        </div>
                        <button type="submit">Submit</button>
                        <button type="button" @click="generateFakeUser('en')">Random EN</button>
                        <button type="button" @click="generateFakeUser('es')">Random ES</button>
                        <button type="button" @click="generateFakeUser('de')">Random DE</button>
                        <button type="button" @click="generateFakeUser('pt')">Random PT</button>
                    </form>
                </div>
                <div class="row">
                    <h3>DELETE</h3>
                    <!-- DELETE request form -->
                    <form @submit.prevent="deleteUser">
                        <input v-model="deleteId" type="text" placeholder="User ID (for DELETE)">
                        <button type="submit">Delete User</button>
                    </form>
                </div>
            </div>
            <div class="col-6">
                <h1>Response:</h1>
                <div v-if="response">
                    <pre>{{ response }}</pre>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Query from '@/types/Query.js'
import URL from '@/types/URL.js'
import BasicAuth from '@/types/BasicAuth.js'
import User from '@/types/User.js'
import NavBar from '@/components/NavBar.vue'
import { faker, fakerDE, fakerES, fakerPT_PT } from '@faker-js/faker'

export default {
    components: {},
    data() {
        return {
            method: 'POST',
            userId: '',
            userData: '',
            deleteId: '',
            getId: '',
            response: null,
            query: null,
        }
    },
    created() {
        this.myUrl = new URL('http', 'localhost', 2003)
        this.myBasicAuth = new BasicAuth('admin@admin.com', 'admin')
        this.query = new Query(this.myUrl).withAuth(this.myBasicAuth)
        this.faker = faker;
    },
    methods: {
        async getUsers() {
            try {
                this.response = await this.query.getUsers()
            } catch (err) {
                this.response = err.message
            }
        },
        async getUser() {
            try {
                this.response = null // Clear the previous response
                const user = await this.query.getUser(this.getId)
                this.response = user.toJSON()
                console.log(user.toString())
            } catch (err) {
                this.response = err.message
            }
        },
        async submitForm() {
            try {
                const user = new User()
                user.withObject(this.userData)
                if (this.method === 'POST') {
                    this.userId = null // Set userId to null for POST requests
                    await this.query.postUser(user)
                } else if (this.method === 'PUT') {
                    await this.query.putUser(user)
                } else if (this.method === 'PATCH') {
                    await this.query.patchUser(user)
                }
                this.response = 'User created/updated successfully'
            } catch (err) {
                this.response = err.message
            }
        },
        async deleteUser() {
            try {
                await this.query.deleteUser(this.deleteId)
                this.response = 'User deleted successfully'
            } catch (err) {
                this.response = err.message
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
                rol: 'user'
            }
        },
    },
}
</script>