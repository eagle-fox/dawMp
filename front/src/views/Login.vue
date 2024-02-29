<script>
import NavBar from '@/components/NavBar.vue'
import { styleAssets } from '@/assets/config.json'

export default {
    name: 'Login',
    components: {
        NavBar,
    },
    data() {
        return {
            svgFile: null,
        }
    },
    methods: {
        loadSvgFile() {
            this.svgFile = 'src/assets/' + styleAssets.svgData.typoBackground
        },
    },
    mounted() {
        this.loadSvgFile()
    },
}
</script>

<template>
    <NavBar></NavBar>
    <div
        :style="{ 'background-image': 'url(' + svgFile + ')' }"
        class="main-container svg-path"
    >
        <div
            class="d-inline-block d-flex justify-content-center align-items-center all bg-light rounded"
        >
            <div class="shadow p-4 rounded">
                <div class="d-flex justify-content-center">
                    <img alt="Logo" src="../assets/logo_circle.svg" width="64" />
                </div>
                <div
                    class="d-flex justify-content-center align-items-center flex-column gap-2"
                >
                    <form>
                        <div class="mb-3">
                            <label class="form-label" for="name">{{
                                    $t('login.login_username')
                                }}</label>
                            <input
                                id="name"
                                aria-describedby="name"
                                class="form-control"
                                type="text"
                            />
                            <div id="name" class="form-text">
                                {{ $t('login.login_message') }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="exampleInputPassword1">{{
                                    $t('login.login_password')
                                }}</label>
                            <input
                                id="exampleInputPassword1"
                                class="form-control"
                                type="password"
                            />
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a class="btn btn-primary" type="submit">{{
                                    $t('login.login_t1')
                                }}</a>
                            <router-link
                                class="btn btn-primary"
                                to="/register"
                                type="submit"
                            >{{ $t('login.login_message1') }}
                            </router-link
                            >
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.main-container {
    height: calc(100vh - 58px);
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>