<script>
import NavBar from '@/components/NavBar.vue'
import { styleAssets } from '@/assets/config.json'
import AnimalFormVue from '@/components/AnimalForm.vue'

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
    <main :style="{ 'background-image': 'url(' + svgFile + ')' }">
        <div class="d-flex flex-column align-items-center p-4 gap-4">
            <h1>{{ $t('about.about_h1') }}</h1>
            <div class="d-inline-flex shadow rounded bg-light p-4"></div>
        </div>

        <AnimalFormVue></AnimalFormVue>
    </main>
</template>

<style></style>