<template>
  <div>
    <h4>Ügyfelek listája</h4>

    <table border="1" cellpadding="8" cellspacing="0">
      <thead>
        <tr>
          <th>ID</th>
          <th>Név</th>
          <th>Okmányazonosító</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="client in clients" :key="client.id">
          <tr>
            <td>{{ client.id }}</td>
            <td @click="loadCars(client.id)" style="cursor: pointer;">{{ client.name }}</td>
            <td>{{ client.idcard }}</td>
          </tr>
          <tr v-if="selectedClientId === client.id">
            <td colspan="3">
              <h3>Járművei:</h3>
              <table border="1" cellpadding="6" cellspacing="0" style="width: 100%; margin-top: 10px;">
                <thead>
                  <tr>
                    <th>Autó ID</th>
                    <th>Típus</th>
                    <th>Regisztráció</th>
                    <th>Saját márka</th>
                    <th>Balesetek száma</th>
                    <th>Utolsó esemény</th>
                    <th>Utolsó esemény ideje</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-for="car in cars" :key="car.id">
                    <tr>
                      <td @click="loadServices(car.car_id)" style="cursor: pointer;">{{ car.car_id }}</td>
                      <td>{{ car.type }}</td>
                      <td>{{ car.registered }}</td>
                      <td>{{ car.ownbrand === 1 ? 'IGEN' : 'NEM' }}</td>
                      <td>{{ car.accident }}</td>
                      <td>{{ car.last_event || '-' }}</td>
                      <td>{{ car.last_event_time || '-' }}</td>
                    </tr>
                  </template>
                  <!-- Szerviznapló mindig az utolsó autósor után jelenik meg -->
                  <tr v-if="services.length > 0">
                    <td colspan="7">
                      <ServiceLog :services="services" />
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </template>
      </tbody>
    </table>
  </div>
</template>




<script>
import axios from 'axios';
import ServiceLog from './ServiceLog.vue';


export default {
  components: {
    ServiceLog
  },
  name: 'ClientList',
  data() {
    return {
      clients: [],
      cars: [],
      services: [],
      selectedClientId: null,
      selectedCarId: null
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
        if (this.selectedClientId === clientId) {
          // Ugyanarra kattintott → zárjuk be
          this.selectedClientId = null;
          this.cars = [];
          this.services = [];
          this.selectedCarId = null;
          return;
        }

        // Új ügyfélre kattintott
        const response = await axios.get(`/api/clients/${clientId}/cars`);
        this.cars = response.data;
        this.services = [];
        this.selectedClientId = clientId;
        this.selectedCarId = null;
      } catch (error) {
        console.error('Hiba az autók lekérésekor:', error);
      }
    },
    async loadServices(carId) {
      try {
        if (this.selectedCarId === carId) {
          // Ha újra ugyanarra az autóra kattintott → zárjuk be
          this.selectedCarId = null;
          this.services = [];
          return;
        }

        // Új autó, betöltjük a szerviznaplót
        const response = await axios.get(`/api/clients/${this.selectedClientId}/cars/${carId}/services`);
        this.services = response.data;
        this.selectedCarId = carId;
        console.log('Betöltött szerviznaplók:', this.services);
      } catch (error) {
        console.error('Hiba a szerviznapló lekérésekor:', error);
      }
    },
    formatDocumentId(service) {
        if (!service.document_id || service.document_id === '0') {
          return '-';
        }
        return service.document_id;
      },
  }
      
};
</script>
<style scoped>
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
  margin-top: 0.1rem;
  font-size: 1.2rem;
  font-weight: bold;
}

h4 {
  margin-top: 2.5rem;
  margin-bottom: 0.5rem;
  font-size: 1.5rem;
  font-weight: bold;
}
</style>
