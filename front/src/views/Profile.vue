<script>
import NavBar from '@/components/NavBar.vue'
import ConnectionApi from '../assets/js/connectionApi.js'
// import axios from 'axios';

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


        },testConnectionApi(){
            const connectionApiInstance = new ConnectionApi();
            connectionApiInstance.testAxios();
        },testAppendApi(){
            const connectionApiInstance = new ConnectionApi();
            connectionApiInstance.makeUser();
        }
    }
    ,mounted() {
        // this.checkValidationToken(this.$userSession.token);
        this.testConnectionApi();
    }
}

</script>

<template>
    <NavBar></NavBar>

    <div>
        <button class="btn btn-primary" @click="testAppendApi">Test Add User</button>
    </div>

    
</template>

<style>

</style>