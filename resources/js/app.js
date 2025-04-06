import './bootstrap';
import { createApp } from 'vue'

import App from './App.vue'
import { createPinia } from 'pinia'
import router from './routes';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
// import BootstrapVue from 'bootstrap-vue';


// vue.use(BootstrapVue);
const pinia = createPinia()
const app = createApp(App)
pinia.use(piniaPluginPersistedstate)
app.use(pinia)
app.use(router)
app.mount("#app")