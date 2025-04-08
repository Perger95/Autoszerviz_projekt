<template>
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
          <td @click="$emit('car-selected', car.car_id)" style="cursor: pointer;">
            {{ car.car_id }}
          </td>
          <td>{{ car.type }}</td>
          <td>{{ car.registered }}</td>
          <td>{{ car.ownbrand === 1 ? 'IGEN' : 'NEM' }}</td>
          <td>{{ car.accident }}</td>
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
