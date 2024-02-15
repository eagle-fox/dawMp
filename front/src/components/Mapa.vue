<script>
import { ref, onMounted, reactive } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

export default {
  setup() {
    const mapElement = ref(null);

    let state = reactive({
      lat: 40.2451186,
      lon: -3.7020313,
      map: null,
      marker: null
    });

    let state2 = reactive({
      lat: 40.2451541,
      lon: -8.7207,
      map: null,
      marker: null
    });

    onMounted(() => {
      const map = L.map(mapElement.value).setView([state.lat, state.lon], 30);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">FBI</a>'
      }).addTo(map);

      // Example for placing several markers on the map

      const marker = L.marker([state.lat, state.lon]).addTo(map).bindPopup('Casa del Puto del Carlos');
      const marker2 = L.marker([state2.lat, state2.lon]).addTo(map).bindPopup('Marker 2');
      
      state.map = map;
      state.markers = [marker, marker2]
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


<template>
  <div class="mt-4">
    <div ref="mapElement" class="viewerMap"></div>

    <div class="d-flex justify-content-center mt-4 buttonsSlayer">
      <button class="btn btn-primary" @click="changePos">Cambiar Posición</button>
    </div>
  </div>
</template>


<style scoped>

.viewerMap{
  width: 600px;
  height: 500px;

  border-radius: 10px;
}

@media screen and (max-width: 700px) {
  .viewerMap{
    width: 500px;
    height: 350px;
  }
}

@media screen and (max-width: 550px) {
  .viewerMap{
    width: 350px;
    height: 300px;

  }

  .buttonsSlayer{
    flex-direction: column;
  }
}

</style>
