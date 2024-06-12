<script>
import NavBar from '@/components/NavBar.vue'
import { styleAssets } from '@/assets/config.json'
import AnimalFormVue from '@/components/AnimalForm.vue'
import parseUrl from '@/assets/js/miscelaneus'


export default {
    name: 'About',
    components: {
        NavBar,
        AnimalFormVue
    },
    data() {
        return {
            svgFile: null,
        }
    },
    methods: {
        loadSvgFile() {
            this.svgFile = 'src/assets/' + styleAssets.svgData.typoBackground
        },
    },
    mounted() {
        this.loadSvgFile()
    },
}
</script>

<template>
    <NavBar></NavBar>
    <main class="main-container" :style="{ 'background-image': 'url(' + svgFile + ')' }">
        <AnimalFormVue></AnimalFormVue>
    </main>
</template>

<style scoped>
.main-container {
    height: calc(97vh - 58px);
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>