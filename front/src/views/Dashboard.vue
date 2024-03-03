<script>
import NavBar from '@/components/NavBar.vue'
import FooterMain from '@/components/FooterMain.vue'
import Mapa from '@/components/Mapa.vue'
import PetCard from '@/components/PetCard.vue'
import Query from '@/types/Query.js'
import URL from '@/types/URL.js'
import Cookies from 'js-cookie'
import BearerToken from '@/types/BearerToken.js'
import { IconSearch } from '@tabler/icons-vue'


export default {
    name: 'Dashboard',
    components: {
        NavBar,
        Mapa,
        PetCard,
        FooterMain,
        IconSearch
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
                return true;
            }

            this.userData = {
                name: data.name,
                gmail: data.gmail,
                iotDevices: [],
            }

        },
        async getDevicesByMyself() {
            try {
                return this.$store.getters.getIotDevices
            } catch (err) {
                let response = err
            }
        },
        async tryGetLoginData() {
            try {
                if (this.$store.getters.getUserSession) {
                    this.loadUserData(this.$store.getters.getUserSession);
                    console.log(this.$store.getters.getUserSession.token);
                    this.loadIotDevices().then((data) => {

                        for (let cord of data) {
                            let animalData = {
                                petName: cord.name,
                                latitud: parseFloat(cord.last_latitude),
                                longitud: parseFloat(cord.last_longitude),
                                petSpecie: cord.especie,
                                petDate: new Date(cord.created_at),
                                petCords: [parseFloat(cord.last_latitude), parseFloat(cord.last_longitude)]
                            }
                            this.devicesData.push(animalData);
                        }
                    });
                }
            } catch (err) {

            }
        }, 
        // It makes a request to the API to get the animal data via the user's token.
        async loadIotDevices() {
            try {
                let myUrl = new URL('http', 'localhost', 2003);
                let query = new Query(myUrl).withAuth(new BearerToken(this.$store.getters.getUserSession.token));
                let response = await query.getIotDevicesBySelf();
                response = response.data;

                await this.$store.dispatch('setIotDevices', response);
                this.$router.push('/dashboard')
                return response;
            } catch (error) {
                throw error;
            }
        },

        // If the user has his token, returns false
        // If it does not check for the cookie it calls `tokenCookie`, if it does, it makes the API request to get all the user's data.

        async loadUserSessionByCookie() {
            if (this.$store.getters.getUserSession.token !== null) {
                return false;
            }

            if (Cookies.get('tokenCookie')) {
                let myUrl = new URL('http', 'localhost', 2003);
                let query = new Query(myUrl).withAuth(new BearerToken(Cookies.get('tokenCookie')));
                let response = await query.login();

                console.log('entry');

                let userData = {
                    name: response.user.nombre,
                    email: response.user.email,
                    role: response.user.rol,
                    token: response.user.clients[0].token,
                }

                this.$store
                    .dispatch('updateUserSession', userData)
                    .catch((error) => {
                        console.error('Error al crear la nueva userSession:', error)
                    })

                return true;

            }
        }
    },
    computed: {
        filteredPets() {
            return this.devicesData.filter((pet) =>
                pet.petName.toLowerCase().includes(this.searchTerm.toLowerCase())
            );
        }
    },
    mounted() {
        // This method obtains the user's animal data via the cookie with the token, but first checks if this information already exists in the user's session.
        //  - If it finds the data in the session it stops loading them from the cookie and does not make the API request.
        // - If it does not find them, it makes the request to the API using the token stored in the cookie.

        this.loadUserSessionByCookie()
            .then((result) => {
                if (result) {
                    this.tryGetLoginData().then(() => {
                        setTimeout(() => {this.loading = false;}, 1000);
                    });
                } else {
                    this.loading = false;
                    this.loadUserData(this.$store.getters.getUserSession);
                    for (let cord of this.$store.getters.getIotDevices) {
                        let animalData = {
                            petName: cord.name,
                            latitud: parseFloat(cord.last_latitude),
                            longitud: parseFloat(cord.last_longitude),
                            petSpecie: cord.especie,
                            petDate: new Date(cord.created_at),
                            petCords: [parseFloat(cord.last_latitude), parseFloat(cord.last_longitude)]
                        }
                        this.devicesData.push(animalData);
                    }
                }
            });

    }
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
                    <h3>{{ $t('miscelaneus.welcome') }}, {{ userData.name }}</h3>
                    <div class="d-flex gap-2 justify-content-between unterscharführer">
                        <!-- Left Site-->
                        <div class="d-inline-flex flex-column gap-2">
                            <div class="d-inline-flex flex-column gap-1 p-2 ">
                                <h4>{{ $t('miscelaneus.pets') }}:</h4>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">
                                        <IconSearch></IconSearch>
                                    </span>
                                    <input type="text" class="form-control" placeholder="Buscar mascota"
                                        aria-label="Buscar mascota" aria-describedby="basic-addon1"
                                        v-model="searchTerm" />
                                </div>
                                <div class="d-inline-flex flex-column gap-1 scroll-container">
                                    <div v-for="pet in filteredPets" :key="pet.id">
                                        <PetCard :petCords="pet" :petDate="pet.petDate" :petName="pet.petName"
                                            :petSpecies="pet.petSpecie">
                                        </PetCard>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Site-->
                        <div v-if="showMap && devicesData.length > 0" ref="mapContainer" class="obergruppenführer">
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


.scroll-container {
    max-height: calc(50vh - 20px);
    overflow: hidden;
    overflow-y: scroll;
}

.full {
    height: calc(80vh - 65px);
}

.obergruppenführer .unterscharführer {}

.unterscharführer {
    overflow: hidden;
    height: 100%;
}

.obergruppenführer {
    padding: 1em;
    width: 100%;
    height: calc(60vh);
    position: relative;
    z-index: 0;
    overflow: hidden;
}
</style>