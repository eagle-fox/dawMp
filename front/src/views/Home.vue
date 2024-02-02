<script >
import NavBar from '@/components/NavBar.vue'
import { IconMapPinFilled, IconMap2, IconChartHistogram} from '@tabler/icons-vue';
import { ref } from 'vue'
import Mapa from "@/components/Mapa.vue";


export default {
  name:'Home',
  data() {
    return {
      inputText: ''
    };
  },
  components:{
    Mapa,
    IconMapPinFilled,
    IconMap2,
    IconChartHistogram,
    NavBar
  },
  mounted() {
    window.addEventListener('scroll', this.handleScroll);
  },
  destroyed() {
    window.removeEventListener('scroll', this.handleScroll);
  },
  methods: {
    handleScroll() {
      const rect = this.$refs.autoFillInput.getBoundingClientRect();
      const isVisible = rect.top < window.innerHeight && rect.bottom >= 0;

      if (isVisible) {
        this.$nextTick(() => {
          this.fillInput('1e38fc04-5779-4561 ', 100);
        });
        window.removeEventListener('scroll', this.handleScroll);
      }
    },
    fillInput(text, speed) {
      const inputElement = this.$refs.autoFillInput;
      let index = 0;

      function fill() {
        if (index < text.length) {
          inputElement.value += text.charAt(index);
          index++;
          setTimeout(fill, speed);
        }
      }
      fill();
    },
    handleInput(event) {
      if (event.target.value.length < this.inputText.length) {
        event.target.value = this.inputText;
      } else {
        this.inputText = event.target.value;
      }
    }
  }
};

</script>

<template>
  <NavBar></NavBar>
  <Mapa></Mapa>
  <main class="mt-5 d-flex justify-content-center align-items-center flex-column gap-2">
    <div class="d-flex justify-content-center align-items-center flex-column gap-5 p-10">
      <div>
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

    <div class="p-4 d-flex w-100 justify-content-center align-items-center flex-md-row flex-column resizeGap ">

      <div class=" flex-column gap-1 d-flex justify-content-center align-items-center mx-width-card">
        <h3 class="">{{ $t('home.home_t5') }}</h3>
        <p class="adjustText">{{$t('home.home_t6')}}</p>
        <img src="../assets/test.png" class="testImage" width="400px" alt="">
      </div>

      <div class="d-flex flex-column gap-1">
        <h3>{{ $t('home.home_t7') }}</h3>
        <p class="">{{$t('home.home_t8')}}</p>

        <div class="mt-4 shadow p-3 rounded">
          <label for="exampleInputEmail1" class="form-label">{{$t('home.home_js1')}}</label>
          <input type="text" class="form-control" id="autoFill" ref="autoFillInput" @input="handleInput">

          <div class="mt-4">
            <button type="submit" class="btn btn-primary">{{$t('home.home_js2')}}</button>
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

.testImage{
  transform: rotate3d(4, 1, 1, -25deg);
}

.adjustText{
  max-width: 600px;
  height: auto;
  overflow: auto;
  word-wrap: break-word;
  white-space: normal;
}

.mx-width-card{
  max-width: 80%;
}

.resizeGap{
  gap: 200px;
}

.textFormat{
  width: 30%;
}

@media only screen and (max-width: 860px) {
  .textFormat{
    width: 100%;
  }
  .testImage{
    width: 250px;
  }
  .mx-width{
    max-width: 100%;
  }
  .adjustText{
    max-width: 100%;
  }
  .resizeGap{
    gap: 70px;
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