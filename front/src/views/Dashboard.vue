<script>
import NavBar from '@/components/NavBar.vue'
import Mapa from '@/components/Mapa.vue'
import PetCard from '@/components/PetCard.vue'
import { ref } from 'vue'

export default {
  name: 'Dashboard',
  components: {
    NavBar,
    Mapa,
    PetCard,
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

    <div class="d-flex justify-content-center">
      <Mapa :puntos="puntosArray"></Mapa>
    </div>
  </div>
</template>

<style scoped>
.pets-view {
  max-width: 500px;
}
</style>
