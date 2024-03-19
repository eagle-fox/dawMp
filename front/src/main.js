import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import dotenv from 'dotenv'
import * as process from 'process'

const apiServer = process.env.REACT_APP_PHP_SERVER || "http://localhost:2003";
const configApi = {
  "devConfig": {
    "apiServer": apiServer,
  }
};



// Paquete para la gestion de idiomas.
// Vue I18n -> https://kazupon.github.io/vue-i18n
import { createI18n } from 'vue-i18n'
import messages from './locales'
// Estilos globales para Bootstrap
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap'

// Aplicar estilos globales
import './scss/custom.css';

import userSession from './assets/js/userSession.js'

const i18n = createI18n({
    legacy: false,
    locale: 'spain',
    fallbackLocale: 'uk',
    messages,
})

const app = createApp(App)
app.config.globalProperties.$config = configApi;
app.use(router).use(store).use(i18n).mount('#app')

const userSessionInstance = new userSession('', '', '')
app.config.globalProperties.$userSession = userSessionInstance
console.log(app.config.globalProperties.$config )