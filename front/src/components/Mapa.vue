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
      lon: -3.703790,
      map: null,
      marker: null
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
      function obtenerPosicion(position){
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;
        return [latitude, longitude];
      }

      function errorC(error){
        switch (error.code) {
          case error.PERMISSION_DENIED:
            console.error("El usuario denegó la solicitud de geolocalización.");
            break;
          case error.POSITION_UNAVAILABLE:
            console.error("La información de ubicación no está disponible.");
            break;
          case error.TIMEOUT:
            console.error("Se ha excedido el tiempo de espera para obtener la ubicación.");
            break;
          case error.UNKNOWN_ERROR:
            console.error("Se produjo un error desconocido al obtener la ubicación.");
            break;
        }
      }

      if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(
          (position) => {
            const newPosition = obtenerPosicion(position);
            state.marker.setLatLng(newPosition).bindPopup('Nueva posición').openPopup();
            state.map.setView(newPosition, 30);
          },
          errorC
        );
      } else {
        console.error("La geolocalización no es compatible con este navegador.");
      }
    };

    return { mapElement, changePos };
  },
};
</script>
