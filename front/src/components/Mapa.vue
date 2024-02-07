<template>
  <div>
    <div ref="mapElement" style="height: 600px; width: 800px;"></div>
    <button @click="changePos">Cambiar Posición</button>
  </div>
</template>

<script>
import { ref, onMounted, reactive } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

export default {
  setup() {
    const mapElement = ref(null);

    let state = reactive({
      lat: 40.416775,
      lon: -3.703790
    });

    onMounted(() => {
      const map = L.map(mapElement.value).setView([state.lat, state.lon], 30);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
      }).addTo(map);

      const marker = L.marker([state.lat, state.lon]).addTo(map).bindPopup('La prima del ricardo');

      state.map = map;
      state.marker = marker;
    });

    const changePos = () => {

      state.marker.setLatLng([40.417, -3.704]).bindPopup('Nueva posición');
      state.map.setView([40.417, -3.704], 30);

      state.lat = 40.417;
      state.lon = -3.704;
    };

    return {mapElement, changePos};
  },
};
</script>
