<script>
import NavBar from '@/components/NavBar.vue'
import axios from 'axios';

export default {
    name: 'Profile',
    components: {
        NavBar
    },
    data() {
        return {
            name: 'Testing',
        }
    }


    ,methods: {
        checkValidationToken(token){
            // Verification to avoid accessing the profile without a valid user token

            if(token == null || !token){
                console.error(`Redirecting to home, /profile is not allowed | token: ${token}`)
                this.$router.push('/');
            }
        },
        loadLocalData(token){
            // Request to the API of the user's information through his authentication token.


        },hacerPeticion() {
            const url = 'http://localhost:2003/users';
            const username = 'admin@admin.com';
            const password = 'admin';

            // Configurar la autenticación básica
            const config = {
                auth: {
                username: username,
                password: password
                }
            };

            // Realizar la petición con Axios
            axios.get(url, config)
                .then(response => {
                console.log('Petición exitosa');
                console.log(response.data); // O response.text si la respuesta es texto en lugar de JSON
                })
                .catch(error => {
                console.error(`Error en la petición: ${error.message}`);
                console.error(error.response.data);
                });
        }
    }
    ,mounted() {
        // this.checkValidationToken(this.$userSession.token);
        this.hacerPeticion();
    }
}

</script>

<template>
    <NavBar></NavBar>


    <div>

    </div>
</template>

<style>

</style>