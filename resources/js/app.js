import './bootstrap';
import { createApp } from 'vue';
import ClientList from './components/ClientList.vue';
import ClientSearch from './components/ClientSearch.vue';

const app = createApp({});

app.component('client-list', ClientList);
app.component('client-search', ClientSearch);

app.mount('#app');
