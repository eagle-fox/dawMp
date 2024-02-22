<script>
import Cookies from 'js-cookie';
import { styleAssets } from '@/assets/config.json';

export default {
    name: 'NavBar',
    methods: {
        changeLanguage(locale) {

            let flags = styleAssets.contryFlags;
            

            if (locale in flags) {
                let flagsImage = flags[locale];
                let altText = locale;

                this.languageToggleIcon = `src/assets/${flagsImage}`
                this.currentLanguageFlagAltText = altText
                this.createLanguageCookie(locale)
                this.$i18n.locale = locale
            }
        },
        // System for creating and loading cookies for the automatic language change chosen by the user.
        createLanguageCookie(data) {
            Cookies.set('languageCookie', data, { expires: 365, sameSite: 'None', secure: true })
        },
        getCookieValue() {
            return Cookies.get('languageCookie')
        },
        changeLanguageCookie() {
            if (this.getCookieValue()){
                this.changeLanguage(this.getCookieValue())
            }
        },
        generateUUID() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                let r = (Math.random() * 16) | 0,
                    v = c === 'x' ? r : (r & 0x3) | 0x8
                return v.toString(16)
            })
        },
        clearSession(){

            // This function is in charge of clearing our user session,
            // changing it to a LogOut state and creating a new token.

            this.$store.dispatch('clearUserSession', this.generateUUID())
                .then(() => {
                    console.log(this.$store.getters.getUserSession)
                    Cookies.remove('sessionCookie')
                })
                .catch(error => {
                    console.error('Error al crear la nueva userSession:', error)
                })
        }
    },
    data() {
        return {
            // Default language
            languageToggleIcon: 'src/assets/flags/es.svg',
            currentLanguageFlagAltText: 'es_flag',
        }
    },
    mounted() {
        this.changeLanguageCookie()
    },
}

</script>

<template>
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <a class="navbar-brand d-flex justify-content-center align-items-center gap-2" href="#">
                <img src="../assets/logo_circle.svg" alt="Logo" width="48" class="d-inline-block align-text-top">
                Eagle Fox
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                    aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <router-link to="/" class="nav-link active" aria-current="page" href="#">
                            {{ $t('miscelaneus.home') }}
                        </router-link>
                    </li>


                </ul>

                <div class="d-flex gap-4">
                    <button  class="btn btn-primary" @click="clearSession()">Log Out</button>

                    <div class="dropstart">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <img :src="languageToggleIcon" width="32" :alt="currentLanguageFlagAltText" class="flag-icon">
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start">
                            <li class="d-flex justify-content-center align-items-center">
                                <img src="../assets/flags/es.svg" width="32" alt="es_flag">
                                <a class="dropdown-item w-50" @click="changeLanguage('spain')" href="#">{{ $t('lang.lang_es')
                                    }}</a>
                            </li>
                            <li class="d-flex justify-content-center align-items-center">
                                <img src="../assets/flags/gl.svg" width="32" alt="gl_flag">
                                <a class="dropdown-item w-50" @click="changeLanguage('galician')" href="#">{{ $t('lang.lang_gl')
                                    }}</a>
                            </li>
                            <li class="d-flex justify-content-center align-items-center">
                                <img src="../assets/flags/sh.svg" width="32" alt="sh_flag">
                                <a class="dropdown-item w-50" @click="changeLanguage('uk')" href="#">{{ $t('lang.lang_sh')
                                    }}</a>
                            </li>
                            <li class="d-flex justify-content-center align-items-center">
                                <img src="../assets/flags/de.svg" width="32" alt="de_flag">
                                <a class="dropdown-item w-50" @click="changeLanguage('germany')" href="#">{{ $t('lang.lang_de')
                                    }}</a>
                            </li>
                        </ul>
                    </div>
                </div>


            </div>


        </div>
    </nav>
</template>

<style scoped>
.flag-icon {
    margin-left: 3px;
}
</style>