import './bootstrap';
import $ from 'jquery';
window.$ = window.jQuery = $;
import 'jquery.cookie';
import { createApp } from 'vue'

import App from './App.vue'
import DataTable from './components/DataTable.vue'
import { createPinia } from 'pinia'
import router from './routes';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
// import BootstrapVue from 'bootstrap-vue';


// vue.use(BootstrapVue);
const pinia = createPinia()
const app = createApp(App)
app.component('DataTable', DataTable)
pinia.use(piniaPluginPersistedstate)
app.use(pinia)
app.use(router)
app.use('v-select', vSelect)
app.mount("#app")
