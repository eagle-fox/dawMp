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
    </div>
</template>

<script>
import { reactive } from 'vue'
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
    methods: {
        initializeMap() {
            if ('geolocation' in navigator) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const { latitude, longitude } = position.coords
                    const map = L.map(this.$refs.mapElement)
                        .setView([latitude,
                                  longitude], 15)
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 30,
                        minZoom: 3,
                        attribution: '&copy; <a href="https://www.fbi.gov/investigate">FBI</a>',
                    }).addTo(map)

                    const marker = L.marker([latitude,
                                             longitude])
                        .addTo(map)
                        .bindPopup('Tu posición actual')
                    this.state.map = map
                    this.state.markers.push(marker)
                    console.log(this.puntos)

                    if (!Array.isArray(this.puntos)) {
                        let iconUrl
                        console.log('THIS.PUNTOS ->')
                        console.log(this.puntos)
                        switch (this.puntos.petSpecie) {
                            case 'dog':
                                iconUrl = 'src/assets/pointers/dog.svg'
                                break
                            case 'cat':
                                iconUrl = 'src/assets/pointers/cat.svg'
                                break
                            case 'pig':
                                iconUrl = 'src/assets/pointers/pig.svg'
                                break
                            case 'cow':
                                iconUrl = 'src/assets/pointers/cow.svg'
                                break
                            case 'sheep':
                                iconUrl = 'src/assets/pointers/sheep.svg'
                                break
                            default:
                                iconUrl = 'src/assets/pointers/animal.svg'
                        }
                        const animalIcon = L.divIcon({
                            html: `<img src="${iconUrl}" width="50" height="50" style="top: 50%; left: 50%; transform: translate(-50%,-50%);">`,
                            iconSize: [0,
                                       0],
                            iconAnchor: [0,
                                         0],
                        })
                        const marker = L.marker([this.puntos[0],
                                                 this.puntos[1]], {
                            icon: animalIcon,
                        })
                            .addTo(this.state.map)
                            .bindPopup(this.puntos.petName)
                        this.state.markers.push(marker)
                        this.state.map.setView([this.puntos[0],
                                                this.puntos[1]], 15)

                    } else {
                        this.puntos.forEach((punto) => {
                            let iconUrl
                            switch (punto.petSpecie) {
                                case 'dog':
                                    iconUrl = 'src/assets/pointers/dog.svg'
                                    break
                                case 'cat':
                                    iconUrl = 'src/assets/pointers/cat.svg'
                                    break
                                case 'pig':
                                    iconUrl = 'src/assets/pointers/pig.svg'
                                    break
                                case 'cow':
                                    iconUrl = 'src/assets/pointers/cow.svg'
                                    break
                                case 'sheep':
                                    iconUrl = 'src/assets/pointers/sheep.svg'
                                    break
                                default:
                                    iconUrl = 'src/assets/pointers/animal.svg'
                            }

                            const animalIcon = L.divIcon({
                                html: `<img src="${iconUrl}" width="50" height="50" style="top: 50%; left: 50%; transform: translate(-50%,-50%);">`,
                                iconSize: [0,
                                           0],
                                iconAnchor: [0,
                                             0],
                            })
                            const marker = L.marker([punto.latitud,
                                                     punto.longitud], {
                                icon: animalIcon,
                            })
                                .addTo(this.state.map)
                                .bindPopup(punto.petName)
                            this.state.markers.push(marker)
                        })
                    }

                    this.setLoadingStatus()
                })
            } else {
                console.error('La geolocalización no es compatible con este navegador.')
            }
        },
        setLoadingStatus() {
            this.loading = false
        },
    },
    computed: {
        state() {
            return reactive({
                map: null,
                markers: [],
            })
        },
    },
    mounted() {
        this.initializeMap()
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