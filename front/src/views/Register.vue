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
        showError(errorMessage,id) {
            let itemByID = document.getElementById(id);
            let alertMessage = document.createElement('div');

            alertMessage.innerHTML = `
            <div class="alert alert-danger d-flex align-items-center d-flex gap-2" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" class='' style='width:25px;' class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                <div>
                    ${errorMessage}
                </div>
            </div>`

            alertMessage.style.position = 'absolute';
            alertMessage.style.top = '50%';
            alertMessage.style.left = '50%';
            alertMessage.style.transform = 'translate(-50%, -50%)';
            alertMessage.style.textAlign = 'center';
            
            itemByID.append(alertMessage);

            setTimeout(() => {
                alertMessage.remove();
            },5000);

        },setLoadStatus(type, id) {
            let itemByID = document.getElementById(id);

            if (type) {
                let loadingMaker = document.createElement('div');
                loadingMaker.innerHTML = `
                <div class="spinner-border text-primary loadSphere" id='loadMarker' style='width: 50px; height: 50px;' role="status">
                <span class="visually-hidden">Loading...</span>
                </div>
                <div class="text-primary">
                    ${ this.$t('miscelaneus.loading') }...
                </div>
                `

                loadingMaker.style.position = 'absolute';
                loadingMaker.style.top = '50%';
                loadingMaker.style.left = '50%';
                loadingMaker.style.transform = 'translate(-50%, -50%)';
                loadingMaker.style.textAlign = 'center';

                loadingMaker.id = 'loadMarker';

                itemByID.children[1].style.filter = 'blur(5px)';
                itemByID.children[0].style.filter = 'blur(5px)';
                itemByID.append(loadingMaker);
            }else{
                let loadMarker = document.getElementById('loadMarker');
                loadMarker.remove();

                itemByID.children[1].style.filter = 'blur(0px)';
                itemByID.children[0].style.filter = 'blur(0px)';
            }   


        },async handleSubmit() {

            this.setLoadStatus(true, 'formMakeUser');

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


            if (typeof result === "string") {
                this.setLoadStatus(false, 'formMakeUser');
                this.showError(result,'formMakeUser')
            }

            if (result == true) {
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
            <div class="shadow p-4 rounded" id="formMakeUser">
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
                        <input v-model="formData.email" type="email" class="form-control" id="email"
                            aria-describedby="emailHelp" />
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

    <div>
        <button class="btn btn-primary" @click="showError('Error','formMakeUser')"></button>
    </div>
</template>

<style scoped>
.main-container {
    height: calc(100vh - 200px);
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
</style>
