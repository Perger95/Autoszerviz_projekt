import './bootstrap';
import { createApp } from 'vue';
import ClientList from './components/ClientList.vue';

const app = createApp({});
app.component('client-list', ClientList);
app.mount('#app');
