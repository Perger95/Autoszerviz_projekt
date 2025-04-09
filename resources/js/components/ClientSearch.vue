<template>
    <div class="min-h-screen flex items-start justify-center py-10 bg-gray-100">
        <div class="bg-white p-8 rounded-xl shadow-xl w-[550px]">
            <!-- Kereső űrlap -->
            <div class="space-y-6">
                <h2 class="text-3xl font-bold text-black text-center shadow-[2px_2px_4px_rgba(0,0,0,0.3)] px-4 py-2 rounded-lg inline-block mb-6">
                    Ügyfél keresés
                </h2>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Név</label>
                        <input
                          v-model="name"
                          placeholder="Pl. Kovács"
                          class="w-full border border-gray-300 bg-gray-50 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Okmányazonosító</label>
                        <input
                          v-model="idcard"
                          placeholder="ABC123456"
                          class="w-full border border-gray-300 bg-gray-50 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            />
                    </div>
                </div>

                <button
                  @click="search"
                  class="bg-indigo-600 text-white font-semibold px-6 py-2 rounded-md hover:bg-indigo-700 transition w-full"
        >
                    Keresés
                </button>

                <div v-if="error" class="text-red-500 text-sm font-medium text-center">
                    {{ error }}
                </div>
            </div>

            <!-- Több találat -->
            <div v-if="clients.length > 1" class="mt-8">
                <p class="text-gray-700 font-medium mb-3">Több találat:</p>
                <ul class="space-y-2">
                    <li
                      v-for="client in clients"
                      :key="client.id"
                      @click="selectClient(client)"
                      class="cursor-pointer text-indigo-600 hover:underline"
          >
                        {{ client.name }} ({{ client.idcard }})
                    </li>
                </ul>
            </div>

            <!-- Kiválasztott ügyfél -->
            <div v-if="selectedClient" class="mt-10 relative bg-white/95 p-10 rounded-xl shadow-[0_6px_20px_rgba(0,0,0,0.3)] text-center min-h-[520px]">
                <!-- Bezárás gomb -->
                <button
                  @click="clearResults"
                  class="absolute top-4 right-4 text-black hover:text-red-600 text-sm"
                  aria-label="Bezárás"
                  title="Bezárás"
        >
                    ❌ Bezárás
                </button>

                <!-- Cím -->
                <h2 class="text-4xl font-bold text-black mb-6 inline-block px-5 py-2 rounded-lg shadow-[2px_2px_4px_rgba(0,0,0,0.3)]">
                    Ügyfél adatai
                </h2>

                <!-- Adatok -->
                <div class="space-y-3 text-gray-800 text-left mt-6">
                    <p>
                        <strong>Ügyfél azonosító:</strong> {{ selectedClient.id }}
                    </p>
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

                <!-- Járművek megjelenítése gomb -->
                <div v-if="cars.length > 0" class="mt-6">
                    <button
                      @click="showCars = !showCars"
                      class="bg-indigo-500 text-white px-4 py-2 rounded-md font-medium shadow hover:bg-indigo-600 transition"
          >
                        {{ showCars ? 'Járművek elrejtése' : 'Járművek megtekintése' }}
                    </button>
                </div>

                <!-- Járműlista -->
                <CarList
                  v-if="showCars"
                  :cars="cars"
                  @car-selected="loadServices"
        />

                <!-- Szerviznapló -->
                <ServiceLog
                  v-if="services.length > 0"
                  :services="services"
        />
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
    overflow-x: auto;
    margin-top: 1rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    }

    th, td {
    border: 1px solid #ccc;
    padding: 4px 15px;
    text-align: left;
    vertical-align: middle;
    }

    th {
    background-color: #f3f4f6;
    font-weight: 600;
    color: #374151;
    }

    tbody tr:nth-child(even) {
    background-color: #f9fafb;
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
    box-shadow: 0 4px 8px rgba(0,0,0,0.04);
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
    h3 {
    font-size: 1.4rem;
    font-weight: bold;
    margin-bottom: 1rem;
    color: black;
    }
    h4 {
    font-weight: bold;
    margin-bottom: 0.6rem;
    color: black;
    }

</style>

<style scoped="">
    /* Csak a ServiceLog komponens táblázatára, ha itt van beágyazva */
    .service-log table {
    width: 60%;
    border-collapse: collapse;
    margin-top: 1rem;
    }

    .service-log th, .service-log td {
    border: 1px solid #ccc;
    padding: 4px 10px;
    text-align: left;
    vertical-align: middle;
    }

    .service-log th {
    background-color: #f0f0f0;
    font-weight: bold;
    }

    .service-log tbody tr:nth-child(even) {
    background-color: #fafafa;
    }
</style>
