<template>
  <div>
    <h3>Ügyfél keresés</h3>
    <div>
      <label>Ügyfél neve:</label>
      <input v-model="name" placeholder="Pl. Kovács" />

      <label>Okmányazonosító:</label>
      <input v-model="idcard" placeholder="ABC123456" />

      <button @click="search">Keresés</button>
    </div>

    <div v-if="error" style="color: red;">{{ error }}</div>

    <div v-if="clients.length > 1">
      <p><strong>Találatok:</strong></p>
      <ul>
        <li v-for="client in clients" :key="client.id">
          <span @click="selectClient(client)" style="cursor: pointer; color: blue;">
            {{ client.name }} ({{ client.idcard }})
          </span>
        </li>
      </ul>
    </div>

    <div v-if="selectedClient">
      <h4>Találat</h4>
      <p>Ügyfél azonosító: {{ selectedClient.id }}</p>
      <p>Ügyfél neve: {{ selectedClient.name }}</p>
      <p>Okmányazonosító: {{ selectedClient.idcard }}</p>
      <p>Autók száma: {{ selectedClient.car_count }}</p>
      <p>Szerviznaplók száma: {{ selectedClient.service_count }}</p>
    </div>

    <CarList
      v-if="cars.length > 0"
      :cars="cars"
      @car-selected="loadServices"
    />

    <ServiceLog
      v-if="services.length > 0"
      :services="services"
    />
  </div>
</template>

<script>
import axios from 'axios';
import CarList from './CarList.vue';
import ServiceLog from './ServiceLog.vue';

export default {
  name: 'ClientSearch',
  components: {
    CarList,
    ServiceLog
  },
  data() {
    return {
      name: '',
      idcard: '',
      error: '',
      clients: [],
      selectedClient: null,
      cars: [],
      services: [],
    };
  },
  methods: {
    async search() {
      this.error = '';
      this.clients = [];
      this.selectedClient = null;
      this.cars = [];
      this.services = [];


// validalasok

      if (this.name && this.idcard) {
        this.error = 'Csak az egyik mezőt töltsd ki!';
        return;
      }

      if (!this.name && !this.idcard) {
        this.error = 'Legalább az egyik mezőt ki kell tölteni!';
        return;
      }
      if (this.idcard && !/^[a-zA-Z0-9]+$/.test(this.idcard)) {
        this.error = 'Az okmányazonosító csak betűket és számokat tartalmazhat!';
        return;
      }



      try {
        const response = await axios.get('/api/client-search', {
          params: {
            name: this.name,
            idcard: this.idcard,
          },
        });

        if (Array.isArray(response.data)) {
          if (response.data.length === 1) {
            this.selectClient(response.data[0]);
          } else {
            this.clients = response.data;
          }
        } else {
          this.selectClient(response.data);
        }
      } catch (err) {
        this.error = err.response?.data?.message || 'Hiba a keresés során.';
      }
    },
    async selectClient(client) {
      this.selectedClient = client;
      await this.loadCars(client.id);
    },
    async loadCars(clientId) {
      try {
        const response = await axios.get(`/api/clients/${clientId}/cars`);
        this.cars = response.data;
        this.services = [];
      } catch (error) {
        console.error('Hiba az autók lekérésekor:', error);
      }
    },
    async loadServices(carId) {
      const clientId = this.selectedClient?.id;
      if (!clientId) return;

      try {
        const response = await axios.get(`/api/clients/${clientId}/cars/${carId}/services`);
        this.services = response.data;
      } catch (error) {
        console.error('Hiba a szerviznapló lekérésekor:', error);
      }
    }
  }
};
</script>
<style scoped="">
    table {
    width: 40%;
    border-collapse: collapse;
    margin-top: 1rem;
    }

    th, td {
    border: 1px solid #ccc;
    padding: 4px 15px;
    text-align: left;
    vertical-align: middle;
    }

    th {
    background-color: #f0f0f0;
    font-weight: bold;
    }

    tbody tr:nth-child(even) {
    background-color: #fafafa;
    }

    h3 {
    margin-top: 1.5rem;
    font-size: 1.2rem;
    }
</style>
<style scoped="">
    button {
    background-color: #007BFF;
    color: white;
    padding: 6px 12px;
    font-weight: bold;
    border: none;
    border-radius: 4px;
    }

    button:hover {
    background-color: #0056b3;
    }
    div > div:first-of-type {
    background-color: #e0e0e0;
    padding: 1rem;
    border-radius: 6px;
    color: black;
    width:  80%;
    }
    h3 {
    font-size: 1.4rem;
    font-weight: bold;
    margin-bottom: 1rem;
    color: black;
    }

</style>


