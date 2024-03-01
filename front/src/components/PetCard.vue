<script>
import { IconCake, IconCalendar, IconSettings } from '@tabler/icons-vue'

export default {
    name: 'PetCard',
    components: {
        IconCalendar,
        IconCake,
        IconSettings,
    },
    data() {
        return {
            edadNacimiento: new Date(this.petDate),
            edadDiff: new Date(Date.now() - this.edadNacimiento),
            nombre: this.petName,
            especie: this.petSpecies,
        }
    },
    props: {
        petName: String,
        petDate: Date,
        petSpecies: String,
        petCords: Array
    },
    methods: {
        calcAge() {
            const now = new Date()
            const birthDate = new Date(this.petDate)
            const diffTime = Math.abs(now - birthDate)
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
            const diffMonths = Math.floor(diffDays / 30.44)
            const diffYears = Math.floor(diffDays / 365.25)

            if (diffYears > 0) {
                return `${diffYears} ${this.$t('miscelaneus.years')}${diffYears > 1 ? 's' : ''}`
            } else if (diffMonths > 0) {
                return `${diffMonths} ${this.$t('miscelaneus.mounths')}${diffMonths > 1 ? 's' : ''}`
            } else {
                return `${diffDays} ${this.$t('miscelaneus.days')}${diffDays > 1 ? 's' : ''}`
            }
        },
    },
    computed: {
        icono() {
            const species = this.petSpecies.toLowerCase()
            if (['cow',
                 'sheep',
                 'dog',
                 'cat'].includes(species)) {
                return `${species}.svg`
            } else {
                return 'default.svg'
            }
        },
    },
}
</script>

<template>
    <div class="card">
        <img
            :src="'../src/assets/pointers/' + icono"
            alt=""
            class="animal-image"
        />
        <div class="card-body">
            <h5 class="card-title">{{ petName }}</h5>
            <div>
                <div>
                    <div>{{ $t('miscelaneus.birthday') }}: {{ petDate.toLocaleDateString() }}
                        <IconCake :size="20" class="mb-1"></IconCake>
                    </div>
                </div>
                <div>
                    <div class="d-flex aling-items-center gap-1">
                        <div>{{ $t('miscelaneus.age') }}: {{ calcAge() }}</div>
                        <IconCalendar :size="20" class="mb-1"></IconCalendar>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-1">
            <button class="btn btn-primary">Mapa</button>
            <button class="btn btn-light" type="button">
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