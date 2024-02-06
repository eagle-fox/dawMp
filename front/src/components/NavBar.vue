<script>
import Cookies from 'js-cookie';
export default {
  name: 'NavBar',
    methods: {
        changeLanguage(locale) {
            const languageMappings = {
                es: { icon: 'es.svg', altText: 'es_flag' },
                en: { icon: 'sh.svg', altText: 'sh_flag' },
                de: { icon: 'de.svg', altText: 'de_flag' },
                gl: { icon: 'gl.svg', altText: 'gl_flag' },
            };
            if (languageMappings[locale]) {
                const { icon, altText } = languageMappings[locale];
                this.languageToggleIcon = `src/assets/flags/${icon}`;
                this.currentLanguageFlagAltText = altText;
                this.createLanguageCookie(locale);
                this.$i18n.locale = locale;
            }
        },
        // System for creating and loading cookies for the automatic language change chosen by the user.
        createLanguageCookie(data){
            Cookies.set('languageCookie', data, { expires: 7, sameSite: 'None', secure: true  });
        },
        getCookieValue() {
            return Cookies.get('languageCookie');
        },
        changeLanguageCookie(){
            if(this.getCookieValue())
                this.changeLanguage(this.getCookieValue())
        }
    },
    data(){
      return{
          // Default language
          languageToggleIcon: 'src/assets/flags/es.svg',
          currentLanguageFlagAltText: 'es_flag',
      }
    },
    mounted() {
        this.changeLanguageCookie();
    }
}

</script>

<template>
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <a class="navbar-brand d-flex justify-content-center align-items-center gap-2" href="#">
                <img src="../assets/logo_circle.svg" alt="Logo" width="48" class="d-inline-block align-text-top">
                Eagle Fox
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <router-link to="/" class="nav-link active" aria-current="page" href="#">{{ $t('miscelaneus.home') }}</router-link>
                    </li>
                </ul>
                <div class="dropstart">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img :src="languageToggleIcon" width="32" :alt="currentLanguageFlagAltText" class="flag-icon">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start">
                        <li class="d-flex justify-content-center align-items-center">
                            <img src="../assets/flags/es.svg" width="32" alt="es_flag">
                            <a class="dropdown-item w-50" @click="changeLanguage('es')" href="#">{{ $t('lang.lang_es') }}</a>
                        </li>
                        <li class="d-flex justify-content-center align-items-center">
                            <img src="../assets/flags/gl.svg" width="32" alt="gl_flag">
                            <a class="dropdown-item w-50" @click="changeLanguage('gl')" href="#">{{ $t('lang.lang_gl') }}</a>
                        </li>
                        <li class="d-flex justify-content-center align-items-center">
                            <img src="../assets/flags/sh.svg" width="32" alt="sh_flag">
                            <a class="dropdown-item w-50" @click="changeLanguage('en')" href="#">{{ $t('lang.lang_sh') }}</a>
                        </li>
                        <li class="d-flex justify-content-center align-items-center">
                            <img src="../assets/flags/de.svg" width="32" alt="de_flag">
                            <a class="dropdown-item w-50" @click="changeLanguage('de')" href="#">{{ $t('lang.lang_de') }}</a>
                        </li>
                    </ul>
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