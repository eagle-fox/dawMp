<script>
import NavBar from '@/components/NavBar.vue'

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


        }
    }
    ,mounted() {
        // this.checkValidationToken(this.$userSession.token);
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