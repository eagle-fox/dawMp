<template>
  <div>
    <navbar></navbar>
    <div class="whole-page">
      <div class="d-flex flex-row bd-highlight mb-3">
        <div class="pet-card">
          <img :src="imageUrl" :alt="petName" class="pet-image">
          <div class="pet-details">
            <h3 class="pet-name">{{ petName }}</h3>
            <p class="pet-birthdate">{{ $t('animaloptions.fecha_nacimiento') }}: {{ birthDate }}</p>
          </div>
        </div>
        <div class="position-box">
          <div class="coordinates">
            <h3 class="position">{{ $t('animaloptions.posicion') }}</h3>
            <h4>X: {{ coordinates.x }}</h4>
            <h4>Y: {{ coordinates.y }}</h4>
          </div>
        </div>
        <div class="position-box">
          <div class="extra-details">
            <h3 class="age">{{ $t('animaloptions.edad') }}: {{ age }} {{ age === 1 ? $t('animaloptions.anio') : $t('animaloptions.anios') }}</h3>
            <h3 class="distance">{{ $t('animaloptions.distancia') }}: {{ distance.toFixed(2) }} km</h3>
          </div>
        </div>
      </div>
      <div class="d-flex flex-row bd-highlight mb-3">
        <button @click="eliminar" class="delete-button">{{ $t('animaloptions.eliminar') }}</button>
        <button @click="editar" class="edit-button"><router-link aria-current="page" class="nav-link active text-light" href="#" to="/editanimal">
                            {{ $t('animaloptions.editar') }}
                        </router-link></button>
      </div>
    </div>
    <footermain></footermain>
  </div>
</template>

<script>
import footermain from '@/components/FooterMain.vue'
import navbar from '@/components/NavBar.vue'

export default {
  name: 'animal',
  components: {
    navbar,
    footermain
  },
  data() {
    return {
      age: 0,
      distance: 0,
      coordinates: {
        x: 0,
        y: 0
      }
    }
  },
  props: {
    petName: {
      type: String,
      default: 'sancho'
    },
    birthDate: {
      type: String,
      default: '01/01/2023'
    },
    species: {
      type: String,
      default: 'dog'
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
    this.setCoordinates();
  },
  methods: {
    eliminar() {
      console.log('animal eliminado');
    },
    editar() {
      console.log('animal editado');
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
      const R = 6371;
      const lat1 = this.coordinates.x;
      const lon1 = this.coordinates.y;
      const lat2 = 3;
      const lon2 = 0.1;

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
    },
    setCoordinates() {
      console.log(this.$store.getters.getCoordinates);
      const storedCoordinates = this.$store.getters.getCoordinates;
      this.coordinates.x = storedCoordinates.latitud;
      this.coordinates.y = storedCoordinates.longitud;
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
