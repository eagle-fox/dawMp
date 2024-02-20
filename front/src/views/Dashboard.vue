<script>
import NavBar from "@/components/NavBar.vue";
import Mapa from "@/components/Mapa.vue";
import PetCard from "@/components/PetCard.vue";

export default {
  name: "Dashboard",
  components: {
    NavBar,
    Mapa,
    PetCard
  },
  data() {
    return {
      petname: "Charly",
      petdate: "29/08/2023"
    }
  }
}

</script>

<template>
  <NavBar></NavBar>

  <div class="pets-view p-4 ">
    <PetCard :petName=petname :petDate=petdate ></PetCard>
  </div>
  
</template>

<style scoped>
  .pets-view{
    max-width: 500px;
  }
</style>