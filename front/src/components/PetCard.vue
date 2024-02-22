<script>
import { IconCalendar, IconCake, IconSettings } from '@tabler/icons-vue';
import { styleAssets } from '@/assets/config.json';

export default {
    name: 'PetCard',
    components: {
        IconCalendar,
        IconCake,
        IconSettings
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
        petDate: String,
        petSpecies: String,
    }, methods: {
        
        calculateAge(birthday) {
            let birthday_arr = birthday.split("/");
            let birthday_date = new Date(birthday_arr[2], birthday_arr[1] - 1, birthday_arr[0]);
            let ageDifMs = Date.now() - birthday_date.getTime();
            let ageDate = new Date(ageDifMs);
            let calculatedAgeYears = Math.abs(ageDate.getUTCFullYear() - 1970);

            if (calculatedAgeYears === 0) {
                var calculatedAgeMonths = ageDate.getUTCMonth();
                this.calcAge = calculatedAgeMonths;
                this.switchAge = false;

                console.log(this.switchAge)
            } else {
                this.calcAge = calculatedAgeYears;
                this.switchAge = true;
            }
        },
        chargeSpecieImage(specie){

            let animalDictionary = styleAssets.animalPointers;

            if (specie in animalDictionary){
                this.petSpecieFileUrl = animalDictionary[specie];
            }else{
                let  animalKeysArray = Object.keys(animalDictionary);
                let animalAleatorio = animalKeysArray[Math.floor(Math.random() * animalKeysArray.length)];

                this.petSpecieFileUrl = animalDictionary[animalAleatorio];

            }

        }

    }, mounted() {
        this.calculateAge(this.petDate);
        this.chargeSpecieImage(this.petSpecies);
    }

}

</script>

<template>
    <div class="card">
        <img :src="'../src/assets/' + this.petSpecieFileUrl " class="animal-image" alt="">
        <div class="card-body">
            <h5 class="card-title">{{ petName }}</h5>
            <div>
                <div>{{ $t('miscelaneus.date') }}: {{ petDate }}
                    <IconCake :size="20" class="mb-1"></IconCake>
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
}</style>