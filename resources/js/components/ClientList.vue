<template>
    <div>
        <h2>Ügyfelek</h2>

        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Név</th>
                    <th>Okmányazonosító</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="client in clients" :key="client.id">
                    <td>{{ client.id }}</td>
                    <td @click="loadCars(client.id)" style="cursor: pointer;">{{ client.name }}</td>
                    <td>{{ client.idcard }}</td>
                </tr>
            </tbody>
        </table>

        <CarList
            v-if="cars.length > 0"
            :cars="cars"
            @car-selected="loadServices"
        />

        <ServiceLog
            :services="services"
        />
    </div>
</template>

<script>
import axios from 'axios';
import CarList from './CarList.vue';
import ServiceLog from './ServiceLog.vue';

export default {
    name: 'ClientList',
    components: {
        CarList,
        ServiceLog
    },
    data() {
        return {
            clients: [],
            cars: [],
            services: [],
            selectedClientId: null
        };
    },
    mounted() {
        axios.get('/api/clients')
            .then(response => {
                this.clients = response.data;
            })
            .catch(error => {
                console.error('Hiba az adatok lekérésekor:', error);
            });
    },
    methods: {
        loadCars(clientId) {
            this.selectedClientId = clientId;
            axios.get(`/api/clients/${clientId}/cars`)
                .then(response => {
                    this.cars = response.data;
                    this.services = []; // reset services when changing client
                })
                .catch(error => {
                    console.error('Autók betöltése sikertelen:', error);
                });
        },
        loadServices(carId) {
            if (!this.selectedClientId) return;

            axios.get(`/api/clients/${this.selectedClientId}/cars/${carId}/services`)
                .then(response => {
                    this.services = response.data;
                })
                .catch(error => {
                    console.error('Szerviznapló betöltése sikertelen:', error);
                });
        }
    }
}
</script>
