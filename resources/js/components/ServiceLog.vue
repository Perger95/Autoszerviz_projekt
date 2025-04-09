<template>
  <div v-if="services.length > 0" class="service-list">
    <h3>Szerviznapló:</h3>
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
          <td>{{ formatEventTime(service) }}</td>
          <td>{{ service.document_id && service.document_id !== '0' ? service.document_id : '-' }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
    export default {
      name: 'ServiceLog',
      props: {
        services: {
          type: Array,
          required: true
        }
      },
      methods: {
      formatEventTime(service) {
        if (service.event === 'regisztralt') {
          return service.registered || '-';
        }
        return service.event_time || service.eventtime || '-';
      },
      formatDocumentId(service) {
        if (!service.document_id || service.document_id === '0') {
          return '-';
        }
        return service.document_id;
      }
    }
};
</script>
<style scoped="">
    .service-list {
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

    tbody tr:nth-child(even) {
    background-color: #fafafa;
    }

    h3 {
    margin-top: 0.5rem;
    font-size: 1.2rem;
    font-weight: bold;
    }
</style>
