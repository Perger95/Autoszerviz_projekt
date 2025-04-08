import './bootstrap';
import { createApp } from 'vue';
import ClientList from './components/ClientList.vue';
import ClientSearch from './components/ClientSearch.vue';

const app = createApp({
    data() {
        return {
            showList: true
        };
    },
    methods: {
        toggleView() {
            this.showList = !this.showList;
        },
        handleClientSelected(clientId) {
            this.$refs.clientList.loadCars(clientId);
            this.showList = true; // visszavált a listanézetre
        }
    }
});

app.component('client-list', ClientList);
app.component('client-search', ClientSearch);

app.mount('#app');
