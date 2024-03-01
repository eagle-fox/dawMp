<script>
import NavBar from '@/components/NavBar.vue'
import FooterMain from '@/components/FooterMain.vue'
import Mapa from '@/components/Mapa.vue'
import PetCard from '@/components/PetCard.vue'
import { ref } from 'vue'
import URL from '@/types/URL.js'
import Query from '@/types/Query.js'
import BearerToken from '@/types/BearerToken.js'

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
            devicesData: []
        }
    },
    methods: {
        loadUserData() {
            // Test data
            this.userData = {
                name: this.$store.getters.getUserSession.name,
                gmail: this.$store.getters.getUserSession.gmail,
                iotDevices: [],
            }
        },
        async getDevicesByMyself() {
            try {
                console.log('Esperando mapa');
                return this.$store.getters.getIotDevices
            } catch (err) {
                let response = err
            }
        },
    },
    mounted() {
        this.getDevicesByMyself().then((response) => {
            console.log('Datos cargados');
            console.log(response);

            for (let cord in response) {
                // Use response[cord] to get the value of the current property
                let coordAnimal = {
                    petName: response[cord].name,
                    latitud: response[cord].last_latitude,
                    longitud: response[cord].last_longitude
                }

                this.devicesData.push(coordAnimal);
            }
        })
        this.loadUserData()
    },
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

                    <div class="d-inline-flex flex-column gap-4">
                        <div v-for="pet in userData.iotDevices">
                            <PetCard
                                :petDate="pet.petDate"
                                :petName="pet.petName"
                                :petSpecies="pet.petSpecie"
                            ></PetCard>
                        </div>
                    </div>
                </div>

                <!-- Right Site-->
                <div v-if="devicesData.length > 0">
                    <Mapa :puntos="devicesData"></Mapa>
                </div>
            </div>
        </div>
        <FooterMain></FooterMain>
    </div>
</template>

<style scoped>
.pets-view {
    max-width: 500px;
}
</style>