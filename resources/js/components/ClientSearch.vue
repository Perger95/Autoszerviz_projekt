<template>
    <div style="min-height: 300px; text-align: center;">

        <div style="display: inline-block; text-align: left; width: 600px;">
            <!-- Kereső űrlap -->
            <div class="card">
                <h2>Ügyfél keresés</h2>

                <div class="form-row">
                    <label for="name">Név</label>
                    <input v-model="name" id="name" placeholder="Pl. Kovács" />
                </div>

                <div class="form-row">
                    <label for="idcard">Okmányazonosító</label>
                    <input v-model="idcard" id="idcard" placeholder="ABC123456" />
                </div>

                <button @click="search">Keresés</button>

                <div v-if="error" class="error-message">
                    {{ error }}
                </div>

      </div>

      <!-- Kiválasztott ügyfél -->
            <!-- Ügyfél adatok blokk -->
            <div v-if="selectedClient" class="client-data">
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

                <!-- Csak a gomb marad itt -->
                <div v-if="cars.length > 0">
                    <button @click="toggleView">
                        {{ showCars || showServices ? 'Járművek elrejtése' : 'Járművek megtekintése' }}
                    </button>
                </div>
            </div>
            <!-- ⬇⬇⬇ ITT külön divbe tesszük a listákat, a teljes ügyfél blokk alatt -->
            <div v-if="showCars || showServices" class="client-cars" style="margin-top: 1.5rem;">
                <CarList v-if="showCars" :cars="cars" @car-selected="loadServices" />
                <ServiceLog v-if="showServices" :services="services" />
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
    showServices: false,
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
       toggleView() {
            this.showCars = !this.showCars;
            this.showServices = !this.showServices;
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
    .card {
    background-color: #f9fafb;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    border: 1px solid #cbd5e1;
    margin-bottom: 1.0rem;
    margin-bottom: 1.0rem;
    margin-top: 2rem;
    box-shadow: 0 5px 4px rgba(0, 0, 0, 0.15);
    }

    .client-data {
    background-color: #cbd5e1;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    border: 1px solid #cbd5e1;
    box-shadow: 0 5px 4px rgba(0, 0, 0, 0.15);
    }

    .client-cars {
    top: 50%;
    left: 50%;
    transform: translate(-12%, 0%);

    background-color: #cbd5e1;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    border: 1px solid #cbd5e1;
    box-shadow: 0 5px 4px rgba(0, 0, 0, 0.15);
    width: 790px;
    }


    .form-row {
    display: flex;
    flex-direction: column;
    margin-bottom: 1rem;
    }

    .form-row label {
    font-weight: bold;
    margin-bottom: 4px;
    color: #374151;
    }

    input {
    padding: 8px 10px;
    font-size: 15px;
    border: 1px solid #ccc;
    border-radius: 6px;
    transition: border-color 0.2s ease;
    }

    input:focus {
    border-color: #4f46e5;
    outline: none;
    }

    th {
    background-color: #d6e6f2;
    font-weight: 600;
    color: #4f46e5;
    }

    th, td {
    border: 1px solid #ccc;
    padding: 4px 15px;
    text-align: left;
    vertical-align: middle;
    }

    button {
    background-color: #7a9fc4;
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

    .btn-right {
    display: inline-block;
    text-align: right;
    }


    h2 {
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 10px;
    }

    h4 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: #1f2937;
    }

    .error-message {
    color: #cc0000;
    margin-top: 10px;
    font-weight: 500;
    }

</style>
