<script>
import Cookies from 'js-cookie'
import { cookieSettings, styleAssets } from '@/assets/config.json'

export default {
    name: 'NavBar',
    methods: {
        changeLanguage(locale) {
            let flags = styleAssets.contryFlags

            if (locale in flags) {
                let flagsImage = flags[locale]
                let altText = locale

                this.languageToggleIcon = `src/assets/${flagsImage}`
                this.currentLanguageFlagAltText = altText
                this.createLanguageCookie(locale)
                this.$i18n.locale = locale
            }
        },
        // System for creating and loading cookies for the automatic language change chosen by the user.
        createLanguageCookie(data) {
            let secureStatus = cookieSettings.secure
            let sameSiteConfig = cookieSettings.sameSite

            Cookies.set('languageCookie', data, {
                expires: 365,
                sameSite: sameSiteConfig,
                secure: secureStatus,
            })
        },
        createDarkModeCookie(mode) {
            let secureStatus = cookieSettings.secure
            let sameSiteConfig = cookieSettings.sameSite

            Cookies.set('darkMode', mode, {
                expires: 365,
                sameSite: sameSiteConfig,
                secure: secureStatus,
            })
        },
        getCookieValue(cookieName) {
            return Cookies.get(cookieName)
        },
        changeLanguageCookie() {
            if (this.getCookieValue('languageCookie')) {
                this.changeLanguage(this.getCookieValue('languageCookie'))
            }
        },
        generateUUID() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(
                /[xy]/g,
                function(c) {
                    let r = (Math.random() * 16) | 0,
                        v = c === 'x' ? r : (r & 0x3) | 0x8
                    return v.toString(16)
                },
            )
        },
        clearSession() {
            // This function is in charge of clearing our user session,
            // changing it to a LogOut state and creating a new token.

            this.$store
                .dispatch('clearUserSession', this.generateUUID())
                .then(() => {
                    console.log(this.$store.getters.getUserSession)
                    Cookies.remove('sessionCookie')
                })
                .catch((error) => {
                    console.error('Error al crear la nueva userSession:', error)
                })
        },
    },
    data() {
        return {
            // Default language
            languageToggleIcon: 'src/assets/flags/es.svg',
            currentLanguageFlagAltText: 'es_flag',
            darkMode: false,
        }
    },
    mounted() {
        this.changeLanguageCookie()

        // Check the darkMode cookie and change both the page theme and the dark mode indicative switch.

        if (this.getCookieValue('darkMode') == 'true') {
            this.darkMode = true
            document.body.classList.toggle('dark', true)
            document.getElementById('flexSwitchCheckDefault').checked = true
        } else {
            this.darkMode = false
            document.body.classList.toggle('dark', false)
            document.getElementById('flexSwitchCheckDefault').checked = false
        }
    },
    watch: {
        // Switch between light and dark mode

        darkMode(value) {
            document.body.classList.toggle('dark', value)
            this.createDarkModeCookie(value)
            this.darkMode = value
        },
    },
}
</script>

<template>
    <nav id="navbar" class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a
                class="navbar-brand d-flex justify-content-center align-items-center gap-2 text-light"
                href="#"
            >
                <img
                    alt="Logo"
                    class="d-inline-block align-text-top"
                    src="../assets/logo_circle.svg"
                    width="48"
                />
                Eagle Fox
            </a>

            <button
                aria-controls="navbarNavAltMarkup"
                aria-expanded="false"
                aria-label="Toggle navigation"
                class="navbar-toggler"
                data-bs-target="#navbarNavAltMarkup"
                data-bs-toggle="collapse"
                type="button"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div id="navbarNavAltMarkup" class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <router-link
                            aria-current="page"
                            class="nav-link active text-light"
                            href="#"
                            to="/"
                        >
                            {{ $t('miscelaneus.home') }}
                        </router-link>
                    </li>
                </ul>

                <div class="d-flex gap-4 align-items-center">
                    <!-- <button  class="btn btn-primary" @click="clearSession()">Log Out</button> -->
                    <div class="form-check form-switch d-flex">
                        <label
                            class="form-check-label"
                            for="flexSwitchCheckChecked"
                            style="margin-right: 45px"
                        >🌞</label
                        >
                        <input
                            id="flexSwitchCheckDefault"
                            v-model="darkMode"
                            class="form-check-input"
                            role="switch"
                            type="checkbox"
                        />
                        <label
                            class="form-check-label"
                            for="flexSwitchCheckChecked"
                            style="margin-left: 4px"
                        >🌙</label
                        >
                    </div>

                    <div class="dropstart">
                        <button
                            aria-expanded="false"
                            class="btn btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown"
                            type="button"
                        >
                            <img
                                :alt="currentLanguageFlagAltText"
                                :src="languageToggleIcon"
                                class="flag-icon"
                                width="32"
                            />
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start">
                            <li class="d-flex justify-content-center align-items-center">
                                <img alt="es_flag" src="../assets/flags/es.svg" width="32" />
                                <a
                                    class="dropdown-item w-50"
                                    href="#"
                                    @click="changeLanguage('spain')"
                                >{{ $t('lang.lang_es') }}</a
                                >
                            </li>
                            <li class="d-flex justify-content-center align-items-center">
                                <img alt="gl_flag" src="../assets/flags/gl.svg" width="32" />
                                <a
                                    class="dropdown-item w-50"
                                    href="#"
                                    @click="changeLanguage('galician')"
                                >{{ $t('lang.lang_gl') }}</a
                                >
                            </li>
                            <li class="d-flex justify-content-center align-items-center">
                                <img alt="sh_flag" src="../assets/flags/sh.svg" width="32" />
                                <a
                                    class="dropdown-item w-50"
                                    href="#"
                                    @click="changeLanguage('uk')"
                                >{{ $t('lang.lang_sh') }}</a
                                >
                            </li>
                            <li class="d-flex justify-content-center align-items-center">
                                <img alt="de_flag" src="../assets/flags/de.svg" width="32" />
                                <a
                                    class="dropdown-item w-50"
                                    href="#"
                                    @click="changeLanguage('germany')"
                                >{{ $t('lang.lang_de') }}</a
                                >
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <hr />
    </nav>
</template>

<style scoped>
.flag-icon {
    margin-left: 3px;
}

#navbar {
    background-color: #1e1e1e;
    border-bottom: var(--accent) 2px solid;
    padding: 1em 4em;
}
</style>