<template>
    <div style="min-height: 300px; text-align: center;">

        <div style="display: inline-block; text-align: left; width: 600px;">
            <!-- Kereső űrlap -->
            <div style="min-height: 120px; text-align: left;">
                <h2>Ügyfél keresés</h2>

        <div>
          <div>
            <label>Név</label>
            <input v-model="name" placeholder="Pl. Kovács" />
          </div>

          <div>
            <label>Okmányazonosító</label>
            <input v-model="idcard" placeholder="ABC123456" />
          </div>
        </div>

        <button @click="search">Keresés</button>

          <div v-if="error" style="color: #cc0000; margin-top: 10px;">
              {{ error }}
          </div>

      </div>

      <!-- Kiválasztott ügyfél -->
            <div v-if="selectedClient">
                <h4>Ügyfél adatai:</h4>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <p style="margin: 0;">
                        <strong>Ügyfél azonosító:</strong> {{ selectedClient.id }}
                    </p>
                    <button @click="clearResults">❌ Bezárás</button>
                </div>

                <div>
                    <p>
                        <strong>Ügyfél neve:</strong> {{ selectedClient.name }}
                    </p>
                    <p>
                        <strong>Okmányazonosító:</strong> {{ selectedClient.idcard }}
                    </p>
                    <p>
                        <strong>Autók száma:</strong> {{ cars.length }}
                    </p>
                    <p>
                        <strong>Szerviznaplók száma:</strong> {{ services.length }}
                    </p>
                </div>

        <div v-if="cars.length > 0">
          <button @click="showCars = !showCars">
            {{ showCars ? 'Járművek elrejtése' : 'Járművek megtekintése' }}
          </button>
        </div>

        <CarList v-if="showCars" :cars="cars" @car-selected="loadServices" />
        <ServiceLog v-if="services.length > 0" :services="services" />
      </div>
    </div>
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
    showCars: false,
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
        clearResults() {
        this.cars = [];
        this.services = [];
        this.selectedCarId = null;
        this.selectedClient = null;
        this.showCars = false; 
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
    .table-container {
    margin-top: 1rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    table {
    width: 100%;
    border-collapse: collapse;
    background-color: black;
    }

    th, td {
    border: 1px solid #ccc;
    padding: 4px 15px;
    text-align: left;
    vertical-align: middle;
    }

    th {
    background-color: #000; /* fix: #00000 is not valid */
    font-weight: 600;
    color: #4f46e5;
    }

    tbody tr:nth-child(even) {
    background-color: #f9fafb;
    }

    /* ServiceLog egyedi táblázatstílusai */
    .service-log table {
    width: 60%;
    margin-top: 1rem;
    background-color: transparent; /* ne legyen fekete mint az alap */
    }

    .service-log th {
    background-color: #f0f0f0;
    font-weight: bold;
    }

    .service-log tbody tr:nth-child(even) {
    background-color: #fafafa;
    }

    button {
    background-color: #4f46e5;
    color: white;
    padding: 8px 14px;
    font-weight: 600;
    border: none;
    border-radius: 6px;
    transition: background-color 0.2s ease;
    }

    button:hover {
    background-color: #4338ca;
    }

    .card {
    background-color: #f9fafb;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.04);
    }

    .btn-right {
    display: inline-block;
    text-align: right;
    }


    h3 {
    margin-top: 1.5rem;
    font-size: 1.2rem;
    }

    h4 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: #1f2937;
    }
</style>
