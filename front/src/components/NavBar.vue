<script>
export default {
    name: 'NavBar',
    data() {
        return {
            language: 'es',
            languageElement: '',
        }
    },
    methods: {
        languageDefault(lang) {
            let esItem = `<img src="src/assets/flags/es.svg" class="" style="width: 30px" alt="" />`
            if (lang === 'es') {
                return esItem
            }
        },
        changeLangauge(lang) {
            let esItem = `<img src="src/assets/flags/es.svg" class="" style="width: 30px" alt="" />`
            let deItem = `<img src="src/assets/flags/de.svg" class="" style="width: 30px" alt="" />`
            let shItem = `<img src="src/assets/flags/sh.svg" class="" style="width: 30px" alt="" />`

            if (lang === 'es') {
                this.languageElement = esItem
            }
            if (lang === 'de') {
                this.languageElement = deItem
            }
            if (lang === 'sh') {
                this.languageElement = shItem
            }
        },
    },
    mounted() {
        this.languageElement = this.languageDefault(this.language)
    },
}
</script>

<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand me-2" href="https://mdbgo.com/">
                <img
                    src="../assets/logo_circle.svg"
                    height="48"
                    alt="Eagle-fox logo"
                    loading="lazy"
                />
            </a>

            <div
                class="collapse navbar-collapse"
                id="navbarButtonsExample"
                style="width: 400px !important"
            >
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link fs-5" href="#">EagleFox</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <button class="btn btn-primary px-3 me-2">Login</button>
                    <button type="button" class="btn btn-primary me-3">
                        Sign up for free
                    </button>
                    <div>
                        <div
                            class="collapse navbar-collapse"
                            id="navbarNavDarkDropdown"
                        >
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <button
                                        class="btn btn-light dropdown-toggle"
                                        data-bs-toggle="dropdown"
                                    >
                                        <div v-html="languageElement"></div>
                                    </button>
                                    <ul
                                        class="dropdown-menu dropdown-menu-primary"
                                    >
                                        <li>
                                            <a
                                                class="dropdown-item d-flex gap-3"
                                                @click="changeLangauge('es')"
                                                ><img
                                                    src="../assets/flags/es.svg"
                                                    class="flag"
                                                    alt=""
                                                />Español</a
                                            >
                                        </li>
                                        <li>
                                            <a
                                                class="dropdown-item d-flex gap-3"
                                                @click="changeLangauge('de')"
                                            >
                                                <img
                                                    src="../assets/flags/de.svg"
                                                    class="flag"
                                                    alt=""
                                                />German
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                class="dropdown-item d-flex gap-3"
                                                @click="changeLangauge('sh')"
                                                ><img
                                                    src="../assets/flags/sh.svg"
                                                    class="flag"
                                                    alt=""
                                                />English</a
                                            >
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<style scoped>
.flag {
    width: 30px;
}
</style>
