<script>
import NavBar from '@/components/NavBar.vue'
import Mapa from '@/components/Mapa.vue'
import MapaRegion from '@/components/MapaRegion.vue'
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
        Mapa,
        MapaRegion
    },
    data() {
        return {
            svgFile: null,
            name: '',
            password: '',
            query: null,
            response: 'Esperando acción del usuario...',
            authType: 'Basic',
            mapPoints: []
        }
    },
    methods: {
        presetMapPoints() {
            console.log(this.$store.getters.getCoordinates)
            if (this.$store.getters.getCoordinates) {
                this.mapPoints = { ...this.$store.getters.getCoordinates };
            }
        }
    },
    mounted() {
        this.presetMapPoints();
    }

}
</script>

<template>
    <NavBar></NavBar>
    <div ref="mapContainer" class="cssrandom0">
        <Mapa :puntos="mapPoints" width=""></Mapa>
    </div>
</template>

<style scoped>
.main-container {
    height: calc(100vh - 58px);
    display: flex;
    justify-content: center;
    align-items: center;
}
.full {
    height: calc(80vh - 65px);
}

.cssrandom0 .cssrandom1 {

}

.cssrandom1 {
    overflow: hidden;
    height: 100%;
}

.cssrandom0 {
    padding: 1em;
    width: 100%;
    height: calc(60vh);
    position: relative;
    z-index: 0;
    overflow: hidden;
}
</style>