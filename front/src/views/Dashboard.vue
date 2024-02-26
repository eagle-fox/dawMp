<script>
import NavBar from '@/components/NavBar.vue'
import FooterMain from '@/components/FooterMain.vue'
import Mapa from '@/components/Mapa.vue'
import PetCard from '@/components/PetCard.vue'
import { ref } from 'vue'

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
    }
  },
  setup() {
    const puntosArray = ref([
        { name: 'charly', latitud: 51.505, longitud: -0.09, species: 'dog' },
    ])

    const petname = ref('Charly')
    const petdate = ref('29/08/2023')

    return { puntosArray, petname, petdate }
  },
  methods: {
    loadUserData() {
      // Test data
      let obtainData = {
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

        this.userData = obtainData
    },
  },
  mounted() {
      this.loadUserData()
  },
}
</script>

<template>
  <NavBar></NavBar>

  <div class="p-2">
    <div class="p-4 bg-light rounded">
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
          <Mapa :puntos="userData.iotDevices"></Mapa>
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