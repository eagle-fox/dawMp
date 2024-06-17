<script>
import NavBar from '@/components/NavBar.vue'
import FooterMain from '@/components/FooterMain.vue'
import Mapa from '@/components/Mapa.vue'
import PetCard from '@/components/PetCard.vue'
import Query from '@/types/Query.js'
import URL from '@/types/URL.js'
import Cookies from 'js-cookie'
import BearerToken from '@/types/BearerToken.js'
import { IconPlus, IconSearch } from '@tabler/icons-vue'
import parseUrl from '@/assets/js/miscelaneus'

export default {
    name: 'Dashboard',
    components: {
        NavBar,
        Mapa,
        PetCard,
        FooterMain,
        IconSearch,
        IconPlus,
    },
    data() {
        return {
            userData: [],
            devicesData: [],
            showMap: true,
            loading: true,
            searchTerm: '',
        }
    },
    methods: {
        loadUserData(data) {
            if (!data) {
                this.userData = {
                    name: this.$store.getters.getUserSession.name,
                    gmail: this.$store.getters.getUserSession.gmail,
                    iotDevices: [],
                }
                return true
            }
            this.userData = {
                name: data.name,
                gmail: data.gmail,
                iotDevices: [],
            }
        },
        async getDevicesByMyself() {
            try {
                let myUrl = new URL('http', 'localhost', 2003)
                console.log('Logeando en getDevicesByMyself con el token: ' + this.$store.getters.getUserSession.token)
                let query = new Query(myUrl).withAuth(new BearerToken(this.$store.getters.getUserSession.token))
                let response = await query.getIotDevicesBySelf()
                console.log('Respuesta de getDevicesByMyself: ', response)
                return response // Asegurémonos de devolver `response.data` como un array
            } catch (err) {
                console.error('Error al cargar los dispositivos IoT:', err)
                return [] // Devuelve un array vacío en caso de error
            }
        },
        async tryGetLoginData() {
            try {
                if (this.$store.getters.getUserSession) {
                    this.loadUserData(this.$store.getters.getUserSession)
                    console.log(this.$store.getters.getUserSession.token)
                    this.loadIotDevices().then((data) => {
                        console.log('Entrando en el then de loadIotDevices')
                        for (let cord of data) {
                            console.log(cord)
                            let animalData = {
                                petName: cord.Name,
                                latitud: parseFloat(cord.LastLatitude),
                                longitud: parseFloat(cord.LastLongitude),
                                petSpecie: cord.Especie,
                                petDate: new Date(cord.CreatedAt),
                                petCords: [parseFloat(cord.LastLatitude), parseFloat(cord.LastLongitude)],
                            }
                            this.devicesData.push(animalData)
                        }
                    })
                }
            } catch (err) {
                this.$router.push('/login')
            }
        },
        async loadIotDevices() {
            try {
                let myUrl = new URL('http', 'localhost', 2003)
                let query = new Query(myUrl).withAuth(new BearerToken(this.$store.getters.getUserSession.token))
                let response = await query.getIotDevicesBySelf()
                let devices = response.data // Almacenamos la respuesta en la variable `devices`

                for (let cord of devices) {
                    console.log(cord)
                }

                await this.$store.dispatch('setIotDevices', devices)
                this.$router.push('/dashboard')
                return devices
            } catch (error) {
                console.error('Error al cargar los dispositivos:', error)
                this.$router.push('/login')
                throw error
            }
        },
        async loadUserSessionByCookie() {
            if (this.$store.getters.getUserSession.token !== null) {
                return false
            }

            if (Cookies.get('tokenCookie')) {
                let myUrl = new URL('http', 'localhost', 2003)
                let query = new Query(myUrl).withAuth(new BearerToken(Cookies.get('tokenCookie')))
                let response = await query.login()

                let userData = {
                    name: response.user.nombre,
                    email: response.user.email,
                    role: response.user.rol,
                    token: response.user.clients[0].token,
                }

                this.$store.dispatch('updateUserSession', userData)
                    .then(() => {
                        console.log('User session updated successfully')
                    })
                    .catch((error) => {
                        console.error('Error al crear la nueva userSession:', error)
                    })

                return true
            }
        },
    },
    computed: {
        filteredPets() {
            return this.devicesData.filter((pet) =>
                pet.petName.toLowerCase().includes(this.searchTerm.toLowerCase()),
            )
        },
    },
    mounted() {
        this.loading = false
        this.loadUserData(this.$store.getters.getUserSession)
        this.getDevicesByMyself()
            .then((data) => {
                console.log('Dispositivos IoT:', data)
                for (let cord of data) {
                    let animalData = {
                        petName: cord.Name,
                        latitud: parseFloat(cord.LastLatitude),
                        longitud: parseFloat(cord.LastLongitude),
                        petSpecie: cord.Especie,
                        petDate: new Date(cord.CreatedAt),
                        petCords: [parseFloat(cord.LastLatitude), parseFloat(cord.LastLongitude)],
                    }
                    this.devicesData.push(animalData)
                }
            })
            .catch((err) => {
                console.error('Error al cargar los dispositivos IoT:', err)
            })
    },
}
</script>

<template>
    <div id="pagePrincipal">
        <NavBar></NavBar>

        <div class="p-2">
            <div class="p-4 bg-light rounded container-fluid full">
                <div v-if="loading" class="h-100">
                    <div class="d-flex justify-content-center align-items-center h-100">
                        <div class="d-flex flex-column align-items-center gap-2">
                            <div class="spinner-border text-primary loadSphere" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="loading-text">{{ $t('miscelaneus.loading') }}...</div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <h3 class="text-black">{{ $t('miscelaneus.welcome') }}, {{ userData.name }}</h3>
                    <div class="d-flex gap-2 justify-content-between cssrandom1">
                        <div class="d-inline-flex flex-column gap-2">
                            <div class="d-inline-flex flex-column gap-1 p-2 ">
                                <h4 class="text-black">{{ $t('miscelaneus.pets') }}:</h4>
                                <div class="input-group mb-3">
                                    <span id="basic-addon1" class="input-group-text">
                                        <IconSearch></IconSearch>
                                    </span>
                                    <input v-model="searchTerm" aria-describedby="basic-addon1" aria-label="Buscar mascota"
                                           class="form-control" placeholder="Buscar mascota" type="text" />
                                </div>
                                <div class="d-inline-flex flex-column gap-1 scroll-container">
                                    <div class="card card-plus" @click="letA">
                                        <div class="card-body gray-card d-flex justify-content-center">
                                            <IconPlus></IconPlus>
                                        </div>
                                    </div>
                                    <div v-for="pet in filteredPets" :key="pet.ID">
                                        <PetCard :petCords="pet.petCords" :petDate="pet.petDate" :petName="pet.petName"
                                                 :petSpecies="pet.petSpecie"></PetCard>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="showMap && devicesData.length > 0" ref="mapContainer" class="cssrandom0">
                            <Mapa :puntos="devicesData" width=""></Mapa>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <FooterMain></FooterMain>
    </div>
</template>

<style scoped>
#pagePrincipal {
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow: hidden;
    overflow-y: scroll;
}

.gray-card {
    background-color: rgb(223, 223, 223);
}

.gray-card:hover {
    background-color: rgb(223, 218, 218);
}

.card-plus {
    background-color: rgb(223, 223, 223);
    border: 0.2em dashed #adadad;
}

.card-plus:hover {
    background-color: rgb(223, 218, 218);
}

.scroll-container {
    max-height: calc(50vh - 20px);
    overflow: hidden;
    overflow-y: scroll;
}

.full {
    height: calc(80vh - 65px);
}

.cssrandom0 .cssrandom1 {
}

.cssrandom1 {
    overflow: hidden;
    height: 100%;
}

.cssrandom0 {
    padding: 1em;
    width: 100%;
    height: calc(60vh);
    position: relative;
    z-index: 0;
    overflow: hidden;
}
</style>