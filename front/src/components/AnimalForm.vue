<script>
import {IconAlertCircleFilled} from '@tabler/icons-vue';

export default {
  name: 'AnimalForm',
  components: {
    IconAlertCircleFilled
  },
  data() {
    return{
      authCode: '',
      authCodeValidation: true
    }
  },
  methods: {
    checkCodeRegex(){
      let regex = /^\d{2}[a-zA-Z]{2}-\d{4}-\d{4}$/; // 21hu-3412-1523 This code works
      if(!regex.test(this.authCode)){
        this.authCodeValidation = false
      }else{
        this.authCodeValidation = true
        document.getElementById("errorCode").classList.toggle("oculto");
      }
    }
  }
}

</script>

<template>
  <div class="p-4 shadow rounded-2 d-flex gap-4 flex-column">
    <h3>{{$t('form_a.arf_t1')}}</h3>

    <div class="d-flex justify-content-center align-items-center flex-column gap-2">
      <form>
        <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label">{{$t('form_a.arf_t2')}}</label>
          <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
          <label for="animalBreed" class="form-label">{{$t('form_a.arf_t3')}}</label>
          <select class="form-select" id="animalBreed" aria-label="Default select example">
            <option selected value="dog">{{$t('form_a.arf_b1')}}</option>
            <option value="cat">{{$t('form_a.arf_b2')}}</option>
            <option value='rabbit'>{{$t('form_a.arf_b3')}}</option>
            <option value="hamster">{{$t('form_a.arf_b4')}}</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="" class="form-label">{{$t('home.home_js1')}}</label>
          <input type="text" v-model="authCode" @input="checkCodeRegex()" class="form-control" id="autoFill" ref="autoFillInput">
          <span class="error" v-if="!authCodeValidation">
            <div id="errorCode" class="errorCode alert alert-danger mt-2 customAlert text-center" role="alert">
              {{$t('form_a.arf_js1')}}
              <IconAlertCircleFilled class="mb-1"></IconAlertCircleFilled>
            </div>
          </span>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <button type="submit" class="btn btn-primary">{{$t('home.home_js2')}}</button>
        </div>
      </form>
    </div>

  </div>
</template>

<style scoped>
  .customAlert{
    padding: 6px;
  }

  .errorCode {
    opacity: 1;
    transition: opacity 1s ease-in-out;
  }

  .errorCode.oculto {
    opacity: 0;
  }
</style>