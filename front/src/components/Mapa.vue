<script>
import { ref, onMounted, reactive } from 'vue'
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
            maxZoom: 19,
            attribution:
              '&copy; <a href="http://www.openstreetmap.org/copyright">FBI</a>',
          }).addTo(map)

          const marker = L.marker([latitude, longitude])
            .addTo(map)
            .bindPopup('Tu posición actual')
          state.map = map
          state.markers.push(marker)

          props.puntos.forEach((punto) => {
            const cowIcon = L.divIcon({
              iconUrl: './assets/pointers/animal.svg',
              iconSize: [38, 15],
              iconAnchor: [22, 24],
            })
            const marker = L.marker([punto.latitud, punto.longitud], {
              icon: cowIcon,
            })
              .addTo(state.map)
              .bindPopup(punto.name)
            state.markers.push(marker)
          })
        })
      } else {
        console.error('La geolocalización no es compatible con este navegador.')
      }
    })

    const addMarker = () => {
      if (state.map) {
        const latitude = state.map.getCenter().lat
        const longitude = state.map.getCenter().lng
        const marker = L.marker([latitude, longitude])
          .addTo(state.map)
          .bindPopup('Nuevo marcador')
        state.markers.push(marker)
      }
    }

    return { mapElement, addMarker }
  },
  components: {
    IconDog,
  },
}
</script>

<template>
  <div class="mt-4">
    <div ref="mapElement" class="viewerMap"></div>

    <div class="d-flex justify-content-center mt-4 buttonsSlayer">
      <button class="btn btn-primary" @click="changePos">
        Cambiar Posición
      </button>
    </div>
  </div>
</template>

<style scoped>
.viewerMap {
  width: 600px;
  height: 500px;
  border-radius: 10px;
}

@media screen and (max-width: 700px) {
  .viewerMap {
    width: 500px;
    height: 350px;
  }
}

@media screen and (max-width: 550px) {
  .viewerMap {
    width: 350px;
    height: 300px;
  }

  .buttonsSlayer {
    flex-direction: column;
  }
}
</style>
