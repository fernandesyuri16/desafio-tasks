import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import api from '@/services/axios'

const app = createApp(App)

app.use(createPinia())
app.use(router)

router.afterEach((to) => {
    document.title = to.meta.title || 'Desafio Tasks';
});

app.mount('#app')
export { api };
