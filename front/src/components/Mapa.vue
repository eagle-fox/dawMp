<template>
  <div ref="mapElement" style="height: 600px; width: 800px;"></div>
</template>

<script>
import { ref, onMounted } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

export default {
  setup() {
    const mapElement = ref(null);

    onMounted(() => {
      const map = L.map(mapElement.value).setView([40.416775, -3.703790], 13);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
      }).addTo(map);
    });

    return { mapElement };
  }
};
</script>