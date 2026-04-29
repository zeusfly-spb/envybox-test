import { createApp } from 'vue';
import App from './App.vue';
import router from './router/index.js';
import vuetify from './plugins/vuetify';


const app = createApp(App).use(router).use(vuetify);
app.mount('#app')
