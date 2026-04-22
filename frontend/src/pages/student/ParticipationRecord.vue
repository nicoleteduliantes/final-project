<template>
  <div>
    <h1>Participation Record</h1>

    <table>
      <tr>
        <th>Event</th>
        <th>Role</th>
      </tr>

      <tr v-for="event in events" :key="event.id">
        <td>{{ event.name }}</td>
        <td>{{ event.role }}</td>
      </tr>
    </table>
  </div>
</template>

<script setup>
const events = [
  { id: 1, name: "Hackathon", role: "Participant" }
];
</script>