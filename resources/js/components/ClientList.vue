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

    <div v-if="cars.length > 0">
      <h3>Autók</h3>
      <table border="1" cellpadding="8" cellspacing="0">
        <thead>
          <tr>
            <th>Autó ID</th>
            <th>Típus</th>
            <th>Regisztráció</th>
            <th>Saját márka</th>
            <th>Balesetek száma</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="car in cars" :key="car.id">
            <td @click="loadServices(car.car_id)" style="cursor: pointer;">{{ car.car_id }}</td>
            <td>{{ car.type }}</td>
            <td>{{ car.registered }}</td>
            <td>{{ car.ownbrand }}</td>
            <td>{{ car.accident }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="services.length > 0">
      <h3>Szerviznapló</h3>
      <table border="1" cellpadding="8" cellspacing="0">
        <thead>
          <tr>
            <th>Alkalom sorszáma</th>
            <th>Esemény neve</th>
            <th>Esemény időpontja</th>
            <th>Munkalap azonosító</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="service in services" :key="service.id">
            <td>{{ service.lognumber }}</td>
            <td>{{ service.event }}</td>
            <td>{{ service.event_time || service.registered }}</td>
            <td>{{ service.document_id }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'ClientList',
  data() {
    return {
      clients: [],
      cars: [],
      services: [],
      selectedClientId: null
    };
  },
  mounted() {
    this.fetchClients();
  },
  methods: {
    async fetchClients() {
      try {
        const response = await axios.get('/api/clients');
        this.clients = response.data;
      } catch (error) {
        console.error('Hiba az ügyfelek lekérésekor:', error);
      }
    },
    async loadCars(clientId) {
      try {
        const response = await axios.get(`/api/clients/${clientId}/cars`);
        this.cars = response.data;
        this.services = [];
        this.selectedClientId = clientId; // tároljuk a kiválasztott ügyfél ID-jét
      } catch (error) {
        console.error('Hiba az autók lekérésekor:', error);
      }
    },
    async loadServices(carId) {
      try {
        const response = await axios.get(`/api/clients/${this.selectedClientId}/cars/${carId}/services`);
        this.services = response.data;
      } catch (error) {
        console.error('Hiba a szerviznapló lekérésekor:', error);
      }
    }
  }
};
</script>
