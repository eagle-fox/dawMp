<template>
  <div>
    <NavBar></NavBar>
    <div class="whole-page">
      <div class="d-flex flex-row bd-highlight mb-3">
        <div class="pet-card">
          <img :src="imageUrl" :alt="petName" class="pet-image">
          <div class="pet-details">
            <h3 class="pet-name">{{ petName }}</h3>
            <p class="pet-birthdate">Fecha de nacimiento: {{ birthDate }}</p>
          </div>
        </div>
        <div class="position-box">
          <div class="coordinates">
            <h3 class="position">Posición</h3>
            <h4>X: {{ positionX }}</h4>
            <h4>Y: {{ positionY }}</h4>
          </div>
        </div>
      </div>
      <button @click="eliminar" class="delete-button">Eliminar</button>
    </div>
    <FooterMain></FooterMain>
  </div>
</template>

<script>
import FooterMain from '@/components/FooterMain.vue'
import NavBar from '@/components/NavBar.vue'

export default {
  name: 'Animal',
  components: {
    NavBar,
    FooterMain
  },
  props: {
    petName: {
      type: String,
      default: 'Sancho' // Valor predeterminado para el nombre del animal
    },
    birthDate: {
      type: String,
      default: '01/01/2022' // Valor predeterminado para la fecha de nacimiento
    },
    species: {
      type: String,
      default: 'dog' // Valor predeterminado para la especie del animal
    },
    positionX: {
      type: Number,
      default: 0 // Valor predeterminado para la posición en el eje X
    },
    positionY: {
      type: Number,
      default: 0 // Valor predeterminado para la posición en el eje Y
    }
  },
  computed: {
    imageUrl() {
      switch (this.species) {
        case 'dog':
          return 'src/assets/pointers/dog.svg';
        case 'cat':
          return 'src/assets/pointers/cat.svg';
        case 'pig':
          return 'src/assets/pointers/cat.svg';
        case 'cow':
          return 'src/assets/pointers/cow.svg';
        case 'sheep':
          return 'src/assets/pointers/sheep.svg';
        default:
          return 'src/assets/animal.svg';
      }
    }
  },
  methods: {
    eliminar() {
      // Aquí puedes implementar la lógica para eliminar el animal
      console.log('Animal eliminado');
    }
  }
};
</script>

<style scoped>
.whole-page {
  position: relative;
  margin: 1%;
  background-color: #e6e6e6;
  padding: 1%;
  border-radius: 20px;
  height: 400px;
}

.pet-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 1rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  width: 200px;
  margin-top: 1%;
}

.pet-image {
  width: 100px;
  height: 100px;
  object-fit: cover;
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

.position-box {
  border: 1px solid #ccc;
  border-radius: 8px;
  padding: 2px;
  margin-top: 1%;
  margin-left: 30px;
  width:25rem;
}

.coordinates {
  margin-top: 1%;
  padding-left: 5%;
}

.position {
  margin-bottom: 5px;
}

.delete-button {
  position: absolute;
  bottom: 10px;
  left: 10px;
  background-color: #f44336;
  color: white;
  border: none;
  border-radius: 5px;
  padding: 5px 10px;
  cursor: pointer;
}

.delete-button:hover {
  background-color: #d32f2f;
}
</style>
