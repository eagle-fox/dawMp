<script>
import { defineComponent } from 'vue'
import QueryUi from '@/components/QueryUi.vue'
import NavBar from '@/components/NavBar.vue'
import Footer from '@/components/FooterMain.vue'

export default defineComponent({
    components: { QueryUi, Footer, NavBar },
})
</script>
<template>
    <NavBar />
    <QueryUi />
    <Footer />
</template>
<style>
body {
    background: white;
}
</style>