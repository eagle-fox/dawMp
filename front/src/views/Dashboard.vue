<script>
import NavBar from "@/components/NavBar.vue";
import Mapa from "@/components/Mapa.vue";

export default {
  name: "Dashboard",
  components: {
    NavBar,
    Mapa
  },
  data() {
    return {
      puntos: [
        { latitud: 40.416775, longitud: -5.703790, name: 'tobi' },
        { latitud: 46.2406, longitud: -8.7207, name: 'sancho' }
      ]
    }
  }
}

</script>

<template>
  <NavBar></NavBar>
  <div class="d-flex justify-content-center align-items-center ">
    <Mapa :puntos="puntos"/>
  </div>
</template>

<style scoped>

</style>