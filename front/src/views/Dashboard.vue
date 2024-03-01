<script>
import NavBar from '@/components/NavBar.vue'
import FooterMain from '@/components/FooterMain.vue'
import Mapa from '@/components/Mapa.vue'
import PetCard from '@/components/PetCard.vue'
import Query from '@/types/Query.js'
import URL from '@/types/URL.js'
import BasicAuth from '@/types/BasicAuth.js'
import User from '@/types/User.js'
import Cookies from 'js-cookie'
import BearerToken from '@/types/BearerToken.js'
import { cookieSettings } from '@/assets/config.json'


export default {
    name: 'Dashboard',
    components: {
        NavBar,
        Mapa,
        PetCard,
        FooterMain,
    },
    data() {
        return {
            userData: [],
            devicesData: [],
            dateTest: "10/10/2015",
            showMap: true,
        }
    },
    methods: {
        loadUserData(data) {

            if (!data) {
                this.userData = {
                    name: this.$store.getters.getUserSession.name,
                    gmail: this.$store.getters.getUserSession.gmail,
                    iotDevices: [],
                }
                return true;
            }

            this.userData = {
                name: data.name,
                gmail: data.gmail,
                iotDevices: [],
            }

        },
        async getDevicesByMyself() {
            try {
                return this.$store.getters.getIotDevices
            } catch (err) {
                let response = err
            }
        },
        async tryGetLoginData() {
            try {
                setTimeout(() => {
                    if (this.$store.getters.getUserSession) {
                        console.log(this.$store.getters.getUserSession);
                        this.loadUserData(this.$store.getters.getUserSession);
                        this.loadIotDevices().then((data) => {

                            for (let cord of data) {
                                let coordAnimal = {
                                    petName: cord.name,
                                    latitud: cord.last_latitude,
                                    longitud: cord.last_longitude,
                                    petSpecie: cord.especie,
                                }
                                this.devicesData.push(coordAnimal);
                            }
                        });
                    }
                }, 5000)
            } catch (err) {

            }
        },async loadIotDevices() {
            try {
                let myUrl = new URL('http', 'localhost', 2003);
                let query = new Query(myUrl).withAuth(new BearerToken(this.$store.getters.getUserSession.token));
                let response = await query.getIotDevicesBySelf();
                response = response.data;

                await this.$store.dispatch('setIotDevices', response);
                this.$router.push('/dashboard')
                return response;
            } catch (error) {
                // Manejar el error según tus necesidades
                console.error("Error al cargar los dispositivos IoT:", error);
                throw error; // Puedes lanzar el error nuevamente si es necesario
            }
        },
        async reloadMapSection() {
            this.showMap = !this.showMap;
            console.log('Reload');
        },
    },
    mounted() {
        // this.loadUserData();
        this.tryGetLoginData();
    }
}
</script>

<template>
    <NavBar></NavBar>

    <div class="p-2">
        <div class="p-4 bg-light rounded container-fluid">
            <h2>{{ $t('miscelaneus.welcome') }}, {{ userData.name }}</h2>
            <div class="d-flex gap-2 justify-content-between">
                <!-- Left Site-->
                <div class="d-inline-flex flex-column gap-2">
                    <h4>{{ $t('miscelaneus.pets') }}:</h4>

                    <div class="d-inline-flex flex-column gap-4 p-2 scroll-container">
                        <div v-for="pet in devicesData" :key="pet.id">
                            <PetCard :petDate="pet.birthDate" :petName="pet.petName" :petSpecies="pet.petSpecie"></PetCard>
                        </div>
                    </div>

                </div>

                <!-- Right Site-->
                <div v-if="showMap && devicesData.length > 0">
                    <Mapa :puntos="devicesData"></Mapa>
                </div>
            </div>
        </div>
    </div>
    <FooterMain></FooterMain>
</template>

<style scoped>
.pets-view {
    max-width: 500px;
}

.scroll-container {
    max-height: 550px;
    overflow: hidden;
    overflow-y: scroll;
}

.scroll-container::-webkit-scrollbar {
    width: 12px;
}

.scroll-container::-webkit-scrollbar-thumb {
    background-color: #888;
}

.scroll-container::-webkit-scrollbar-thumb:hover {
    background-color: #555;
}</style>