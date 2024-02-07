import { createApp } from 'vue'
import App from './App.vue'
import router from './router'


// Paquete para la gestion de idiomas.
// Vue I18n -> https://kazupon.github.io/vue-i18n

import { createI18n } from 'vue-i18n';
import messages from './locales';
const i18n = createI18n({
    legacy: false,
    locale: 'es',
    fallbackLocale: 'en',
    messages,
});



// Estilos globales para Bootstrap
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap'

const app = createApp(App)

app.use(router).use(i18n).mount('#app')
app.config.globalProperties.$username = 'Juan'