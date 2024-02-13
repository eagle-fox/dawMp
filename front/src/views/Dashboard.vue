<script>
import NavBar from "@/components/NavBar.vue";
import Mapa from "@/components/Mapa.vue";

export default {
  name: "Dashboard",
  components: {
    NavBar,
    Mapa
  }
}

</script>

<template>
  <NavBar></NavBar>
  <div class="d-flex justify-content-center align-items-center ">
    <Mapa></Mapa>
  </div>
</template>

<style scoped>

</style>