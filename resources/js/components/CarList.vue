<template>
    <div v-if="cars.length > 0" class="car-list">
        <h3>Járművei:</h3>
        <table>
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
                <tr v-for="car in cars" :key="car.id">
                    <td @click="$emit('car-selected', car.car_id)" style="cursor: pointer;">
                        {{ car.car_id }}
                    </td>
                    <td>{{ car.type }}</td>
                    <td>{{ car.registered }}</td>
                    <td>{{ car.ownbrand === 1 ? 'IGEN' : 'NEM' }}</td>
                    <td>{{ car.accident }}</td>
                    <td>{{ car.last_event || '-' }}</td>
                    <td>{{ car.last_event_time || '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
    name: 'CarList',
    props: {
    cars: {
    type: Array,
    required: true
    }
    }
    };
</script>

<style scoped="">
    .car-list {
    margin-top: 1.5rem;
    padding: 0.8rem 1rem;
    border-radius: 12px;
    background-color: #ffffff;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
    font-size: 14px; /* kisebb szöveg az egész komponensre */
    }

    table {
    width: 640px;
    border-collapse: collapse;
    margin-top: 1rem;
    }

    th, td {
    border: 1px solid #ccc;
    padding: 4px 10px;
    text-align: left;
    font-size: 15px;
    vertical-align: middle;
    }

    th {
    background-color: #a3bcd9;
    font-weight: bold;
    }


    h3 {
    margin-top: 0.5rem;
    font-size: 1.2rem;
    font-weight: bold;
    }
</style>
