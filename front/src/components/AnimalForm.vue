<script>
import { IconAlertCircleFilled } from '@tabler/icons-vue'
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import parseUrl from '@/assets/js/miscelaneus'
import Query from '@/types/Query.js'
import URL from '@/types/URL.js'
import BasicAuth from '@/types/BasicAuth.js'
import Cookies from 'js-cookie'
import BearerToken from '@/types/BearerToken.js'
import { faker, fakerDE, fakerES, fakerPT_PT } from '@faker-js/faker'
import UUID from '@/types/UUID';

export default {
    name: 'AnimalForm',
    components: {
        IconAlertCircleFilled,
    },
    data() {
        return {
            authCode: '',
            authCodeValidation: true,
            selectedDate: null
        }
    },
    methods: {
        checkCodeRegex() {
            let regex = /^\d{2}[a-zA-Z]{2}-\d{4}-\d{4}$/ // 21hu-3412-1523 This code works
            if (!regex.test(this.authCode)) {
                this.authCodeValidation = false
            } else {
                this.authCodeValidation = true
                document.getElementById('errorCode').classList.toggle('oculto')
            }
        }, async sendCreateAnimalRequest() {
            try {
                let url = JSON.stringify(this.$config.devConfig.apiServer);
                let connectData = parseUrl(url);
                console.log(connectData);
                let myUrl = new URL(connectData[0], connectData[1], connectData[2]);
                let uuid = faker.string.uuid();

                let query = new Query(myUrl).withAuth(new BearerToken(this.$store.getters.getUserSession.token));
                let response = await query.postIotDevice(
                    { uuid: new UUID, name: 'Federico', especie: 'Alacran', cumpleaños: '10-10-2003' }
                );

                console.log(response);
                return response;
                
            } catch (error) {
                console.error("Error al enviar la solicitud:", error);
                return null; // o manejar el error de otra manera según tus necesidades
            }
        }
    },
    mounted() {
        flatpickr(this.$refs.datepicker, {
            dateFormat: 'd-m-Y'
        });
        this.sendCreateAnimalRequest()

    }
}
</script>

<template>
    <div class="bg-light p-4 shadow rounded-2 d-flex gap-4 flex-column">
        <h3>{{ $t('form_a.arf_t1') }}</h3>

        <div class="d-flex justify-content-center align-items-center flex-column gap-2">
            <form>
                <div class="mb-3">
                    <label class="form-label" for="exampleInputEmail1">{{
            $t('form_a.arf_t2')
        }}</label>
                    <input id="exampleInputEmail1" aria-describedby="emailHelp" class="form-control" type="email" />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="animalBreed">{{
                $t('form_a.arf_t3')
            }}</label>
                    <select id="animalBreed" aria-label="Default select example" class="form-select">
                        <option selected value="dog">{{ $t('form_a.arf_b1') }}</option>
                        <option value="cat">{{ $t('form_a.arf_b2') }}</option>
                        <option value="rabbit">{{ $t('form_a.arf_b3') }}</option>
                        <option value="hamster">{{ $t('form_a.arf_b4') }}</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="date">{{ $t('miscelaneus.birthday_date') }}</label>
                    <input ref="datepicker" class="form-control" type="text" v-model="selectedDate"
                        placeholder="Selecciona una fecha">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="">{{ $t('home.home_js1') }}</label>
                    <input id="autoFill" ref="autoFillInput" v-model="authCode" class="form-control" type="text"
                        @input="checkCodeRegex()" />
                    <span class="error" v-if="!authCodeValidation">
                        <div id="errorCode" class="errorCode alert alert-danger mt-2 customAlert text-center"
                            role="alert">
                            {{ $t('form_a.arf_js1') }}
                            <IconAlertCircleFilled class="mb-1"></IconAlertCircleFilled>
                        </div>
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <button class="btn btn-primary" type="submit">
                        {{ $t('home.home_js2') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
.customAlert {
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