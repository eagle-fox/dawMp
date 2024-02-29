<template>
  <div class="pet-card">
    <img :src="imageUrl" :alt="petName" class="pet-image">
    <div class="pet-details">
      <h3 class="pet-name">{{ petName }}</h3>
      <p class="pet-birthdate">Fecha de nacimiento: {{ birthDate }}</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    petName: {
      type: String,
      required: true
    },
    birthDate: {
      type: String,
      required: true
    },
    species: {
      type: String,
      required: true
    }
  },
  computed: {
    imageUrl() {
      // Lógica para obtener la URL de la imagen según la especie del animal
      switch (this.species) {
        case 'dog':
          return 'src/assets/dog.jpg';
        case 'cat':
          return 'src/assets/cat.jpg';
        // Agrega más casos según sea necesario para otras especies
        default:
          return 'src/assets/default.jpg';
      }
    }
  }
};
</script>

<style scoped>
.pet-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 1rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  width: 200px;
}

.pet-image {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 50%;
  margin-bottom: 0.5rem;
}

.pet-name {
  margin: 0;
  font-size: 1.2rem;
}

.pet-birthdate {
  margin: 0;
  font-size: 0.8rem;
}
</style>
