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
                name: 'Manolo',
                gmail: 'manolo@gmail.com',
                iotDevices: [
                    {
                        petName: 'Charly',
                        petDate: '12/09/2020',
                        species: 'dog',
                        latitud: 42.2266403,
                        longitud: -8.712718,
                    },
                    {
                        petName: 'Juan',
                        petDate: '12/09/2020',
                        species: 'cat',
                        latitud: 42.3266403,
                        longitud: -8.712718,
                    },
                ],
            }
        },
        async getDevicesByMyself() {
            try {
                let myUrl = new URL('http', 'localhost', 2003)
                let query = new Query(myUrl).withAuth(new BearerToken('9c4ca426-54e4-4326-a882-fc2f71d5f1cd'))
                let response = await query.getIotDevicesBySelf()
                response = response.data;
                return response
            } catch (err) {
                let response = err
            }
        },
    },
    mounted() {

        this.getDevicesByMyself().then((response) => {


            for (let cord in response) {
                // Use response[cord] to get the value of the current property
                let coordAnimal = {
                    petName: response[cord].name,
                    latitud: response[cord].last_latitude,
                    longitud: response[cord].last_longitude,
                }
                this.devicesData.push(coordAnimal);
            }

            console.log(this.devicesData);
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
                <div>
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