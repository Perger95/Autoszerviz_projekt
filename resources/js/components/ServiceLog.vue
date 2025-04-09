<template>
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
    table {
    width: 55%;
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
