<template>
  <div v-if="cars.length > 0">
    <h3>Járművei:</h3>
    <table border="1" cellpadding="8" cellspacing="0">
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
    table {
    width: 100%;
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
</style>
