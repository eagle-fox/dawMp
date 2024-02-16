<script>
import NavBar from '@/components/NavBar.vue'
import ConnectionApi from '../assets/js/connectionApi.js'

export default {
    name: 'Register',
    components: {
        NavBar
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
                repeatPassword: null
            }
        };
    },
    methods: {
        async handleSubmit() {

            const formDataTesting = {
                nombre: this.formData.name,
                nombre_segundo: this.formData.secondName,
                apellido_primero: this.formData.firstSubname,
                apellido_segundo: this.formData.secondSubname,
                email: this.formData.email,
                password: this.formData.password
            };

            const connectionApiInstance = new ConnectionApi();
            const result = await connectionApiInstance.makeUser(formDataTesting);

            
            if(result){
                this.$router.push('/');
            }

            //this.$router.push('/');
        }
    }
}

</script>

<template>
    <NavBar></NavBar>
    <div class="main-container">
        <div class="vh-100 d-flex justify-content-center align-items-center">
            <div class="shadow p-4 rounded">
                <h3 class="text-center mb-4">{{ $t('login.login_message1') }}</h3>
                <form @submit.prevent="handleSubmit">
                    <div class="mb-3 d-flex gap-2">
                        <div>
                            <label for="name" class="form-label">{{ $t('login.login_username') }}</label>
                            <input v-model="formData.name" type="text" class="form-control" id="name" />
                        </div> 
                        <div>
                            <label for="secondName" class="form-label">{{ $t('login.login_secondName') }}</label>
                            <input v-model="formData.secondName" type="text" class="form-control" id="secondName" />
                        </div>
                    </div>
                    <div class="mb-3 d-flex gap-2">
                        <div>
                            <label for="firtsSubname" class="form-label">{{ $t('login.login_firtsSubname') }}</label>
                            <input v-model="formData.firstSubname" type="text" class="form-control" id="firtsSubname" />
                        </div>
                        <div>
                            <label for="secondSubname" class="form-label">{{ $t('login.login_secondSubname') }}</label>
                            <input v-model="formData.secondSubname" vtype="text" class="form-control" id="secondSubname" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ $t('login.login_email') }}</label>
                        <input v-model="formData.email" type="email" class="form-control" id="email" aria-describedby="emailHelp" />
                        <div id="emailhelp" class="form-text">{{ $t('login.login_message') }}</div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ $t('login.login_password') }}</label>
                        <input v-model="formData.password" type="password" class="form-control" id="password" />
                    </div>
                    <div class="mb-3">
                        <label for="repeatPassword" class="form-label">{{ $t('login.login_rpassword') }}</label>
                        <input v-model="formData.repeatPassword" type="password" class="form-control" id="repeatPassword" />
                    </div>
                    <button type="submit" class="btn btn-primary">{{ $t('login.login_message1') }}</button>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
.main-container {
    height: calc(100vh - 200px);
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
