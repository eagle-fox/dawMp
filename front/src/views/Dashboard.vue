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
    FooterMain
  },
  setup() {
    const puntosArray = ref([
      { name: 'charly', latitud: 51.505, longitud: -0.09, species: 'sheep' },
      { name: 'hugo', latitud: 51.51, longitud: -0.1, species: 'dog' },
    ])

    const petname = ref('Charly')
    const petdate = ref('29/08/2023')

    return { puntosArray, petname, petdate }
  },
}
</script>

<template>
  <div>
    <NavBar></NavBar>

    <div class="pets-view p-4">
      <PetCard :petName="petname" :petDate="petdate"></PetCard>
      <Mapa :puntos="puntosArray"></Mapa>
    </div>
      <FooterMain></FooterMain>
  </div>
</template>

<style scoped>
.pets-view {
  max-width: 500px;
}
</style>
