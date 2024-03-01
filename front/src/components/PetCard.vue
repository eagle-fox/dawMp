<script>
import { IconCake, IconCalendar, IconSettings } from '@tabler/icons-vue'
import { styleAssets } from '@/assets/config.json'

export default {
    name: 'PetCard',
    components: {
        IconCalendar,
        IconCake,
        IconSettings,
    },
    data() {
        return {
            calcAge: null,
            petSpecieFileUrl: null,
            switchAge: true,
            
        }
    },
    props: {
        petName: String,
        petDate: Date,
        petSpecies: String,
    },
    // methods: {
    //     calculateAge(birthday) {
    //         let ageDifMs = Date.now() - birthday.getTime()
    //         let ageDate = new Date(ageDifMs)
    //         this.calcAge = Math.abs(ageDate.getUTCFullYear() - 1970)

    //         if (this.calcAge < 12) {
    //             this.switchAge = false
    //         }
    //     },
    //     chargeSpecieImage(specie) {
    //         let animalDictionary = styleAssets.animalPointers

    //         if (specie in animalDictionary) {
    //             this.petSpecieFileUrl = animalDictionary[specie]
    //         } else {
    //             let animalKeysArray = Object.keys(animalDictionary)
    //             let animalAleatorio =
    //                 animalKeysArray[Math.floor(Math.random() * animalKeysArray.length)]

    //             this.petSpecieFileUrl = animalDictionary[animalAleatorio]
    //         }
    //     },
    // },
    // mounted() {
    //     this.calculateAge(this.petDate)
    //     this.chargeSpecieImage(this.petSpecies)
    // },
    // computed: {
    //     formattedDate() {
    //         //const day = this.petDate.getDate()
    //         //const month = this.petDate.getMonth() + 1 // getMonth() returns a zero-based value (where zero indicates the first month)
    //         //const year = this.petDate.getFullYear().toString().substr(-2) // get last two digits of year
    //         //return `${day}/${month}/${year}`
    //     },
    // },
}
</script>

<template>
    <div class="card">
        <img
            :src="'../src/assets/' + this.petSpecieFileUrl"
            alt=""
            class="animal-image"
        />
        <div class="card-body">
            <h5 class="card-title">{{ petName }}</h5>
            <div>
                <div>
                    <div>Date of Birth: {{ formattedDate }}
                        <IconCake :size="20" class="mb-1"></IconCake>
                    </div>
                </div>
                <div>
                    <div class="d-flex aling-items-center gap-1">
                        {{ $t('miscelaneus.age') }}: {{ this.calcAge }}
                        <p v-if="!switchAge">{{ this.$t('miscelaneus.mounths') }}</p>
                        <p v-if="switchAge">{{ this.$t('miscelaneus.years') }}</p>
                        <IconCalendar :size="20" class="mb-1"></IconCalendar>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-1">
            <button class="btn btn-primary">Mapa</button>
            <button type="button" class="btn btn-light">
                <IconSettings :size="32"></IconSettings>
            </button>
        </div>
    </div>
</template>

<style scoped>
.card {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: row;
    padding-right: 15px;
}

.animal-image {
    width: 55px;
    margin-bottom: 10px;
    margin-left: 15px;
}
</style>