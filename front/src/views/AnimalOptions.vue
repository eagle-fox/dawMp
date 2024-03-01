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
        <div class="position-box">
          <div class="extra-details">
            <h3 class="age">Edad: {{ age }} años</h3>
            <h3 class="distance">Distancia: {{ distance.toFixed(2) }} km</h3> <!-- Aquí se muestra la distancia -->
          </div>
        </div>
      </div>
      <div class="d-flex flex-row bd-highlight mb-3">
        <button @click="eliminar" class="delete-button">Eliminar</button>
        <button @click="editar" class="edit-button">Editar</button>
      </div>
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
  data() {
    return {
      age: 0,
      distance: 0,
      // Aquí puedes definir las coordenadas de los dos puntos
      punto1: { latitud: 1.5, longitud: 2 },
      punto2: { latitud: 3, longitud: 3 }
    }
  },
  props: {
    petName: {
      type: String,
      default: 'Sancho'
    },
    birthDate: {
      type: String,
      default: '01/01/2023'
    },
    species: {
      type: String,
      default: 'dog'
    },
    positionX: {
      type: Number,
      default: 0
    },
    positionY: {
      type: Number,
      default: 0
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
  created() {
    this.calculateAge();
    this.calculateDistance();
  },
  methods: {
    eliminar() {
      console.log('Animal eliminado');
    },
    editar() {
      console.log('Animal editado');
    },
    calculateAge() {
      const today = new Date();
      const birthDate = new Date(this.birthDate);
      const age = today.getFullYear() - birthDate.getFullYear();
      const month = today.getMonth() - birthDate.getMonth();
      if (month < 0 || (month === 0 && today.getDate() < birthDate.getDate())) {
        this.age = age - 1;
      } else {
        this.age = age;
      }
    },
    calculateDistance() {
      const R = 6371; // Radio de la Tierra en kilómetros
      const lat1 = this.punto1.latitud;
      const lon1 = this.punto1.longitud;
      const lat2 = this.punto2.latitud;
      const lon2 = this.punto2.longitud;

      const dLat = this.deg2rad(lat2 - lat1);
      const dLon = this.deg2rad(lon2 - lon1);

      const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(this.deg2rad(lat1)) * Math.cos(this.deg2rad(lat2)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
      this.distance = R * c;
    },
    deg2rad(deg) {
      return deg * (Math.PI / 180);
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

.extra-details {
  margin-top: 10px;
  margin-left: 10px;
}

.age,
.distance {
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

.edit-button {
  position: absolute;
  bottom: 10px;
  right: 10px;
  background-color: #254629;
  color: white;
  border: none;
  border-radius: 5px;
  padding: 5px 10px;
  cursor: pointer;
}

.edit-button:hover {
  background-color: #172d1a;
}
</style>
