import { createApp } from 'vue';
import App from './App.vue';

import router from './router';
import { createPinia } from 'pinia';

import { useAuthStore } from '@/stores/auth';

/* GLOBAL STYLES */
import './styles/global.css';

const app = createApp(App);

app.use(createPinia());
app.use(router);

app.mount('#app');

const auth = useAuthStore();
auth.loadUser();
