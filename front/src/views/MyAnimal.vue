<script>
import NavBar from '@/components/NavBar.vue'
import Mapa from '@/components/Mapa.vue'
import { cookieSettings, styleAssets } from '@/assets/config.json'
import Query from '@/types/Query.js'
import URL from '@/types/URL.js'
import BasicAuth from '@/types/BasicAuth.js'
import Cookies from 'js-cookie'
import BearerToken from '@/types/BearerToken.js'

export default {
    name: 'Login',
    components: {
        NavBar,
        Mapa
    },
    data() {
        return {
            svgFile: null,
            name: '',
            password: '',
            query: null,
            response: 'Esperando acción del usuario...',
            authType: 'Basic'
        }
    },
    methods: {}

}
</script>

<template>
    <NavBar></NavBar>
    <div class="d-flex justify-content-center ">
        <Mapa></Mapa>
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