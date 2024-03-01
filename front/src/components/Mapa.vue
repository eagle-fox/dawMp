<template>
  <div class="mt-4">
      <div id="viewerMap" style="position: relative">
      <div ref="mapElement" class="viewerMap"></div>
      <div v-if="loading" class="loading-overlay">
        <div class="spinner-border text-primary loadSphere" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
          <div class="loading-text">{{ $t('miscelaneus.loading') }}...</div>
      </div>
    </div>
    <div class="d-flex justify-content-center mt-4 buttonsSlayer"></div>
  </div>
</template>

<script>
import { onMounted, reactive, ref } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import { IconDog } from '@tabler/icons-vue'

export default {
  props: {
    puntos: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
        loading: true,
    }
  },

setup(props) {
    const mapElement = ref(null)
    let state = reactive({
      map: null,
      markers: [],
    })

    onMounted(() => {
      if ('geolocation' in navigator) {
        navigator.geolocation.getCurrentPosition((position) => {
          const { latitude, longitude } = position.coords
          const map = L.map(mapElement.value).setView([latitude, longitude], 15)
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 30,
            minZoom: 3,
            attribution:
              '&copy; <a href="https://www.fbi.gov/investigate">FBI</a>',
          }).addTo(map)

          const marker = L.marker([latitude, longitude])
            .addTo(map)
            .bindPopup('Tu posición actual')
          state.map = map
          state.markers.push(marker)

          props.puntos.forEach((punto) => {
            let iconUrl;
            switch (punto.petSpecie) {
                case 'dog':
                    iconUrl = 'src/assets/pointers/dog.svg';
                    break;
                case 'cat':
                    iconUrl = 'src/assets/pointers/cat.svg';
                    break;
                case 'pig':
                    iconUrl = 'src/assets/pointers/pig.svg';
                    break;
                case 'cow':
                    iconUrl = 'src/assets/pointers/cow.svg';
                    break;
                case 'sheep':
                    iconUrl = 'src/assets/pointers/sheep.svg';
                    break;
                default:
                    iconUrl = 'src/assets/pointers/animal.svg';
            }

            const animalIcon = L.divIcon({
              html: `<img src="${iconUrl}" width="50" height="50" style="top: 50%; left: 50%; transform: translate(-50%,-50%);">`,
              iconSize: [0, 0],
              iconAnchor: [0, 0],
            })
            const marker = L.marker([punto.latitud, punto.longitud], {
              icon: animalIcon,
            })
              .addTo(state.map)
              .bindPopup(punto.petName)
            state.markers.push(marker)
          })

            // this.loading = false // Indica que se ha cargado el mapa y los marcadores
        })
      } else {
        console.error('La geolocalización no es compatible con este navegador.')
      }
    })

    return { mapElement }
  },
  components: {
    IconDog,
  },
}
</script>

<style scoped>

.viewerMap {
    width: 100%;
    max-width: 100%;
    height: 80vh;
    max-height: 100%;
    position: relative;
    z-index: 0;
    overflow: hidden;
}

.loading-overlay {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.loading-text {
  position: absolute;
  top: calc(50% + 2rem);
  left: 50%;
  transform: translate(-50%, -50%);
}

</style>