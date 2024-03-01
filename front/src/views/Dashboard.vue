<script>
import NavBar from '@/components/NavBar.vue'
import FooterMain from '@/components/FooterMain.vue'
import Mapa from '@/components/Mapa.vue'
import PetCard from '@/components/PetCard.vue'

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
            dateTest: "10/10/2015"
        }
    },
    methods: {
        loadUserData() {
            this.userData = {
                name: this.$store.getters.getUserSession.name,
                gmail: this.$store.getters.getUserSession.gmail,
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
    },
    mounted() {
        this.getDevicesByMyself().then((response) => {
            for (let cord in response) {
                // Use response[cord] to get the value of the current property
                console.log(response[cord])
                let coordAnimal = {
                    petName: response[cord].name,
                    latitud: response[cord].last_latitude,
                    longitud: response[cord].last_longitude,
                    petSpecie: response[cord].especie,
                }
                console.log(coordAnimal)
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

                    <div class="d-inline-flex flex-column gap-4 p-2 scroll-container" >
                        <div v-for="pet in devicesData" :key="pet.id">
                            <PetCard :petDate="dateTest" :petName="pet.petName" :petSpecies="pet.petSpecie"></PetCard>
                        </div>
                    </div>

                </div>

                <!-- Right Site-->
                <div v-if="devicesData.length > 0">
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
    overflow-y: scroll; /* Muestra la barra de desplazamiento solo cuando hay un desplazamiento real */
  }

  .scroll-container::-webkit-scrollbar {
    width: 12px; /* Ancho de la barra de desplazamiento */
  }

  .scroll-container::-webkit-scrollbar-thumb {
    background-color: #888; /* Color de la barra de desplazamiento */
  }

  .scroll-container::-webkit-scrollbar-thumb:hover {
    background-color: #555; /* Cambia el color cuando el mouse está sobre la barra de desplazamiento */
  }
</style>