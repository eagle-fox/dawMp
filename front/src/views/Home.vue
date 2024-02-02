<script setup>
import NavBar from '@/components/NavBar.vue'
import { IconMapPinFilled, IconMap2, IconChartHistogram} from '@tabler/icons-vue';
<<<<<<< HEAD
import Mapa from "@/components/Mapa.vue";
=======
>>>>>>> db78cb2c12deac467fc03329829d1687a6af529b

</script>

<template>
    <NavBar></NavBar>
    <main class="mt-5 d-flex justify-content-center align-items-center flex-column gap-2">
        <div class="d-flex justify-content-center align-items-center flex-column gap-5 p-10">
            <div>
              <Mapa></Mapa>
              <h1 class="text-center">{{ $t('home.home_t1') }}</h1>
                <p class="text-center">{{$t('home.home_t2')}}</p>
            </div>

            <div>
                <router-link to="/login" class="btn btn-primary text-center">{{$t('home.home_bt1')}} <IconMapPinFilled class="mb-1 levitate"></IconMapPinFilled></router-link>
            </div>
        </div>

        <div class="bg-primary w-100 d-flex justify-content-center align-items-center p-4 flex-md-row flex-column">
            <div class="max-width-xxl p-4 text-light textFormat">
                <h4 class="text-center">{{$t('home.home_t3')}}</h4>
            </div>

            <div class="max-width-xxl p-4 text-light d-flex justify-content-center align-items-center gap-4 flex-column">
                <h5>{{$t('home.home_t4')}}</h5>
                <div class="d-flex justify-content-center align-items-center flex-column gap-2" style="max-width: 70%">

                    <div class="d-flex justify-content-center gap-4 bg-light p-2 rounded">
                        <IconMap2 size="32" class="mt-2" color="black"></IconMap2>
                        <div class="text-dark">{{$t('home.home_bd1')}}</div>
                    </div>

                    <div class="d-flex justify-content-center gap-4 bg-light p-2 rounded">
                        <IconMapPinFilled size="32" class="mt-2" color="black"></IconMapPinFilled>
                        <div class="text-dark ">{{$t('home.home_bd2')}}</div>
                    </div>

                    <div class="d-flex justify-content-center gap-4 bg-light p-2 rounded">
                        <IconChartHistogram size="32" class="mt-2" color="black"></IconChartHistogram>
                        <div class="text-dark">{{$t('home.home_bd3')}}</div>
                    </div>
                </div>
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

.textFormat{
    width: 30%;
}

.caract{
    max-width: 60%;
}

@media only screen and (max-width: 860px) {
    .textFormat{
        width: 100%;
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