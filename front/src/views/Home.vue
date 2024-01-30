<script setup>
import NavBar from '@/components/NavBar.vue'


</script>

<template>
    <NavBar></NavBar>
    <main>
        <div class="d-flex justify-content-center align-items-center p-10">
            <div>
                <h1>{{ $t('home_t1') }}</h1>
            </div>
        </div>
    </main>
</template>

<style scoped>
*{
    font-family: 'BaseFontTest2';
}

@font-face {
    font-family: 'BaseFont';
    src: url("../assets/fonts/PTSans-Regular.ttf");
}

@font-face {
    font-family: 'BaseFontTest';
    src: url("../assets/fonts/Outfit-Regular.ttf");
}

@font-face {
    font-family: 'BaseFontTest2';
    src: url("../assets/fonts/Outfit-Light.ttf");
}

.p-10{
    padding: 60px;
}
</style>
