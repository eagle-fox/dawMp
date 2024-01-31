<script setup>
import NavBar from '@/components/NavBar.vue'
import { IconMapPinFilled } from '@tabler/icons-vue';

</script>

<template>
    <NavBar></NavBar>
    <main class="mt-5 d-flex justify-content-center align-items-center flex-column gap-2">
        <div class="d-flex justify-content-center align-items-center flex-column gap-5 p-10">
            <div>
                <h1 class="text-center">{{ $t('home_t1') }}</h1>
                <p class="text-center">{{$t('home_t2')}}</p>
            </div>

            <div>
                <button class="btn btn-primary text-center">{{$t('home_bt1')}} <IconMapPinFilled class="mb-1 levitate"></IconMapPinFilled></button>
            </div>
        </div>

        <div class="bg-primary w-100 d-flex justify-content-center align-items-center p-4 flex-md-row flex-column">
            <div class="container max-width-xxl w-25 p-4 text-light">
                <h4>{{$t('home_t3')}}</h4>
            </div>
            <div class="container max-width-xxl w-25 p-4 text-light bg-secondary d-flex justify-content-center align-items-center" style="height: 200px">
                Image
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

@keyframes animationLevitate {
    0%, 100% {
        transform: translateY(2px);
    }
    50% {
        transform: translateY(-2px);
    }
}

.markPos{
    position: relative;
    top: -20px;
    left: 200px;
}

.levitate{
    animation: animationLevitate 2s ease-in-out infinite;
}

.p-10{
    padding: 60px;
}
</style>
