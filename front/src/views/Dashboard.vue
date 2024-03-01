<script>
import NavBar from '@/components/NavBar.vue'
import FooterMain from '@/components/FooterMain.vue'
import Mapa from '@/components/Mapa.vue'
import PetCard from '@/components/PetCard.vue'
import Query from '@/types/Query.js'
import URL from '@/types/URL.js'
import Cookies from 'js-cookie'
import BearerToken from '@/types/BearerToken.js'


export default {
    name: 'Dashboard',
    components: {
        NavBar,
        Mapa,
        PetCard,
        FooterMain,
    },
    data() {
        return {
            userData: [],
            devicesData: [],
            showMap: true,
            loading: true,
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
                            }
                            this.devicesData.push(animalData);
                        }
                    });
                }
            } catch (err) {

            }
        }, async loadIotDevices() {
            try {
                let myUrl = new URL('http', 'localhost', 2003);
                let query = new Query(myUrl).withAuth(new BearerToken(this.$store.getters.getUserSession.token));
                let response = await query.getIotDevicesBySelf();
                response = response.data;

                await this.$store.dispatch('setIotDevices', response);
                this.$router.push('/dashboard')
                return response;
            } catch (error) {
                // Manejar el error según tus necesidades
                console.error("Error al cargar los dispositivos IoT:", error);
                throw error; // Puedes lanzar el error nuevamente si es necesario
            }
        },
        async loadUserSessionByCookie() {
            if (Cookies.get('tokenCookie')) {
                let myUrl = new URL('http', 'localhost', 2003);
                let query = new Query(myUrl).withAuth(new BearerToken(Cookies.get('tokenCookie')));
                let response = await query.login();

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

            }
        }
    },
    mounted() {

        this.loadUserSessionByCookie()
            .then(() => {
                this.tryGetLoginData().then(() => {setTimeout(() => {this.loading = false},1000)});
            })


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
                    <h2>{{ $t('miscelaneus.welcome') }}, {{ userData.name }}</h2>
                    <div class="d-flex gap-2 justify-content-between unterscharführer">
                        <!-- Left Site-->
                        <div class="d-inline-flex flex-column gap-2">
                            <h4>{{ $t('miscelaneus.pets') }}:</h4>

                            <div class="d-inline-flex flex-column gap-4 p-2 scroll-container">
                                <div v-for="pet in devicesData" :key="pet.id">
                                    <PetCard :petCords="pet.petCords" :petDate="pet.petDate" :petName="pet.petName" :petSpecies="pet.petSpecie">
                                    </PetCard>
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
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}


.scroll-container {
    max-height: 550px;
    overflow: hidden;
    overflow-y: scroll;
}

.scroll-container::-webkit-scrollbar {
    width: 12px;
}

.scroll-container::-webkit-scrollbar-thumb {
    background-color: #888;
}

.scroll-container::-webkit-scrollbar-thumb:hover {
    background-color: #555;
}

.full {
    height: calc(80vh - 65px);
}

.obergruppenführer .unterscharführer {
    height: 100%;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}

.unterscharführer {
    overflow: hidden;
    height: 100%;
}

.obergruppenführer {
    padding: 1em;
    width: 100%;
    height: calc(50vh);
    border-radius: 10px;
    position: relative;
    z-index: 0;
}


</style>