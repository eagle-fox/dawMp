<script>
import Cookies from 'js-cookie';
import NavBar from  '@/components/NavBar.vue'
import { styleAssets } from '@/assets/config.json';

export default {
    name: 'Login',
    components: {
        NavBar
    },
    data() {
        return {
            svgFile: null,
        }
    },
    methods: {
        loadSvgFile() {
            this.svgFile = 'src/assets/'+styleAssets.svgData.typoBackground
        }
    },
    mounted() {
      this.loadSvgFile();
    }
}

</script>

<template>
    <NavBar></NavBar>
    <div class="main-container svg-path" :style="{ 'background-image': 'url(' + svgFile + ')' }">
        <div class="d-inline-block d-flex justify-content-center align-items-center all bg-light rounded">
            <div class="shadow p-4 rounded">
                <div class="d-flex justify-content-center">
                    <img alt="Logo" src="../assets/logo_circle.svg" width="64">
                </div>
                <div class="d-flex justify-content-center align-items-center flex-column gap-2">
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">{{$t('login.login_username')}}</label>
                            <input type="text" class="form-control" id="name" aria-describedby="name">
                            <div id="name" class="form-text">{{$t('login.login_message')}}</div>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">{{$t('login.login_password')}}</label>
                            <input type="password" class="form-control" id="exampleInputPassword1">
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a type="submit" class="btn btn-primary" >{{$t('login.login_t1')}}</a>
                            <router-link to="/register" type="submit" class="btn btn-primary">{{$t('login.login_message1')}}</router-link>
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