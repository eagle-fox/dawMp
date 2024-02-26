<template>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <h1>Todos los usuarios</h1>

                <!-- GET request form -->
                <form @submit.prevent="getUsers">
                    <button type="submit">Get All Users</button>
                </form>

                <!-- GET by ID request form -->
                <form @submit.prevent="getUser">
                    <input v-model="getId" type="text" placeholder="User ID (for GET)">
                    <button type="submit">Show User</button>
                </form>

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
                    <textarea v-model="userData" placeholder="User Data (for POST, PUT, PATCH)"></textarea>

                    <button type="submit">Submit</button>
                </form>

                <!-- DELETE request form -->
                <form @submit.prevent="deleteUser">
                    <input v-model="deleteId" type="text" placeholder="User ID (for DELETE)">
                    <button type="submit">Delete User</button>
                </form>
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

export default {
    data() {
        return {
            method: 'POST',
            userId: '',
            userData: '',
            deleteId: '',
            getId: '',
            response: null,
            query: null,
        };
    },
    created() {
        this.myUrl = new URL('http', 'localhost', 2003)
        this.myBasicAuth = new BasicAuth('admin@admin.com','admin')
        this.query = new Query(this.myUrl).withAuth(this.myBasicAuth)
    },
    methods: {
        async getUsers() {
            try {
                this.response = await this.query.getUsers();
            } catch (err) {
                this.response = err.message;
            }
        },
        async getUser() {
            try {
                this.response = null; // Clear the previous response
                const user = await this.query.getUser(this.getId);
                this.response = user.toJSON();
                console.log(user.toString());
            } catch (err) {
                this.response = err.message;
            }
        },
        async submitForm() {
            try {
                const user = new User();
                user.withObject(JSON.parse(this.userData));
                if (this.method === 'POST') {
                    await this.query.postUser(user);
                    this.response = 'User created successfully';
                } else if (this.method === 'PUT') {
                    await this.query.putUser(user);
                    this.response = 'User updated successfully';
                } else if (this.method === 'PATCH') {
                    await this.query.patchUser(user);
                    this.response = 'User patched successfully';
                }
            } catch (err) {
                this.response = err.message;
            }
        },
        async deleteUser() {
            try {
                await this.query.deleteUser(this.deleteId);
                this.response = 'User deleted successfully';
            } catch (err) {
                this.response = err.message;
            }
        },
    },
};
</script>